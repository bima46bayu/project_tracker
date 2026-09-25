<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\TaskTimelineWeek;
use App\Services\ProjectTaskService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskTimelineController extends Controller
{
    protected $taskService;

    public function __construct(ProjectTaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Show the task detail page with timeline and RAB tabs.
     */
    public function show(Project $project, ProjectTask $task)
    {
        // Ensure task belongs to project
        abort_if($task->project_id !== $project->id, 404);

        $task->load(['taskItems.masterItem.category', 'taskItems.realisasis', 'planWeeks', 'realisasiWeeks']);

        // Auto-generate weeks if none exist yet
        if ($task->planWeeks->isEmpty()) {
            $this->generateWeeksForTask($task, 'plan');
            $task->load('planWeeks');
        }

        if ($task->realisasiWeeks->isEmpty()) {
            $this->generateWeeksForTask($task, 'realisasi');
            $task->load('realisasiWeeks');
        }

        $masterItems = \App\Models\MasterItem::with('category')->get();

        return view('projects.task-detail', compact('project', 'task', 'masterItems'));
    }

    /**
     * Generate ISO weeks (Monday-Sunday) between task start_date and end_date.
     */
    protected function generateWeeksForTask(ProjectTask $task, string $type): void
    {
        $startDate = Carbon::parse($task->start_date);
        $endDate = Carbon::parse($task->end_date);

        // Find the Monday of the week containing start_date
        $currentMonday = $startDate->copy()->startOfWeek(Carbon::MONDAY);

        $weekNumber = 1;
        while ($currentMonday->lte($endDate)) {
            $currentSunday = $currentMonday->copy()->endOfWeek(Carbon::SUNDAY);

            TaskTimelineWeek::create([
                'project_task_id' => $task->id,
                'type' => $type,
                'week_number' => $weekNumber,
                'start_date' => $currentMonday->toDateString(),
                'end_date' => $currentSunday->toDateString(),
                'progress_percentage' => 0,
                'is_extra' => false,
            ]);

            $weekNumber++;
            $currentMonday->addWeek();
        }
    }

    /**
     * Save/update plan weeks progress percentages.
     */
    public function storePlan(Request $request, ProjectTask $task)
    {
        if ($task->is_plan_locked) {
            return response()->json(['message' => 'Plan is locked. Unlock it first to make changes.'], 403);
        }

        $validated = $request->validate([
            'weeks' => 'required|array',
            'weeks.*.id' => 'required|exists:task_timeline_weeks,id',
            'weeks.*.progress_percentage' => 'required|numeric|min:0|max:100',
        ]);

        foreach ($validated['weeks'] as $weekData) {
            TaskTimelineWeek::where('id', $weekData['id'])
                ->where('project_task_id', $task->id)
                ->where('type', 'plan')
                ->update(['progress_percentage' => $weekData['progress_percentage']]);
        }

        return response()->json(['message' => 'Plan saved successfully.']);
    }

    /**
     * Lock the plan (simpan permanen).
     */
    public function lockPlan(ProjectTask $task)
    {
        $task->update(['is_plan_locked' => true]);
        return response()->json(['message' => 'Plan locked permanently.', 'is_plan_locked' => true]);
    }

    /**
     * Unlock the plan (tombol ubah).
     */
    public function unlockPlan(ProjectTask $task)
    {
        $task->update(['is_plan_locked' => false]);
        return response()->json(['message' => 'Plan unlocked.', 'is_plan_locked' => false]);
    }

    /**
     * Save/update realisasi weeks and auto-update task progress.
     */
    public function storeRealisasi(Request $request, ProjectTask $task)
    {
        $validated = $request->validate([
            'weeks' => 'required|array',
            'weeks.*.id' => 'required|exists:task_timeline_weeks,id',
            'weeks.*.progress_percentage' => 'required|numeric|min:0|max:100',
        ]);

        foreach ($validated['weeks'] as $weekData) {
            TaskTimelineWeek::where('id', $weekData['id'])
                ->where('project_task_id', $task->id)
                ->where('type', 'realisasi')
                ->update(['progress_percentage' => $weekData['progress_percentage']]);
        }

        // Auto-update task progress from the last filled realisasi week
        $lastFilledWeek = $task->realisasiWeeks()
            ->where('progress_percentage', '>', 0)
            ->orderByDesc('week_number')
            ->first();

        $newProgress = $lastFilledWeek ? $lastFilledWeek->progress_percentage : 0;

        // Update task progress and status
        $status = 'TODO';
        if ($newProgress >= 100) {
            $status = 'DONE';
        } elseif ($newProgress > 0) {
            $status = 'IN_PROGRESS';
        }

        $task->update([
            'progress_percentage' => $newProgress,
            'status' => $status,
        ]);

        // Sync project status
        $this->taskService->syncProjectStatus($task->project_id);

        return response()->json([
            'message' => 'Realisasi saved successfully.',
            'progress_percentage' => $newProgress,
            'status' => $status,
        ]);
    }

    /**
     * Add an extra week for overdue tracking in Realisasi.
     */
    public function addExtraWeek(ProjectTask $task)
    {
        // Find the last realisasi week
        $lastWeek = $task->realisasiWeeks()->reorder('week_number', 'desc')->first();

        if (!$lastWeek) {
            return response()->json(['message' => 'No realisasi weeks found. Generate weeks first.'], 400);
        }

        $nextMonday = Carbon::parse($lastWeek->end_date)->addDay(); // Monday after last Sunday
        $nextSunday = $nextMonday->copy()->endOfWeek(Carbon::SUNDAY);
        $newWeekNumber = $lastWeek->week_number + 1;

        $newWeek = TaskTimelineWeek::create([
            'project_task_id' => $task->id,
            'type' => 'realisasi',
            'week_number' => $newWeekNumber,
            'start_date' => $nextMonday->toDateString(),
            'end_date' => $nextSunday->toDateString(),
            'progress_percentage' => 0,
            'is_extra' => true,
        ]);

        return response()->json($newWeek, 201);
    }

    /**
     * Add an extra week for Plan (extending the plan duration).
     */
    public function addExtraPlanWeek(ProjectTask $task)
    {
        if ($task->is_plan_locked) {
            return response()->json(['message' => 'Plan is locked. Unlock it first to make changes.'], 403);
        }

        // Find the last plan week
        $lastWeek = $task->planWeeks()->reorder('week_number', 'desc')->first();

        if (!$lastWeek) {
            return response()->json(['message' => 'No plan weeks found. Generate weeks first.'], 400);
        }

        $nextMonday = Carbon::parse($lastWeek->end_date)->addDay();
        $nextSunday = $nextMonday->copy()->endOfWeek(Carbon::SUNDAY);
        $newWeekNumber = $lastWeek->week_number + 1;

        $newWeek = TaskTimelineWeek::create([
            'project_task_id' => $task->id,
            'type' => 'plan',
            'week_number' => $newWeekNumber,
            'start_date' => $nextMonday->toDateString(),
            'end_date' => $nextSunday->toDateString(),
            'progress_percentage' => 0,
            'is_extra' => true,
        ]);

        return response()->json($newWeek, 201);
    }

    /**
     * Delete an extra week (only extra weeks can be deleted).
     */
    public function deleteWeek(ProjectTask $task, TaskTimelineWeek $week)
    {
        // Ensure week belongs to this task
        if ($week->project_task_id !== $task->id) {
            return response()->json(['message' => 'Week does not belong to this task.'], 403);
        }

        // Only allow deleting extra weeks
        if (!$week->is_extra) {
            return response()->json(['message' => 'Only extra weeks can be deleted.'], 403);
        }

        // If plan week, check lock
        if ($week->type === 'plan' && $task->is_plan_locked) {
            return response()->json(['message' => 'Plan is locked. Unlock it first to make changes.'], 403);
        }

        $week->delete();

        return response()->json(['message' => 'Week deleted successfully.']);
    }
}
