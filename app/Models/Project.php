<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'project_code',
        'name',
        'spk_no',
        'jenis_project_id',
        'customer_id',
        'bowheer_id',
        'account_manager_id',
        'lokasi',
        'start_date',
        'end_date',
        'status'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->project_code)) {
                $last = self::orderBy('id', 'desc')->first();
                $nextId = $last ? $last->id + 1 : 1;
                $model->project_code = 'TPN-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function jenisProject()
    {
        return $this->belongsTo(Misc::class, 'jenis_project_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function bowheer()
    {
        return $this->belongsTo(Bowheer::class);
    }

    public function accountManager()
    {
        return $this->belongsTo(User::class, 'account_manager_id');
    }

    public function subkons()
    {
        return $this->belongsToMany(Subkon::class, 'project_subkon')->withPivot('payment_term');
    }

    public function projectManagers()
    {
        return $this->belongsToMany(User::class, 'project_pm');
    }

    public function tasks()
    {
        return $this->hasMany(ProjectTask::class);
    }

    public function indirectCosts()
    {
        return $this->hasMany(IndirectCost::class);
    }

    public function issues()
    {
        return $this->hasMany(ProjectIssue::class);
    }

    public function documentations()
    {
        return $this->hasMany(ProjectDocumentation::class)->orderBy('logged_date', 'desc');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
