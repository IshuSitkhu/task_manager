<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TaskType;

class Task extends Model
{
    use HasFactory;

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
        'type_id',
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


    public function bugs()
    {
        return $this->hasMany(\App\Models\Bug::class);
    }

    public function type()
    {
        return $this->belongsTo(TaskType::class, 'type_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function checklists()
    {
        return $this->hasMany(TaskChecklist::class)
                    ->orderBy('sort_order');
    }
}
