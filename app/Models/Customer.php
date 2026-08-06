<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'customer_code',
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
            if (empty($model->customer_code)) {
                $lastCustomer = self::orderBy('id', 'desc')->first();
                $nextId = $lastCustomer ? $lastCustomer->id + 1 : 1;
                $model->customer_code = 'CUST-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
