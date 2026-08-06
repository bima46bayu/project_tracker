<?php

namespace App\Http\Controllers;

use App\Models\ProjectIssue;
use Illuminate\Http\Request;

class ProjectIssueController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'project_task_id' => 'nullable|exists:project_tasks,id',
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'impact' => 'required|in:LOW,MEDIUM,HIGH',
            'status' => 'required|in:OPEN,IN_PROGRESS,RESOLVED',
            'reported_date' => 'required|date',
            'resolution' => 'nullable|string',
        ]);

        $issue = ProjectIssue::create($validated);
        
        return response()->json($issue->load('task'), 201);
    }

    public function update(Request $request, ProjectIssue $issue)
    {
        $validated = $request->validate([
            'project_task_id' => 'nullable|exists:project_tasks,id',
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'impact' => 'required|in:LOW,MEDIUM,HIGH',
            'status' => 'required|in:OPEN,IN_PROGRESS,RESOLVED',
            'reported_date' => 'required|date',
            'resolution' => 'nullable|string',
        ]);

        $issue->update($validated);

        return response()->json($issue->load('task'));
    }

    public function destroy(ProjectIssue $issue)
    {
        $issue->delete();
        return response()->json(null, 204);
    }
}
