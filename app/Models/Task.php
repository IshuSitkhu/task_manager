<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'project_id',
        'epic_id',
        'sprint_id',
        'title',
        'description',
        'image',
        'assigned_to',
        'status',
        'priority',
        'type',
        'github_link',
        'due_date',
    ];

    // Task belongs to Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    //  Task belongs to Epic
    public function epic()
    {
        return $this->belongsTo(Epic::class);
    }

    //  Task belongs to Sprint
    public function sprint()
    {
        return $this->belongsTo(Sprint::class);
    }

    //  Task assigned to User
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function projectStatus()
    {
        return $this->belongsTo(ProjectStatus::class, 'status', 'slug');
    }

}
