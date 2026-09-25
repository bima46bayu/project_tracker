<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskItemRealisasi extends Model
{
    protected $fillable = [
        'task_item_id',
        'task_timeline_week_id',
        'qty_realisasi',
        'harga_satuan_realisasi',
    ];

    public function taskItem()
    {
        return $this->belongsTo(TaskItem::class);
    }

    public function taskTimelineWeek()
    {
        return $this->belongsTo(TaskTimelineWeek::class);
    }
}
