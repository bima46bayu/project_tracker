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
        'is_plan_locked',
    ];

    protected $casts = [
        'is_plan_locked' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function taskItems()
    {
        return $this->hasMany(TaskItem::class);
    }

    public function timelineWeeks()
    {
        return $this->hasMany(TaskTimelineWeek::class)->orderBy('week_number');
    }

    public function planWeeks()
    {
        return $this->hasMany(TaskTimelineWeek::class)->where('type', 'plan')->orderBy('week_number');
    }

    public function realisasiWeeks()
    {
        return $this->hasMany(TaskTimelineWeek::class)->where('type', 'realisasi')->orderBy('week_number');
    }

    public function issues()
    {
        return $this->hasMany(ProjectIssue::class, 'project_task_id');
    }
}
