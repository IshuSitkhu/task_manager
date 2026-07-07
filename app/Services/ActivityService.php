<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

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
}
