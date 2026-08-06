<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterIndirectCost extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'satuan'];

    public function indirectCosts()
    {
        return $this->hasMany(IndirectCost::class);
    }
}
