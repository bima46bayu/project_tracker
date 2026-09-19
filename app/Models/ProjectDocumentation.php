<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDocumentation extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'category',
        'title',
        'document_number',
        'description',
        'logged_date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function files()
    {
        return $this->hasMany(ProjectDocumentationFile::class);
    }
}
