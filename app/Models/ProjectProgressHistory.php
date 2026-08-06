<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectProgressHistory extends Model
{
    //
    protected $fillable = [
        'project_id',
        'record_date',
        'progress_percentage'
    ];
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
