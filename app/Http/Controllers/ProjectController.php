<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\SCurveService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $sCurveService;

    public function __construct(SCurveService $sCurveService)
    {
        $this->sCurveService = $sCurveService;
    }

    public function index()
    {
        $projects = Project::with(['customer', 'accountManager'])->withCount('tasks')->get();
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json($projects);
        }
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'spk_no' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'jenis_project_id' => 'required|exists:miscs,id',
            'customer_id' => 'required|exists:customers,id',
            'bowheer_id' => 'required|exists:bowheers,id',
            'account_manager_id' => 'required|exists:users,id',
            'lokasi' => 'required|string',
            'subkon_ids' => 'nullable|array',
            'pm_ids' => 'required|array',
        ]);

        $project = Project::create($validated);
        
        if (!empty($validated['subkon_ids'])) {
            $project->subkons()->attach($validated['subkon_ids']);
        }
        
        if (!empty($validated['pm_ids'])) {
            $project->projectManagers()->attach($validated['pm_ids']);
        }
        
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($project, 201);
        }
        return redirect()->route('projects.show', $project->id)->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        $project->load([
            'tasks.taskItems.masterItem.category',
            'payments',
            'indirectCosts',
            'actualIndirectCosts.indirectCost',
            'customer',
            'accountManager',
            'projectManagers',
            'subkons',
            'bowheer',
            'jenisProject',
            'issues',
            'documentations.files'
        ]);
        
        // Track recent project in session
        $recent = session()->get('recent_projects', []);
        $recent = array_filter($recent, function($p) use ($project) {
            return $p['id'] !== $project->id;
        });
        array_unshift($recent, [
            'id' => $project->id,
            'name' => $project->name,
            'code' => $project->project_code
        ]);
        session()->put('recent_projects', array_slice($recent, 0, 3));
        
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json($project);
        }
        return view('projects.show', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'spk_no' => 'sometimes|string',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'jenis_project_id' => 'sometimes|exists:miscs,id',
            'customer_id' => 'sometimes|exists:customers,id',
            'bowheer_id' => 'sometimes|exists:bowheers,id',
            'account_manager_id' => 'sometimes|exists:users,id',
            'lokasi' => 'sometimes|string',
            'subkon_ids' => 'nullable|array',
            'pm_ids' => 'sometimes|array',
            'status' => 'sometimes|string',
        ]);

        $project->update($validated);
        
        if ($request->has('subkon_ids')) {
            $project->subkons()->sync($request->subkon_ids ?: []);
        }
        
        if ($request->has('pm_ids')) {
            $project->projectManagers()->sync($request->pm_ids);
        }
        
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($project);
        }
        return redirect()->route('projects.show', $project->id)->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json(null, 204);
        }
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }

    public function getSCurve(Project $project)
    {
        $data = $this->sCurveService->calculateSCurve($project);
        return response()->json($data);
    }
}
