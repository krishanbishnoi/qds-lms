<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminSummaryMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $reminder;
    public $adminEmails;
    public $summary;
    public $csvPath;

    /**
     * Create a new message instance.
     */
    public function __construct($reminder, $adminEmails = [], $summary = [], $csvPath = null)
    {
        $this->reminder = $reminder;
        $this->adminEmails = $adminEmails;
        $this->summary = $summary;
        $this->csvPath = $csvPath;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $message = $this->subject($this->reminder->subject . ' - Admin Report')
                    ->view('emails.admin-summary-mail')
                    ->with([
                        'reminder' => $this->reminder,
                        'summary' => $this->summary,
                    ]);

        if ($this->csvPath && file_exists($this->csvPath)) {
            $message->attach($this->csvPath, [
                'as' => 'completion_report_' . date('Y-m-d_H-i-s') . '.csv',
                'mime' => 'text/csv',
            ]);
        }

        return $message;
    }
}
