<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bowheer;
use Illuminate\Http\Request;

class BowheerController extends Controller
{
    public function index()
    {
        return Bowheer::all();
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
        
        $bowheer = Bowheer::create($validated);
        return response()->json($bowheer, 201);
    }

    public function update(Request $request, \App\Models\Bowheer $bowheer)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'pic' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string'
        ]);
        
        $bowheer->update($validated);
        return response()->json($bowheer);
    }

    public function destroy(\App\Models\Bowheer $bowheer)
    {
        $bowheer->delete();
        return response()->json(null, 204);
    }
}
