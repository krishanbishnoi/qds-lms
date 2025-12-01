# Reminder Mail Setup Module - Implementation Guide

This document provides complete setup and usage instructions for the Reminder Mail Setup module.

## Files Created

### Core Files
- `database/migrations/2025_11_29_000001_create_reminders_table.php` - Database migration
- `app/Models/Reminder.php` - Reminder model
- `app/Http/Controllers/Admin/ReminderController.php` - Controller for CRUD operations
- `app/Console/Commands/SendReminders.php` - Artisan command to send reminders
- `app/Mail/ReminderMail.php` - Mail class for user reminders
- `app/Mail/AdminSummaryMail.php` - Mail class for admin summary reports
- `app/Console/Kernel.php` - Updated with reminder scheduler

### Blade Views
- `resources/views/admin/reminders/index.blade.php` - List all reminders
- `resources/views/admin/reminders/form.blade.php` - Create/Edit reminder form
- `resources/views/emails/reminder-mail.blade.php` - User reminder email template
- `resources/views/emails/admin-summary-mail.blade.php` - Admin summary email template

### Routes
- Added routes to `routes/web.php` under `/admin/reminders` prefix

### Sidebar Menu
- Updated `resources/views/admin/layouts/default.blade.php` with "Reminder Mail Setup" menu item

---

## Setup Instructions

### Step 1: Run Migration
Run the migration to create the `reminders` table:

```bash
php artisan migrate
```

### Step 2: Configure Mail
Update your `.env` file with SMTP or mail service configuration:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@lms.local
MAIL_FROM_NAME="LMS Training System"
```

For production, use services like:
- SendGrid
- Mailgun
- AWS SES
- Your own SMTP server

### Step 3: Configure Queue (Optional but Recommended)
Update `.env` for queue processing:

```env
QUEUE_CONNECTION=database
```

Or use Redis/Beanstalk for better performance:
```env
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

Create queue table (if using database driver):
```bash
php artisan queue:table
php artisan migrate
```

### Step 4: Set Up Task Scheduler
The reminder scheduler is configured to run every hour. Set up a cron job to execute the scheduler:

**Linux/Unix/macOS:**
Add this line to your crontab (`crontab -e`):
```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

This runs every minute and Laravel handles the scheduling internally.

**Windows:**
Create a scheduled task that runs:
```cmd
cd C:\wamp64\www\lms && php artisan schedule:run
```
Schedule it to run every minute.

### Step 5: Start Queue Worker (If Using Queue)
If you configured a queue driver, start the queue worker:

```bash
php artisan queue:work --tries=3 --timeout=90
```

Or use a process manager like Supervisor for production.

---

## Usage Guide

### Accessing the Module
1. Login to the admin panel
2. In the sidebar, click on "Reminder Mail Setup"
3. You'll see a list of all reminders

### Creating a New Reminder

1. Click "Add New Reminder" button
2. Fill in the form fields:

   **Basic Information:**
   - **Reminder Name**: Descriptive name (e.g., "Training Completion Reminder")
   - **Entity Type**: Choose between "Training" or "Test"
   - **Trigger Event**: When to send the reminder:
     - Before Due Date
     - After Due Date
     - Daily
     - Weekly
     - At Due Date

   **Timing:**
   - **Days Offset**: Number of days before/after trigger (e.g., 2 days before due date)
   - **Send Time**: Time of day to send (24-hour format)

   **Email Content:**
   - **Subject**: Email subject line
   - **Body**: Email body with HTML support
   
   **Available Placeholders:**
   - `{user_name}` - User's full name
   - `{entity_name}` - Training/Test name
   - `{deadline}` - Due date
   - `{completion_status}` - Current completion status

   **Recipients:**
   - **Send To**: Choose recipient type:
     - All Participants
     - Not Completed (only those who haven't finished)
     - Completed (only those who finished)
     - Admins Only
     - Custom Email List (enter comma-separated emails)

   **Options:**
   - **Include Completion Report**: Attach CSV with user completion data
   - **Enable This Reminder**: Toggle to enable/disable

3. Click "Save Reminder"

### Editing a Reminder
1. Click the pencil icon next to the reminder
2. Modify the fields as needed
3. Click "Save Reminder"

### Sending a Test Email
1. Click the send/paper plane icon next to a reminder
2. A test email will be sent to your admin account
3. Check your inbox to verify the email format

### Toggling Reminder Status
1. Click the "Enabled"/"Disabled" button next to a reminder
2. The button will toggle between states
3. Disabled reminders won't be sent

### Deleting a Reminder
1. Click the trash icon next to a reminder
2. Confirm the deletion
3. The reminder will be permanently deleted

---

## Email Template Examples

### Example 1: Training Completion Reminder (Before Due Date)
```
Reminder Name: Training Completion - 2 Days Before
Entity Type: Training
Trigger Event: Before Due Date
Days Offset: 2
Send Time: 09:00
Subject: Reminder: Complete Your {entity_name} Training
Body:
Dear {user_name},

This is a reminder that your training "{entity_name}" is due in 2 days.

Current Status: {completion_status}
Deadline: {deadline}

Please complete the training before the due date.

Best regards,
Training Team
```

### Example 2: Test Follow-up (After Due Date)
```
Reminder Name: Test Completion Follow-up
Entity Type: Test
Trigger Event: After Due Date
Days Offset: 1
Send Time: 10:00
Subject: Action Required: Complete Your {entity_name}
Body:
Dear {user_name},

Your test "{entity_name}" was due on {deadline}.

Current Status: {completion_status}

Please complete the test as soon as possible.

Contact your training manager for assistance.

Best regards,
Training Team
```

---

## Monitoring and Logs

### Check Scheduler Logs
View Laravel logs to verify reminders are being sent:
```bash
tail -f storage/logs/laravel.log
```

### Database Records
View sent reminders in the database:
```bash
SELECT * FROM reminders WHERE last_sent_at IS NOT NULL ORDER BY last_sent_at DESC;
```

### Queue Status
If using queue, check queue jobs:
```bash
php artisan queue:failed
php artisan queue:retry all
```

---

## Troubleshooting

### Reminders Not Sending

1. **Check if scheduler is running:**
   ```bash
   php artisan schedule:list
   ```

2. **Run scheduler manually to test:**
   ```bash
   php artisan schedule:run
   ```

3. **Run the command directly:**
   ```bash
   php artisan reminders:send
   ```

4. **Check Laravel logs:**
   ```bash
   tail storage/logs/laravel.log
   ```

### Emails Not Received

1. **Verify mail configuration in `.env`**
2. **Test mail connection:**
   ```bash
   php artisan tinker
   > Mail::raw('Test', function($msg) { $msg->to('test@example.com'); })
   ```

3. **Check email spam folder**
4. **Verify sender email is allowed**

### Database Connection Issues

1. **Clear application cache:**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

2. **Check database migrations:**
   ```bash
   php artisan migrate:status
   ```

---

## Performance Optimization

### For High-Volume Emails
1. Use queue processing with Redis
2. Increase queue workers:
   ```bash
   php artisan queue:work --pool=default --workers=4
   ```

3. Batch recipients to avoid timeouts

### Disable CSV Reports for Large Users Lists
- Only enable CSV reports if necessary
- CSV generation takes time with many users

---

## API Reference

### Command
```bash
# Send reminders immediately
php artisan reminders:send

# Send reminders with output
php artisan reminders:send -v
```

### Routes
```
GET    /admin/reminders              - List all reminders (Reminders.index)
GET    /admin/reminders/create       - Create form (Reminders.create)
POST   /admin/reminders/store        - Store reminder (Reminders.store)
GET    /admin/reminders/{id}/edit    - Edit form (Reminders.edit)
PUT    /admin/reminders/{id}/update  - Update reminder (Reminders.update)
DELETE /admin/reminders/{id}/destroy - Delete reminder (Reminders.destroy)
POST   /admin/reminders/{id}/toggle-status - Toggle status (Reminders.toggleStatus)
POST   /admin/reminders/{id}/send-test - Send test email (Reminders.sendTest)
```

---

## Database Schema

```sql
CREATE TABLE reminders (
  id BIGINT PRIMARY KEY,
  name VARCHAR(255),
  entity_type ENUM('training', 'test'),
  trigger_event VARCHAR(255),
  days_offset INT,
  send_time TIME,
  subject VARCHAR(255),
  body LONGTEXT,
  recipient_type ENUM('all_participants','not_completed','completed','admins','custom'),
  custom_emails TEXT,
  include_completion_report BOOLEAN DEFAULT FALSE,
  enabled BOOLEAN DEFAULT TRUE,
  last_sent_at TIMESTAMP NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  INDEX(entity_type),
  INDEX(enabled)
);
```

---

## Security Notes

1. **Always validate user input** - The controller uses validation rules
2. **Use authentication middleware** - All routes require auth
3. **Sanitize email addresses** - Custom emails are filtered
4. **Protect admin reports** - Only send to verified admin emails
5. **Use HTTPS** - Ensure secure transmission of emails
6. **Backup database** - Reminders table contains important configuration

---

## Support & Next Steps

For additional features or customization:
1. Modify recipient selection logic in `SendReminders.php`
2. Add additional email placeholders
3. Implement conditional reminders based on custom fields
4. Add webhook notifications
5. Create reminder templates library

---

**Version:** 1.0  
**Last Updated:** {{ now()->format('M d, Y') }}  
**Status:** Production Ready
