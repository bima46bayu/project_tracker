<?php

namespace App\Http\Controllers;

use App\Models\ProjectTask;
use App\Services\ProjectTaskService;
use Illuminate\Http\Request;

class ProjectTaskController extends Controller
{
    protected $taskService;

    public function __construct(ProjectTaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'sometimes|in:TODO,IN_PROGRESS,DONE',
            'priority' => 'sometimes|in:LOW,MEDIUM,HIGH',
            'progress_percentage' => 'sometimes|numeric|min:0|max:100',
        ]);

        $task = $this->taskService->createTask($validated);
        return response()->json($task, 201);
    }

    public function update(Request $request, ProjectTask $task)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'progress_percentage' => 'sometimes|numeric|min:0|max:100',
            'status' => 'sometimes|in:TODO,IN_PROGRESS,DONE',
            'priority' => 'sometimes|in:LOW,MEDIUM,HIGH',
        ]);

        $updatedTask = $this->taskService->updateTask($task, $validated);
        return response()->json($updatedTask);
    }

    public function destroy(ProjectTask $projectTask)
    {
        $projectId = $projectTask->project_id;
        $projectTask->delete();
        $this->taskService->syncProjectStatus($projectId);
        return response()->json(null, 204);
    }

    public function syncTaskItems(Request $request, ProjectTask $task)
    {
        $validated = $request->validate([
            'task_items' => 'array',
            'task_items.*.master_item_id' => 'required|exists:master_items,id',
            'task_items.*.qty' => 'required|numeric|min:0',
            'task_items.*.harga_satuan' => 'required|numeric|min:0',
            'task_items.*.modal_satuan' => 'required|numeric|min:0',
        ]);

        $task->taskItems()->delete();

        if (!empty($validated['task_items'])) {
            $items = array_map(function ($item) {
                $item['total_harga'] = $item['qty'] * $item['harga_satuan'];
                $item['total_modal'] = $item['qty'] * ($item['modal_satuan'] ?? 0);
                return $item;
            }, $validated['task_items']);

            $task->taskItems()->createMany($items);
        }

        return response()->json(['message' => 'Synced successfully']);
    }
}
