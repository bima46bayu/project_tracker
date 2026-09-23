<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskTimelineWeek extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_task_id',
        'type',
        'week_number',
        'start_date',
        'end_date',
        'progress_percentage',
        'is_extra',
    ];

    protected $casts = [
        'is_extra' => 'boolean',
        'progress_percentage' => 'decimal:2',
    ];

    public function projectTask()
    {
        return $this->belongsTo(ProjectTask::class);
    }

    /**
     * Scope for plan type weeks
     */
    public function scopePlan($query)
    {
        return $query->where('type', 'plan');
    }

    /**
     * Scope for realisasi type weeks
     */
    public function scopeRealisasi($query)
    {
        return $query->where('type', 'realisasi');
    }
}
