@extends('admin.layouts.default')
@section('content')


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">

    <!-- Include Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script src="{{ asset('all-cdn/ckeditor.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <div class="content-wrapper">
        <div class="page-header">
            <h1>
                {{ $sectionName }}
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i>
                            Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $sectionName }}</li>

                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="box-header with-border pd-custom">
                            <div class="listing-btns d-flex justify-content-between mb-3">
                                <h1 class="box-title">Training Users List</h1>
                            </div>
                            <div>
                                <h5 class="mb-3" style="color: #555;">
                                    <span style="font-weight: 500;">Training Name:</span>
                                    <span style="font-weight: 600; color: #222;">{{ $training->title }}</span>
                                </h5>
                            </div>
                        </div>
                        <div class="trainingTabContent">

                            <div class="tab-content" id="tab-Content">
                                <div class="tab-pane fade show active" id="allTab" role="tabpanel">
                                    <div class="box p-0">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>SN.</th>
                                                        <th>User Name</th>
                                                        <th>Email</th>
                                                        <th>Total Documents</th>
                                                        <th>Completed Documents</th>
                                                        <th>Completion %</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($userProgress as $index => $user)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $user['name'] }}</td>
                                                            <td>{{ $user['email'] }}</td>
                                                            <td>{{ $user['total_documents'] }}</td>
                                                            <td>{{ $user['completed_documents'] }}</td>
                                                            <td>{{ $user['completion_percentage'] }}%</td>
                                                            <td>
                                                                <span
                                                                    class="badge {{ $user['status'] == 'Completed' ? 'bg-success' : 'bg-warning' }}">
                                                                    {{ $user['status'] }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="7" class="text-center">No users found for this
                                                                training.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>

                                            <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Back</a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>


    </div>
@stop
