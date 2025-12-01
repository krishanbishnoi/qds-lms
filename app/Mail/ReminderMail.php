<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $reminder;
    public $user;
    public $entityName;
    public $deadline;
    public $completionStatus;

    /**
     * Create a new message instance.
     */
    public function __construct($reminder, $user, $entityName = '', $deadline = '', $completionStatus = '')
    {
        $this->reminder = $reminder;
        $this->user = $user;
        $this->entityName = $entityName;
        $this->deadline = $deadline;
        $this->completionStatus = $completionStatus;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $body = $this->replaceePlaceholders($this->reminder->body);

        return $this->subject($this->reminder->subject)
                    ->view('emails.reminder-mail')
                    ->with([
                        'body' => $body,
                        'user' => $this->user,
                        'reminder' => $this->reminder,
                    ]);
    }

    /**
     * Replace placeholders in body
     */
    private function replaceePlaceholders($body)
    {
        $body = str_replace('{user_name}', $this->user->first_name . ' ' . $this->user->last_name, $body);
        $body = str_replace('{entity_name}', $this->entityName, $body);
        $body = str_replace('{deadline}', $this->deadline, $body);
        $body = str_replace('{completion_status}', $this->completionStatus, $body);
        return $body;
    }
}
