<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'start_date',
        'end_date',
        'progress_percentage',
        'status',
        'priority',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function taskItems()
    {
        return $this->hasMany(TaskItem::class);
    }

    public function issues()
    {
        return $this->hasMany(ProjectIssue::class, 'project_task_id');
    }
}
