<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'satuan',
        'master_category_id'
    ];

    public function category()
    {
        return $this->belongsTo(MasterCategory::class, 'master_category_id');
    }

    public function taskItems()
    {
        return $this->hasMany(TaskItem::class);
    }
}
