<?php

namespace App\Jobs;
use App\Models\Project;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Mail\ProjectAssignedMail;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendProjectAssignedJob implements ShouldQueue
{
    use Queueable;
    public Project $project;
    public array $members;

    /**
     * Create a new job instance.
     */
    public function __construct(Project $project, array $members)
    {
        $this->project = $project;
        $this->members = $members;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->members as $memberId) {

            Notification::create([
                'user_id' => $memberId,
                'title' => 'New Project Assigned',
                'message' => 'You have been assigned to the project "' . $this->project->name . '".',
                'is_read' => false,
            ]);

            $user = User::find($memberId);

            if ($user && $user->email) {
                Mail::to($user->email)
                    ->send(new ProjectAssignedMail($this->project));
            }
        }
    }
}
