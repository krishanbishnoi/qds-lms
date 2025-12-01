<?php

namespace App\Console\Commands;

use App\Mail\AdminSummaryMail;
use App\Mail\ReminderMail;
use App\Models\Reminder;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class SendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduled reminder emails to users based on training/test completion';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting reminder email job...');

        $reminders = Reminder::where('enabled', true)->get();

        if ($reminders->isEmpty()) {
            $this->info('No active reminders found.');
            return 0;
        }

        foreach ($reminders as $reminder) {
            try {
                $this->processReminder($reminder);
            } catch (\Exception $e) {
                $this->error("Error processing reminder {$reminder->id}: {$e->getMessage()}");
                \Log::error("Reminder Error: {$e->getMessage()}", ['reminder_id' => $reminder->id]);
            }
        }

        $this->info('Reminder email job completed!');
        return 0;
    }

    /**
     * Process a single reminder
     */
    private function processReminder(Reminder $reminder)
    {
        $recipients = $this->getRecipients($reminder);
        if ($recipients->isEmpty()) {
            $this->info("No recipients found for reminder: {$reminder->name}");
            return;
        }

        $adminEmails = $this->getAdminEmails($reminder);
        $summary = ['total' => $recipients->count(), 'completed' => 0, 'pending' => 0];
        $csvPath = null;

        // Send individual reminders to users
        foreach ($recipients as $user) {
            try {
                $entityInfo = $this->getEntityInfo($reminder, $user);
                
                $mail = new ReminderMail(
                    $reminder,
                    $user,
                    $entityInfo['name'] ?? '',
                    $entityInfo['deadline'] ?? '',
                    $entityInfo['status'] ?? ''
                );

                Mail::to($user->email)->send($mail);

                // Update summary
                if (isset($entityInfo['status']) && strpos(strtolower($entityInfo['status']), 'completed') !== false) {
                    $summary['completed']++;
                } else {
                    $summary['pending']++;
                }

                $this->info("Sent reminder to: {$user->email}");
            } catch (\Exception $e) {
                $this->error("Failed to send to {$user->email}: {$e->getMessage()}");
            }
        }

        // Generate CSV report if needed
        if ($reminder->include_completion_report) {
            $csvPath = $this->generateCompletionReport($reminder, $recipients);
        }

        // Send admin summary email
        if (!empty($adminEmails)) {
            try {
                foreach ($adminEmails as $adminEmail) {
                    $mail = new AdminSummaryMail($reminder, $adminEmails, $summary, $csvPath);
                    Mail::to($adminEmail)->send($mail);
                }
                $this->info("Sent admin summary report to: " . implode(', ', $adminEmails));
            } catch (\Exception $e) {
                $this->error("Failed to send admin report: {$e->getMessage()}");
            }
        }

        // Update last_sent_at
        $reminder->update(['last_sent_at' => now()]);

        // Cleanup CSV file if it exists
        if ($csvPath && file_exists($csvPath)) {
            unlink($csvPath);
        }
    }

    /**
     * Get recipients based on reminder recipient_type
     */
    private function getRecipients(Reminder $reminder)
    {
        $query = User::where('is_active', 1);

        switch ($reminder->recipient_type) {
            case 'custom':
                $customEmails = $reminder->getCustomEmailsArray();
                return $customEmails ? User::whereIn('email', $customEmails)->get() : collect();

            case 'admins':
                return User::where('user_role_id', SUPER_ADMIN_ROLE_ID)->get();

            case 'completed':
                return $this->getCompletedUsers($reminder);

            case 'not_completed':
                return $this->getNotCompletedUsers($reminder);

            case 'all_participants':
            default:
                return $this->getAllParticipants($reminder);
        }
    }

    /**
     * Get all participants for an entity
     */
    private function getAllParticipants(Reminder $reminder)
    {
        if ($reminder->entity_type === 'training') {
            return User::join('training_test_participants as tt', 'users.id', '=', 'tt.trainee_id')
                ->where('users.is_active', 1)
                ->select('users.*')
                ->distinct()
                ->get();
        } else {
            return User::join('test_result as tr', 'users.id', '=', 'tr.trainee_id')
                ->where('users.is_active', 1)
                ->select('users.*')
                ->distinct()
                ->get();
        }
    }

    /**
     * Get users who completed the entity
     */
    private function getCompletedUsers(Reminder $reminder)
    {
        if ($reminder->entity_type === 'training') {
            return User::join('training_test_participants as tt', 'users.id', '=', 'tt.trainee_id')
                ->where('users.is_active', 1)
                ->where('tt.status', 'completed')
                ->select('users.*')
                ->distinct()
                ->get();
        } else {
            return User::join('test_result as tr', 'users.id', '=', 'tr.trainee_id')
                ->where('users.is_active', 1)
                ->where('tr.status', 'completed')
                ->select('users.*')
                ->distinct()
                ->get();
        }
    }

    /**
     * Get users who have NOT completed the entity
     */
    private function getNotCompletedUsers(Reminder $reminder)
    {
        if ($reminder->entity_type === 'training') {
            return User::join('training_test_participants as tt', 'users.id', '=', 'tt.trainee_id')
                ->where('users.is_active', 1)
                ->where('tt.status', '!=', 'completed')
                ->select('users.*')
                ->distinct()
                ->get();
        } else {
            return User::join('test_result as tr', 'users.id', '=', 'tr.trainee_id')
                ->where('users.is_active', 1)
                ->where(function($q) {
                    $q->whereNull('tr.status')
                      ->orWhere('tr.status', '!=', 'completed');
                })
                ->select('users.*')
                ->distinct()
                ->get();
        }
    }

    /**
     * Get entity-specific information (name, deadline, status)
     */
    private function getEntityInfo(Reminder $reminder, $user)
    {
        // This is a placeholder - adjust based on your actual DB schema
        $info = [];

        if ($reminder->entity_type === 'training') {
            // Get the most recent training
            $training = DB::table('trainings')->latest()->first();
            $info['name'] = $training->name ?? 'Training';
            $info['deadline'] = $training->end_date ?? '';

            $status = DB::table('training_test_participants')
                ->where('trainee_id', $user->id)
                ->where('training_id', $training->id ?? 0)
                ->value('status');
            $info['status'] = ucfirst($status ?? 'Pending');
        } else {
            // Get the most recent test
            $test = DB::table('tests')->latest()->first();
            $info['name'] = $test->name ?? 'Test';
            $info['deadline'] = $test->due_date ?? '';

            $status = DB::table('test_result')
                ->where('trainee_id', $user->id)
                ->where('test_id', $test->id ?? 0)
                ->value('status');
            $info['status'] = ucfirst($status ?? 'Pending');
        }

        return $info;
    }

    /**
     * Get admin email addresses
     */
    private function getAdminEmails(Reminder $reminder)
    {
        $adminEmails = User::where('user_role_id', SUPER_ADMIN_ROLE_ID)
            ->pluck('email')
            ->toArray();

        // Add custom emails if any
        if ($reminder->recipient_type === 'custom') {
            $customEmails = $reminder->getCustomEmailsArray();
            $adminEmails = array_merge($adminEmails, $customEmails);
        }

        return array_unique($adminEmails);
    }

    /**
     * Generate CSV completion report
     */
    private function generateCompletionReport(Reminder $reminder, $recipients)
    {
        $csv = "Email,Name,Status,Last Updated\n";

        foreach ($recipients as $user) {
            $email = $user->email ?? '';
            $name = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));

            $status = 'Pending';
            $lastUpdated = '';

            // Get completion status
            if ($reminder->entity_type === 'training') {
                $record = DB::table('training_test_participants')
                    ->where('trainee_id', $user->id)
                    ->latest()
                    ->first();
            } else {
                $record = DB::table('test_result')
                    ->where('trainee_id', $user->id)
                    ->latest()
                    ->first();
            }

            if ($record) {
                $status = ucfirst($record->status ?? 'Pending');
                $lastUpdated = $record->updated_at ?? '';
            }

            $csv .= "\"{$email}\",\"{$name}\",\"{$status}\",\"{$lastUpdated}\"\n";
        }

        $filePath = storage_path('app/reports/completion_report_' . time() . '.csv');
        
        if (!is_dir(storage_path('app/reports'))) {
            mkdir(storage_path('app/reports'), 0755, true);
        }

        file_put_contents($filePath, $csv);
        return $filePath;
    }
}
