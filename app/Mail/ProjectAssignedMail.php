<?php

namespace App\Mail;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProjectAssignedMail extends Mailable
{
    use Queueable, SerializesModels;
    public Project $project;

    /**
     * Create a new message instance. we'll pass the project into it.
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Get the message envelope. This is simply the email metadata.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Project Assigned',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.project-assigned',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
