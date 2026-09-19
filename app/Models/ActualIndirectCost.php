<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActualIndirectCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'indirect_cost_id',
        'tanggal',
        'sub_item',
        'qty',
        'harga_satuan',
        'harga_total',
    ];

    public function project()
    {
        return $table = $this->belongsTo(Project::class);
    }

    public function indirectCost()
    {
        return $this->belongsTo(IndirectCost::class, 'indirect_cost_id');
    }
}
