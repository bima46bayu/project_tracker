<?php

namespace App\Http\Controllers;

use App\Models\MasterIndirectCost;
use Illuminate\Http\Request;

class MasterIndirectCostController extends Controller
{
    public function index()
    {
        $costs = MasterIndirectCost::all();
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json($costs);
        }
        return view('master-data.index'); // We'll pass both from a single route usually or AJAX
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'satuan' => 'nullable|string',
        ]);

        $cost = MasterIndirectCost::create($validated);
        
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($cost, 201);
        }
        return redirect()->back()->with('success', 'Master Indirect Cost added successfully.');
    }

    public function update(Request $request, MasterIndirectCost $masterIndirectCost)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'satuan' => 'nullable|string',
        ]);

        $masterIndirectCost->update($validated);
        
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($masterIndirectCost);
        }
        return redirect()->back()->with('success', 'Master Indirect Cost updated successfully.');
    }

    public function destroy(MasterIndirectCost $masterIndirectCost)
    {
        $masterIndirectCost->delete();
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json(null, 204);
        }
        return redirect()->back()->with('success', 'Master Indirect Cost deleted successfully.');
    }
}
