<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'subkon_id',
        'type',
        'invoice',
        'keterangan',
        'nilai',
        'tanggal',
        'tanggal_payment',
        'nilai_payment',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
