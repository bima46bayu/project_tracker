<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_task_id',
        'master_item_id',
        'qty',
        'harga_satuan',
        'total_harga',
        'modal_satuan',
        'total_modal',
        'qty_realisasi',
        'harga_satuan_realisasi',
        'modal_satuan_realisasi',
        'total_harga_realisasi',
        'total_modal_realisasi',
    ];

    public function projectTask()
    {
        return $this->belongsTo(ProjectTask::class);
    }

    public function masterItem()
    {
        return $this->belongsTo(MasterItem::class);
    }

    public function realisasis()
    {
        return $this->hasMany(TaskItemRealisasi::class);
    }
}
