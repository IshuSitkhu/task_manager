<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectStatus extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'slug',
        'color',
        'order'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}


