<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectIssue extends Model
{
    protected $fillable = [
        'project_id',
        'project_task_id',
        'title',
        'category',
        'impact',
        'status',
        'reported_date',
        'resolution',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function task()
    {
        return $this->belongsTo(ProjectTask::class, 'project_task_id');
    }
}
