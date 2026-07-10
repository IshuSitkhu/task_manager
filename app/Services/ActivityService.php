<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Project;

class ActivityService
{
    public function log(Authenticatable $user, string $action, string $description, ?Model $subject = null,string $type)
    {
        ActivityLog::create([
            'user_id'      => $user->id,
            'action'       => $action,
            'description'  => $description,
            'subject_id'   => $subject?->id,
            'subject_type' => $type
        ]);
    }

    public function projectCreated(Authenticatable $user, Project $project)
    {
        $this->log(
            $user,
            'Created_Project',
            "Created project '{$project->name}'",
            $project,
            'Project'
        );
    }

    public function projectUpdated(Authenticatable $user, Project $project)
    {
        $this->log(
            $user,
            'Edited_Project',
            "Updated project '{$project->name}'",
            $project,
            'Project'
        );
    }

    public function projectDeleted(Authenticatable $user, Project $project)
    {
        $this->log(
            $user,
            'Deleted_Project',
            "Deleted project '{$project->name}'",
            $project,
            'Project'
        );
    }

    public function memberAdded(Authenticatable $user, Project $project, User $member)
    {
        $this->log(
            $user,
            'Added_member',
            "Added {$member->name} to project '{$project->name}'",
            $project,
            'Project'
        );
    }

    public function memberRemoved(Authenticatable $user, Project $project, User $member)
    {
        $this->log(
            $user,
            'Removed_member',
            "Removed {$member->name} from project '{$project->name}'",
            $project,
            'Project'
        );
    }
}
