<?php

namespace App\Http\Controllers;

use App\Models\TaskItem;
use App\Services\ProjectTaskService;
use Illuminate\Http\Request;

class TaskItemController extends Controller
{
    protected $taskService;

    public function __construct(ProjectTaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_task_id' => 'required|exists:project_tasks,id',
            'master_item_id' => 'required|exists:master_items,id',
            'qty' => 'required|numeric|min:0',
            'harga_satuan' => 'required|numeric|min:0',
            'modal_satuan' => 'required|numeric|min:0',
        ]);

        $item = $this->taskService->addTaskItem($validated);
        return response()->json($item, 201);
    }

    public function update(Request $request, TaskItem $taskItem)
    {
        // Route is task-items, param is task_item
        $validated = $request->validate([
            'qty' => 'sometimes|numeric|min:0',
            'harga_satuan' => 'sometimes|numeric|min:0',
            'modal_satuan' => 'sometimes|numeric|min:0',
        ]);

        $item = $this->taskService->updateTaskItem($taskItem, $validated);
        return response()->json($item);
    }

    public function destroy(TaskItem $taskItem)
    {
        $taskItem->delete();
        return response()->json(null, 204);
    }
}
