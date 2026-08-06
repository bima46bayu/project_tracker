<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDocumentationFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_documentation_id',
        'file_path',
        'file_name',
        'file_type',
    ];

    public function documentation()
    {
        return $this->belongsTo(ProjectDocumentation::class, 'project_documentation_id');
    }
}
