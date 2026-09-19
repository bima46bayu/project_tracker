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
            'category' => 'required|string|in:PROGRESS,INVOICE,CONTRACT,OTHER',
            'title' => 'required|string|max:255',
            'document_number' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'logged_date' => 'required|date',
        ]);

        $doc = ProjectDocumentation::create($validated);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $extension = $file->getClientOriginalExtension() ?: 'bin';
                $filename = Str::uuid() . '.' . $extension;
                
                $categoryFolder = strtolower($validated['category']);
                $path = $file->storeAs('dokumentasi/' . $doc->project_id . '/' . $categoryFolder, $filename, 'public');

                ProjectDocumentationFile::create([
                    'project_documentation_id' => $doc->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return response()->json($doc->load('files'), 201);
    }

    public function update(Request $request, $id)
    {
        $doc = ProjectDocumentation::findOrFail($id);

        $validated = $request->validate([
            'category' => 'sometimes|required|string|in:PROGRESS,INVOICE,CONTRACT,OTHER',
            'title' => 'sometimes|required|string|max:255',
            'document_number' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'logged_date' => 'sometimes|required|date',
        ]);

        $doc->update($validated);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $extension = $file->getClientOriginalExtension() ?: 'bin';
                $filename = Str::uuid() . '.' . $extension;
                
                $categoryFolder = strtolower($doc->category);
                $path = $file->storeAs('dokumentasi/' . $doc->project_id . '/' . $categoryFolder, $filename, 'public');

                ProjectDocumentationFile::create([
                    'project_documentation_id' => $doc->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return response()->json($doc->load('files'));
    }

    public function destroy($id)
    {
        $doc = ProjectDocumentation::with('files')->findOrFail($id);

        foreach ($doc->files as $file) {
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
        }

        $doc->delete();

        return response()->json(['message' => 'Documentation deleted successfully']);
    }

    public function deleteFile($fileId)
    {
        $file = ProjectDocumentationFile::findOrFail($fileId);

        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        $file->delete();

        return response()->json(['message' => 'File deleted successfully']);
    }
}
