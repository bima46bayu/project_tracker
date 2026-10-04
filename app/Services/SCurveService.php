<?php

namespace App\Services;

use App\Models\Project;
use Carbon\Carbon;

class SCurveService
{
    public function calculateSCurve(Project $project)
    {
        $project->load(['tasks.taskItems', 'tasks.planWeeks', 'tasks.realisasiWeeks']);
        
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

        // Collect all distinct weeks (Mondays) across the project tasks
        $allMondays = collect();
        foreach ($project->tasks as $task) {
            $task->bobot = ($task->calculated_total / $totalRAB) * 100;
            
            foreach ($task->planWeeks as $pw) {
                $allMondays->push($pw->start_date);
            }
            foreach ($task->realisasiWeeks as $rw) {
                if ($rw->progress_percentage > 0) {
                    $allMondays->push($rw->start_date);
                }
            }
        }
        
        $allMondays = $allMondays->unique()->sort()->values();
        
        $plannedCurve = [];
        $actualCurve = [];
        $currentActualProgress = 0;

        foreach ($allMondays as $index => $monday) {
            $cumulativePlan = 0;
            $cumulativeActual = 0;
            $weekDate = Carbon::parse($monday);
            
            foreach ($project->tasks as $task) {
                // Since progress_percentage is cumulative, we take the max progress up to this monday
                // This prevents trailing 0s from dropping the curve back down
                $planProgress = $task->planWeeks->where('start_date', '<=', $monday)->max('progress_percentage') ?? 0;
                $cumulativePlan += ($planProgress / 100) * $task->bobot;
                
                // Same for realisasi
                $realisasiProgress = $task->realisasiWeeks->where('start_date', '<=', $monday)->max('progress_percentage') ?? 0;
                $cumulativeActual += ($realisasiProgress / 100) * $task->bobot;
            }

            $plannedCurve[] = [
                'day' => $index + 1,
                'week' => 'W' . ($index + 1),
                'date' => $monday,
                'planned_cumulative' => round($cumulativePlan, 2)
            ];

            // Only add to actual curve if date is not in future, or if it has progress
            // To prevent actual curve from drawing flat lines into the future
            if ($weekDate->lte(Carbon::today()) || $cumulativeActual > 0) {
                $actualCurve[] = [
                    'week' => 'W' . ($index + 1),
                    'date' => $monday,
                    'actual_cumulative' => round($cumulativeActual, 2)
                ];
                $currentActualProgress = round($cumulativeActual, 2);
            }
        }

        return [
            'total_rab' => $totalRAB,
            'current_actual_progress' => $currentActualProgress,
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
