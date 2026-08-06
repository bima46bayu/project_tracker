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
            'indirect_costs.*.qty' => 'required|numeric|min:0',
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
}
