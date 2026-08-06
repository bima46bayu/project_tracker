<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subkon;
use Illuminate\Http\Request;

class SubkonController extends Controller
{
    public function index()
    {
        return Subkon::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'pic' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string'
        ]);
        
        $subkon = Subkon::create($validated);
        return response()->json($subkon, 201);
    }

    public function update(Request $request, \App\Models\Subkon $subkon)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'pic' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string'
        ]);
        
        $subkon->update($validated);
        return response()->json($subkon);
    }

    public function destroy(\App\Models\Subkon $subkon)
    {
        $subkon->delete();
        return response()->json(null, 204);
    }
}
