<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterCategory;

class MasterCategoryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        MasterCategory::create($validated);
        return back()->with('success', 'Category added successfully');
    }

    public function update(Request $request, MasterCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($validated);
        return back()->with('success', 'Category updated successfully');
    }

    public function destroy(MasterCategory $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted successfully');
    }
}
