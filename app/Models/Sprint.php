<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sprint extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'goal',
        'start_date',
        'end_date',
        'status',
        'progress',
    ];

    //  Sprint belongs to Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Sprint has many Tasks
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
