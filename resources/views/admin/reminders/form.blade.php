@extends('admin.layouts.default')
@section('content')

<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-bell-ring"></i>
            </span>
            {{ isset($reminder) ? 'Edit Reminder' : 'Create New Reminder' }}
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('Reminders.index') }}">Reminders</a></li>
                <li class="breadcrumb-item active">{{ isset($reminder) ? 'Edit' : 'Create' }}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form action="{{ isset($reminder) ? route('Reminders.update', $reminder->id) : route('Reminders.store') }}" method="POST" id="reminderForm">
                        @csrf
                        @if(isset($reminder))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Reminder Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" 
                                    value="{{ old('name', $reminder->name ?? '') }}" placeholder="e.g., Training Completion Reminder" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="entity_type" class="form-label">Entity Type <span class="text-danger">*</span></label>
                                <select class="form-control @error('entity_type') is-invalid @enderror" id="entity_type" name="entity_type" required>
                                    <option value="">-- Select Entity Type --</option>
                                    @foreach($entities as $key => $label)
                                        <option value="{{ $key }}" {{ old('entity_type', $reminder->entity_type ?? '') === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('entity_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="trigger_event" class="form-label">Trigger Event <span class="text-danger">*</span></label>
                                <select class="form-control @error('trigger_event') is-invalid @enderror" id="trigger_event" name="trigger_event" required>
                                    <option value="">-- Select Trigger Event --</option>
                                    @foreach($triggers as $key => $label)
                                        <option value="{{ $key }}" {{ old('trigger_event', $reminder->trigger_event ?? '') === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('trigger_event')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="days_offset" class="form-label">Days Offset</label>
                                <input type="number" class="form-control @error('days_offset') is-invalid @enderror" id="days_offset" name="days_offset" 
                                    value="{{ old('days_offset', $reminder->days_offset ?? '') }}" placeholder="e.g., 2" min="0" max="365">
                                <small class="form-text text-muted">Days before/after the trigger event</small>
                                @error('days_offset')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="send_time" class="form-label">Trigger Time</label>
                                <input type="time" class="form-control @error('send_time') is-invalid @enderror" id="send_time" name="send_time" 
                                    value="{{ old('send_time', $reminder->send_time ?? '') }}">
                                <small class="form-text text-muted">Time of day to send</small>
                                @error('send_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Email Subject <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" 
                                value="{{ old('subject', $reminder->subject ?? '') }}" placeholder="e.g., Reminder: Complete your training" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="body" class="form-label">Email Body <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body" rows="8" placeholder="Write email content here..." required>{{ old('body', $reminder->body ?? '') }}</textarea>
                            <small class="form-text text-muted">
                                You can use placeholders: {user_name}, {entity_name}, {deadline}, {completion_status}
                            </small>
                            @error('body')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="recipient_type" class="form-label">Send To <span class="text-danger">*</span></label>
                                <select class="form-control @error('recipient_type') is-invalid @enderror" id="recipient_type" name="recipient_type" required>
                                    <option value="">-- Select Recipients --</option>
                                    @foreach($recipientTypes as $key => $label)
                                        <option value="{{ $key }}" {{ old('recipient_type', $reminder->recipient_type ?? '') === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('recipient_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3" id="custom_emails_group" style="display: none;">
                                <label for="custom_emails" class="form-label">Custom Email Addresses</label>
                                <input type="text" class="form-control @error('custom_emails') is-invalid @enderror" id="custom_emails" name="custom_emails" 
                                    value="{{ old('custom_emails', $reminder->custom_emails ?? '') }}" placeholder="email1@example.com, email2@example.com">
                                <small class="form-text text-muted">Comma-separated email addresses</small>
                                @error('custom_emails')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input type="hidden" name="include_completion_report" value="0">
                                    <input type="checkbox" class="form-check-input" id="include_completion_report" name="include_completion_report" value="1"
                                        {{ old('include_completion_report', $reminder->include_completion_report ?? 0) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="include_completion_report">
                                        Include Completion Report (CSV)
                                    </label>
                                    <small class="form-text text-muted d-block">Send a CSV attachment with user completion status to admins</small>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input type="hidden" name="enabled" value="0">
                                    <input type="checkbox" class="form-check-input" id="enabled" name="enabled" value="1"
                                        {{ old('enabled', $reminder->enabled ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="enabled">
                                        Enable This Reminder
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-gradient-primary me-2">
                                    <i class="mdi mdi-content-save"></i> Save Reminder
                                </button>
                                <a href="{{ route('Reminders.index') }}" class="btn btn-secondary">
                                    <i class="mdi mdi-close"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const recipientTypeSelect = document.getElementById('recipient_type');
        const customEmailsGroup = document.getElementById('custom_emails_group');

        function toggleCustomEmailsField() {
            if (recipientTypeSelect.value === 'custom') {
                customEmailsGroup.style.display = 'block';
            } else {
                customEmailsGroup.style.display = 'none';
            }
        }

        recipientTypeSelect.addEventListener('change', toggleCustomEmailsField);
        toggleCustomEmailsField();
    });
</script>

@endsection
