<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Epic extends Model
{
    use Hasfactory;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'owner_id',
        'priority',
        'status',
        'planned_start_date',
        'planned_end_date',
        'progress'

    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
