<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Misc;
use Illuminate\Http\Request;

class MiscController extends Controller
{
    public function index(Request $request)
    {
        $query = Misc::query();
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        return $query->orderBy('display_order')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:50',
            'value' => 'required|string|max:255',
            'display_order' => 'integer|min:0'
        ]);
        
        $misc = Misc::create($validated);
        return response()->json($misc, 201);
    }

    public function update(Request $request, \App\Models\Misc $misc)
    {
        $validated = $request->validate([
            'value' => 'sometimes|string|max:255',
            'type' => 'sometimes|string|max:255',
            'display_order' => 'nullable|integer'
        ]);
        
        $misc->update($validated);
        return response()->json($misc);
    }

    public function destroy(\App\Models\Misc $misc)
    {
        $misc->delete();
        return response()->json(null, 204);
    }
}
