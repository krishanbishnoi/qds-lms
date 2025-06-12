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
                                <h1 class="box-title">Training List</h1>
                            </div>
                        </div>
                        <div class="trainingTabContent">

                            <div class="tab-content" id="tab-Content">
                                <div class="tab-pane fade show active" id="allTab" role="tabpanel">
                                    <div class="box p-0">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>SN.</th>
                                                        <th>Training Name</th>
                                                        {{-- <th class="text-center">Training Status</th> --}}
                                                        <th class="text-center">Course count</th>
                                                        <th class="text-center">Test count</th>
                                                        <th class="text-center">Total Users</th>
                                                        <th class="text-center">Attempted Users Count</th>
                                                        <th class="text-center">Minimum Passing Score</th>
                                                        <th class="text-center">Report</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($allTraining)
                                                        @foreach ($allTraining as $training)
                                                            @php
                                                                // Count the number of training courses with a not-null "test_id"
                                                                $countCoursesWithTestId = $training->training_courses
                                                                    ->where('test_id', '!=', '')
                                                                    ->count();

                                                                $averageMinimumMarks = $training->training_courses
                                                                    ->where('test_id', '!=', '')
                                                                    ->avg(function ($course) {
                                                                        return optional($course->test)->minimum_marks ??
                                                                            0;
                                                                    });

                                                                // Calculate attempted users count
                                                                $attemptedUsersCount = 0;
                                                                foreach ($training->training_courses as $course) {
                                                                    if ($course->test_id) {
                                                                        $attemptedUsersCount += App\models\TrainingTestResult::where(
                                                                            'training_id',
                                                                            $training->id,
                                                                        )
                                                                            ->where('course_id', $course->id)
                                                                            ->distinct('user_id')
                                                                            ->count('user_id');
                                                                    }
                                                                }
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td class="text-wrap">{{ $training->title }} </td>
                                                                {{-- @if ($training->status == 0)
                                                                    <td class="text-center">Ongoing </td>
                                                                @elseif($training->status == 2)
                                                                    <td class="text-center">Upcoming</td>
                                                                @else
                                                                    <td class="text-center">Completed </td>
                                                                @endif --}}
                                                                <td class="text-center">
                                                                    {{ count($training->training_courses) ?? '0' }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ $countCoursesWithTestId ?? '0' }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ count($training->training_participants_count) ?? '0' }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ $attemptedUsersCount ?? '0' }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ number_format($averageMinimumMarks, 2) ?? '0' }}{{ number_format($averageMinimumMarks, 2) != 0 ? '%' : '' }}
                                                                </td>
                                                                <td class="text-center">
                                                                    <div class="actionGroup"><a
                                                                            href="{{ route('Reports.downloads.training', $training->id) }}"
                                                                            class="deletBtn"><span
                                                                                class="fas fa-download"></span></a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="7" class="text-center">Training Not Created</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
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
