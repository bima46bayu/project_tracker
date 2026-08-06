<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndirectCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'master_indirect_cost_id',
        'qty',
        'harga_satuan',
        'harga_total',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function masterIndirectCost()
    {
        return $this->belongsTo(MasterIndirectCost::class);
    }
}
