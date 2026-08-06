<?php

namespace App\Http\Controllers;

use App\Models\ProjectDocumentation;
use App\Models\ProjectDocumentationFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectDocumentationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logged_date' => 'required|date',
        ]);

        $doc = ProjectDocumentation::create($validated);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // Generate a unique filename
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                
                // Store in public/dokumentasi/{project_id}
                $path = $file->storeAs('dokumentasi/' . $doc->project_id, $filename, 'public');

                ProjectDocumentationFile::create([
                    'project_documentation_id' => $doc->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                ]);
            }
        }

        return response()->json($doc->load('files'), 201);
    }

    public function destroy($id)
    {
        $doc = ProjectDocumentation::with('files')->findOrFail($id);

        // Delete associated files from storage
        foreach ($doc->files as $file) {
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
        }

        $doc->delete();

        return response()->json(['message' => 'Documentation deleted successfully']);
    }
}
