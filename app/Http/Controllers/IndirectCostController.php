<?php

namespace App\Http\Controllers;

use App\Models\IndirectCost;
use Illuminate\Http\Request;

class IndirectCostController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'master_indirect_cost_id' => 'required|exists:master_indirect_costs,id',
            'harga_total' => 'required|numeric|min:0',
        ]);

        $cost = IndirectCost::create($validated);
        
        // Return with related data for frontend
        $cost->load('masterIndirectCost');
        
        return response()->json($cost, 201);
    }

    public function destroy(IndirectCost $indirectCost)
    {
        $indirectCost->delete();
        return response()->json(null, 204);
    }

    public function sync(Request $request, \App\Models\Project $project)
    {
        $request->validate([
            'indirect_costs' => 'array',
            'indirect_costs.*.master_indirect_cost_id' => 'required|exists:master_indirect_costs,id',
            'indirect_costs.*.qty' => 'required|integer|min:1',
            'indirect_costs.*.harga_satuan' => 'required|numeric|min:0',
            'indirect_costs.*.harga_total' => 'required|numeric|min:0',
        ]);

        // Delete existing costs
        $project->indirectCosts()->delete();

        // Create new ones
        $costs = [];
        if ($request->has('indirect_costs')) {
            foreach ($request->indirect_costs as $costData) {
                $costs[] = $project->indirectCosts()->create([
                    'master_indirect_cost_id' => $costData['master_indirect_cost_id'],
                    'qty' => $costData['qty'],
                    'harga_satuan' => $costData['harga_satuan'],
                    'harga_total' => $costData['harga_total'],
                ]);
            }
        }

        return response()->json([
            'message' => 'Indirect costs synced successfully',
            'data' => $project->indirectCosts()->with('masterIndirectCost')->get()
        ]);
    }

    public function syncActuals(Request $request, \App\Models\Project $project)
    {
        $request->validate([
            'actual_costs' => 'nullable|array',
            'actual_costs.*.indirect_cost_id' => 'nullable|exists:indirect_costs,id',
            'actual_costs.*.tanggal' => 'nullable|date',
            'actual_costs.*.sub_item' => 'nullable|string',
            'actual_costs.*.qty' => 'required|integer|min:1',
            'actual_costs.*.harga_satuan' => 'required|numeric|min:0',
            'actual_costs.*.harga_total' => 'required|numeric|min:0',
        ]);

        $project->actualIndirectCosts()->delete();

        if ($request->has('actual_costs')) {
            foreach ($request->actual_costs as $costData) {
                $project->actualIndirectCosts()->create([
                    'indirect_cost_id' => $costData['indirect_cost_id'] ?? null,
                    'tanggal' => $costData['tanggal'] ?? null,
                    'sub_item' => $costData['sub_item'] ?? null,
                    'qty' => (int) $costData['qty'],
                    'harga_satuan' => $costData['harga_satuan'],
                    'harga_total' => $costData['harga_total'],
                ]);
            }
        }

        return response()->json([
            'message' => 'Actual indirect costs synced successfully',
            'data' => $project->actualIndirectCosts()->with('indirectCost.masterIndirectCost')->get()
        ]);
    }

    public function toggleLock(Request $request, \App\Models\Project $project)
    {
        $request->validate([
            'is_locked' => 'required|boolean',
        ]);

        $project->update([
            'is_indirect_cost_locked' => $request->is_locked,
        ]);

        return response()->json([
            'message' => 'Indirect cost plan lock updated successfully',
            'is_indirect_cost_locked' => $project->is_indirect_cost_locked,
        ]);
    }
}
