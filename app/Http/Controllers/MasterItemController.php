<?php

namespace App\Http\Controllers;

use App\Models\MasterItem;
use Illuminate\Http\Request;

class MasterItemController extends Controller
{
    public function index()
    {
        $items = MasterItem::with('category')->get();
        $categories = \App\Models\MasterCategory::all();
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json($items);
        }
        return view('master-data.index', compact('items', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'satuan' => 'required|string',
            'master_category_id' => 'required|exists:master_categories,id',
        ]);

        $item = MasterItem::create($validated);
        
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($item, 201);
        }
        return redirect()->back()->with('success', 'Master Item added successfully.');
    }

    public function update(Request $request, MasterItem $masterItem)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'satuan' => 'required|string',
            'master_category_id' => 'required|exists:master_categories,id',
        ]);

        $masterItem->update($validated);
        
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($masterItem);
        }
        return redirect()->back()->with('success', 'Master Item updated successfully.');
    }

    public function destroy(MasterItem $masterItem)
    {
        $masterItem->delete();
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json(null, 204);
        }
        return redirect()->back()->with('success', 'Master Item deleted successfully.');
    }
}
