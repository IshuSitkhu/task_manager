<?php

namespace App\Events;

use App\Models\Project;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectAssigned
{
    use Dispatchable, SerializesModels;

    // this create property that store projects
    public Project $project;

    //this store member id
    public array $members;

    public function __construct(Project $project, array $members)
    {
        $this->project = $project;
        $this->members = $members;
    }
}
