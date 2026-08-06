<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bowheer extends Model
{
    protected $fillable = [
        'bowheer_code',
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
            if (empty($model->bowheer_code)) {
                $last = self::orderBy('id', 'desc')->first();
                $nextId = $last ? $last->id + 1 : 1;
                $model->bowheer_code = 'BOW-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
