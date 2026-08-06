<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\SCurveService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $sCurveService;

    public function __construct(SCurveService $sCurveService)
    {
        $this->sCurveService = $sCurveService;
    }

    public function index()
    {
        $totalProjects = Project::count();
        $activeProjects = Project::where('status', '!=', 'DONE')->count();
        $completedProjects = Project::where('status', 'DONE')->count();
        
        // Health Status Data
        $projects = Project::with('customer', 'tasks.taskItems')->where('status', '!=', 'DONE')->latest()->get();
        $totalActiveRAB = 0;
        
        $projectHealth = [];

        foreach ($projects as $project) {
            $projectRab = 0;
            foreach ($project->tasks as $task) {
                $projectRab += $task->taskItems->sum('total_harga');
            }
            $totalActiveRAB += $projectRab;

            // Calculate Deviation
            $sCurve = $this->sCurveService->calculateSCurve($project);
            $plannedCurve = $sCurve['planned_curve'] ?? [];
            $lastPlanned = end($plannedCurve);
            $planned = $lastPlanned ? ($lastPlanned['planned_cumulative'] ?? 0) : 0;
            
            // Find today's planned progress
            $today = Carbon::now()->startOfDay();
            $todayPlanned = 0;
            foreach ($plannedCurve as $point) {
                if (Carbon::parse($point['date'])->startOfDay()->lte($today)) {
                    $todayPlanned = $point['planned_cumulative'] ?? 0;
                }
            }

            $actual = $sCurve['current_actual_progress'] ?? 0;
            $deviation = $actual - $todayPlanned;
            
            $health = 'ON_TRACK';
            if ($deviation < -5) {
                $health = 'CRITICAL';
            } elseif ($deviation < 0) {
                $health = 'WARNING';
            }

            $projectHealth[] = (object) [
                'id' => $project->id,
                'name' => $project->name,
                'project_code' => $project->project_code,
                'customer' => $project->customer ? $project->customer->name : 'N/A',
                'rab' => $projectRab,
                'planned' => round($todayPlanned, 2),
                'actual' => round($actual, 2),
                'deviation' => round($deviation, 2),
                'status' => $health
            ];
        }
        
        return view('dashboard', compact('totalProjects', 'activeProjects', 'completedProjects', 'totalActiveRAB', 'projectHealth'));
    }
}
