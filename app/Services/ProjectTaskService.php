<?php

namespace App\Services;

use App\Models\ProjectTask;
use App\Models\TaskItem;

class ProjectTaskService
{
    protected $sCurveService;

    public function __construct(SCurveService $sCurveService)
    {
        $this->sCurveService = $sCurveService;
    }

    public function createTask(array $data)
    {
        $task = ProjectTask::create($data);
        $this->syncProjectStatus($task->project_id);
        return $task;
    }

    public function updateTask(ProjectTask $task, array $data)
    {
        $task->update($data);
        $this->syncProjectStatus($task->project_id);
        return $task;
    }

    public function syncProjectStatus($projectId)
    {
        $project = \App\Models\Project::with('tasks')->find($projectId);
        if (!$project) return;
        
        // Do not alter if manually set to DONE
        if ($project->status === 'DONE') return;

        $tasks = $project->tasks;
        if ($tasks->isEmpty()) {
            $project->status = 'NOT_STARTED';
            $project->save();
            return;
        }

        $allDone = true;
        $anyProgress = false;

        foreach ($tasks as $t) {
            if ($t->progress_percentage > 0) {
                $anyProgress = true;
            }
            if ($t->progress_percentage < 100) {
                $allDone = false;
            }
        }

        if ($allDone) {
            $project->status = 'FINISH';
        } elseif ($anyProgress) {
            $project->status = 'ONGOING';
        } else {
            $project->status = 'NOT_STARTED';
        }
        
        $project->save();
        
        // Record progress snapshot
        $this->recordProgressSnapshot($project);
    }

    protected function recordProgressSnapshot(\App\Models\Project $project)
    {
        $sCurveData = $this->sCurveService->calculateSCurve($project);
        $progress = $sCurveData['current_actual_progress'] ?? 0;

        \App\Models\ProjectProgressHistory::updateOrCreate(
            [
                'project_id' => $project->id,
                'record_date' => now()->format('Y-m-d')
            ],
            [
                'progress_percentage' => $progress
            ]
        );
    }

    public function addTaskItem(array $data)
    {
        $data['total_harga'] = $data['qty'] * $data['harga_satuan'];
        $data['total_modal'] = $data['qty'] * ($data['modal_satuan'] ?? 0);
        return TaskItem::create($data);
    }

    public function updateTaskItem(TaskItem $taskItem, array $data)
    {
        if (isset($data['qty'])) {
            $taskItem->qty = $data['qty'];
        }
        if (isset($data['harga_satuan'])) {
            $taskItem->harga_satuan = $data['harga_satuan'];
        }
        if (isset($data['modal_satuan'])) {
            $taskItem->modal_satuan = $data['modal_satuan'];
        }
        
        $taskItem->total_harga = $taskItem->qty * $taskItem->harga_satuan;
        $taskItem->total_modal = $taskItem->qty * $taskItem->modal_satuan;
        $taskItem->save();

        return $taskItem;
    }
}
