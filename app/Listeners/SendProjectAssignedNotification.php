<?php

namespace App\Listeners;

use App\Events\ProjectAssigned;
use App\Models\Notification;
use App\Mail\ProjectAssignedMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

use App\Jobs\SendProjectAssignedJob;

class SendProjectAssignedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Laravel automatically calls this when the Event is fired. $event VANDEKO EVENT OBJECT CREATED EARLIER
     */
    public function handle(ProjectAssigned $event): void
    {

        // dispatch send jobs to laravel queue. YESLE NOTIFICATION, EMAIL JOB: SendProjectAssignedJob  MA PATHAYO
        SendProjectAssignedJob::dispatch(
            $event->project,
            $event->members
        );
    }
}
