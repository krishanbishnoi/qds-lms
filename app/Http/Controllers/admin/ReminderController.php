<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class ReminderController extends Controller
{
    /**
     * Instantiate the controller
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of reminders
     */
    public function index()
    {
        $reminders = Reminder::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.reminders.index', compact('reminders'));
    }

    /**
     * Show the form for creating a new reminder
     */
    public function create()
    {
        $entities = ['training' => 'Training', 'test' => 'Test'];
        $triggers = [
            'before_due' => 'Before Due Date',
            'after_due' => 'After Due Date',
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'at_due' => 'At Due Date',
        ];
        $recipientTypes = [
            'all_participants' => 'All Participants',
            'not_completed' => 'Not Completed',
            'completed' => 'Completed',
            'admins' => 'Admins Only',
            'custom' => 'Custom Email List',
        ];

        return view('admin.reminders.form', compact('entities', 'triggers', 'recipientTypes'));
    }

    /**
     * Store a newly created reminder in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'entity_type' => 'required|in:training,test',
            'trigger_event' => 'required|string|max:50',
            'days_offset' => 'nullable|integer|min:0|max:365',
            'send_time' => 'nullable|date_format:H:i',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'recipient_type' => 'required|in:all_participants,not_completed,completed,admins,custom',
            'custom_emails' => 'nullable|string',
            'include_completion_report' => 'boolean',
            'enabled' => 'boolean',
        ]);

        Reminder::create($validated);

        return redirect()->route('Reminders.index')->with('success', 'Reminder created successfully!');
    }

    /**
     * Show the form for editing the specified reminder
     */
    public function edit(Reminder $reminder)
    {
        $entities = ['training' => 'Training', 'test' => 'Test'];
        $triggers = [
            'before_due' => 'Before Due Date',
            'after_due' => 'After Due Date',
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'at_due' => 'At Due Date',
        ];
        $recipientTypes = [
            'all_participants' => 'All Participants',
            'not_completed' => 'Not Completed',
            'completed' => 'Completed',
            'admins' => 'Admins Only',
            'custom' => 'Custom Email List',
        ];

        return view('admin.reminders.form', compact('reminder', 'entities', 'triggers', 'recipientTypes'));
    }

    /**
     * Update the specified reminder in storage
     */
    public function update(Request $request, Reminder $reminder)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'entity_type' => 'required|in:training,test',
            'trigger_event' => 'required|string|max:50',
            'days_offset' => 'nullable|integer|min:0|max:365',
            'send_time' => 'nullable|date_format:H:i',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'recipient_type' => 'required|in:all_participants,not_completed,completed,admins,custom',
            'custom_emails' => 'nullable|string',
            'include_completion_report' => 'boolean',
            'enabled' => 'boolean',
        ]);

        $reminder->update($validated);

        return redirect()->route('Reminders.index')->with('success', 'Reminder updated successfully!');
    }

    /**
     * Remove the specified reminder from storage
     */
    public function destroy(Reminder $reminder)
    {
        $reminder->delete();
        return redirect()->route('Reminders.index')->with('success', 'Reminder deleted successfully!');
    }

    /**
     * Toggle reminder enabled/disabled status
     */
    public function toggleStatus(Reminder $reminder)
    {
        $reminder->update(['enabled' => !$reminder->enabled]);
        return back()->with('success', 'Reminder status updated!');
    }

    /**
     * Send a test email for the reminder
     */
    public function sendTest(Reminder $reminder)
    {
        // // Artisan::call('reminders:send');
        $adminEmail = Auth::user()->email;

        try {
            \Mail::raw($reminder->body, function ($message) use ($reminder, $adminEmail) {
                $message->to($adminEmail)
                    ->subject('[TEST] ' . $reminder->subject);
            });

            return back()->with('success', 'Test email sent to ' . $adminEmail);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }
}
