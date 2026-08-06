<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subkon extends Model
{
    protected $fillable = [
        'subkon_code',
        'name',
        'pic',
        'email',
        'phone',
        'address'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->subkon_code)) {
                $last = self::orderBy('id', 'desc')->first();
                $nextId = $last ? $last->id + 1 : 1;
                $model->subkon_code = 'SUB-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
