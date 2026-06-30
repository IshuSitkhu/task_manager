<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'created_by',
        'status',
        'start_date',
        'end_date'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_user')
                    ->withTimestamps();
    }

    public function epics(){
        return $this->hasMany(Epic::class);
    }

    public function sprints()
    {
        return $this->hasMany(Sprint::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function statuses()
    {
        return $this->hasMany(ProjectStatus::class);
    }


public function taskTypes()
{
    return $this->hasMany(TaskType::class);
}

public function bugs()
{
    return $this->hasMany(Bug::class);
}
}
