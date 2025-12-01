@extends('admin.layouts.default')
@section('content')

<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-bell-ring"></i>
            </span>
            Reminder Mail Setup
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Reminders</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h4 class="card-title">All Reminders</h4>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('Reminders.create') }}" class="btn btn-gradient-primary">
                                <i class="mdi mdi-plus"></i> Add New Reminder
                            </a>
                        </div>
                    </div>

                    @if($reminders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Entity Type</th>
                                        <th>Trigger Event</th>
                                        <th>Recipients</th>
                                        <th>Status</th>
                                        <th>Last Sent</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reminders as $reminder)
                                        <tr>
                                            <td>
                                                <strong>{{ $reminder->name }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge @if($reminder->entity_type === 'training') badge-info @else badge-warning @endif">
                                                    {{ ucfirst($reminder->entity_type) }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ ucwords(str_replace('_', ' ', $reminder->trigger_event)) }}
                                                @if($reminder->days_offset !== null)
                                                    <small class="text-muted">({{ $reminder->days_offset }} days)</small>
                                                @endif
                                            </td>
                                            <td>
                                                {{ ucwords(str_replace('_', ' ', $reminder->recipient_type)) }}
                                            </td>
                                            <td>
                                                <form action="{{ route('Reminders.toggleStatus', $reminder->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm @if($reminder->enabled) btn-success @else btn-secondary @endif">
                                                        {{ $reminder->enabled ? 'Enabled' : 'Disabled' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                @if($reminder->last_sent_at)
                                                    {{ $reminder->last_sent_at->format('M d, Y H:i') }}
                                                @else
                                                    <span class="text-muted">Never</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('Reminders.edit', $reminder->id) }}" class="btn btn-sm btn-info" title="Edit">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('Reminders.sendTest', $reminder->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-primary" title="Send Test">
                                                            <i class="mdi mdi-send"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('Reminders.destroy', $reminder->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger delete_any_item" title="Delete">
                                                            <i class="mdi mdi-delete"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $reminders->links('pagination::bootstrap-4') }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="mdi mdi-information"></i> No reminders found. <a href="{{ route('Reminders.create') }}">Create one now</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
