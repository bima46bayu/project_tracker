<?php

namespace App\Services;

use App\Models\Project;
use Carbon\Carbon;

class SCurveService
{
    public function calculateSCurve(Project $project)
    {
        $project->load('tasks.taskItems');
        
        $totalRAB = 0;
        foreach ($project->tasks as $task) {
            $taskTotal = $task->taskItems->sum('total_harga');
            $task->calculated_total = $taskTotal;
            $totalRAB += $taskTotal;
        }

        if ($totalRAB == 0) {
            return [
                'total_rab' => 0,
                'planned_curve' => [],
                'actual_curve' => [],
                'current_actual_progress' => 0,
                'tasks_summary' => []
            ];
        }

        $projectStart = Carbon::parse($project->start_date);
        $projectEnd = Carbon::parse($project->end_date);

        $minTaskStart = $project->tasks->min('start_date');
        $maxTaskEnd = $project->tasks->max('end_date');

        $effectiveStart = $minTaskStart ? Carbon::parse($minTaskStart) : $projectStart;
        $effectiveEnd = $maxTaskEnd ? Carbon::parse($maxTaskEnd) : $projectEnd;

        // Ensure start is not after end
        if ($effectiveStart->gt($effectiveEnd)) {
            $effectiveStart = $projectStart;
            $effectiveEnd = $projectEnd;
        }
        
        $durationDays = $effectiveStart->diffInDays($effectiveEnd) + 1;
        
        $plannedDailyWeight = array_fill(0, $durationDays, 0);
        $actualTotal = 0;

        foreach ($project->tasks as $task) {
            $taskWeight = ($task->calculated_total / $totalRAB) * 100;
            $task->bobot = $taskWeight;

            // Actual progress contribution
            $actualTotal += ($task->progress_percentage / 100) * $taskWeight;

            // Planned spread
            $taskStart = Carbon::parse($task->start_date);
            $taskEnd = Carbon::parse($task->end_date);
            $taskDuration = $taskStart->diffInDays($taskEnd) + 1;

            if ($taskDuration > 0) {
                $dailyTaskWeight = $taskWeight / $taskDuration;
                for ($d = 0; $d < $taskDuration; $d++) {
                    $currentDate = $taskStart->copy()->addDays($d);
                    if ($currentDate->between($effectiveStart, $effectiveEnd)) {
                        $dayIndex = $effectiveStart->diffInDays($currentDate);
                        $plannedDailyWeight[$dayIndex] += $dailyTaskWeight;
                    }
                }
            }
        }

        // Cumulative Arrays
        $plannedCurve = [];
        $cumulativePlanned = 0;
        for ($i = 0; $i < $durationDays; $i++) {
            $cumulativePlanned += $plannedDailyWeight[$i];
            $plannedCurve[] = [
                'day' => $i + 1,
                'date' => $effectiveStart->copy()->addDays($i)->format('Y-m-d'),
                'planned_cumulative' => round($cumulativePlanned, 2)
            ];
        }

        $histories = \App\Models\ProjectProgressHistory::where('project_id', $project->id)
            ->orderBy('record_date', 'asc')
            ->get();
            
        $actualCurve = [];
        // Map history to our format
        foreach ($histories as $history) {
            $actualCurve[] = [
                'date' => $history->record_date,
                'actual_cumulative' => (float) $history->progress_percentage
            ];
        }

        return [
            'total_rab' => $totalRAB,
            'current_actual_progress' => round($actualTotal, 2),
            'planned_curve' => $plannedCurve,
            'actual_curve' => $actualCurve,
            'tasks_summary' => $project->tasks->map(function($t) {
                return [
                    'id' => $t->id,
                    'name' => $t->name,
                    'bobot' => round($t->bobot, 2),
                    'total_qty' => $t->taskItems->sum('qty'),
                    'total_harga' => $t->calculated_total
                ];
            })
        ];
    }
}
