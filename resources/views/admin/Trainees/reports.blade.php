@extends('admin.layouts.default')
@section('content')


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">

    <!-- Include Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script src="https://cdn.ckeditor.com/4.15.0/standard-all/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <div class="content-wrapper">
        <div class="page-header">
            <h1>
                User Report's
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i>
                            Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">User Report's</li>

                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="box-header with-border pd-custom">
                            <div class="listing-btns">
                                <h1 class="box-title">Test's List</h1>
                            </div>
                        </div>
                        <div class="trainingTabContent">

                            <div class="tab-content" id="tab-Content">
                                <div class="tab-pane fade show active" id="allTab" role="tabpanel">
                                    <div class="box p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover table table-bordered mt-2 ">
                                                <thead class="theadLight">
                                                    <tr>
                                                        <th>Test Name</th>
                                                        <th class="text-center">Passing Score</th>
                                                        <th class="text-center">Obtaine Score</th>
                                                        <th class="text-center">Result</th>
                                                        <th class="text-center">View</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @if (isset($testResults) && !empty($testResults))
                                                        @foreach ($testResults as $result)
                                                            <tr>
                                                                <td>{{ $result['test_details']->title }} </td>
                                                                {{-- @if ($result->status == 0)
                                                                    <td class="text-center">Ongoing </td>
                                                                @elseif($result->status == 2)
                                                                    <td class="text-center">Upcoming</td>
                                                                @else
                                                                    <td class="text-center">Completed </td>
                                                                @endif --}}
                                                                <td class="text-center">
                                                                    {{ $result['test_details']->minimum_marks }}%</td>
                                                                <td class="text-center">
                                                                    {{ optional($result['test_results'])->percentage !== null ? $result['test_results']->percentage . '%' : '--' }}
                                                                </td>
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ $result['test_results']->result ?? '--' }}</td>
                                                                @if (isset($result['test_results']->result) && !empty($result['test_results']->result))
                                                                    <td class="text-center action-td">
                                                                        <div class="actionGroup">
                                                                            <a href="{{ route('Trainees.Test.report', ['user_id' => $result['test_results']->user_id, 'test_id' => $result['test_results']->test_id]) }}"
                                                                                class="btn btn-info"><span
                                                                                    class="fa fa-eye"></span>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                @else
                                                                    <td class="text-center action-td">

                                                                    </td>
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="5" class="text-center">Test Not Found </td>
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
        {{-- <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="box-header with-border pd-custom">
                            <div class="listing-btns">
                                <h1 class="box-title">Training's List</h1>
                            </div>
                        </div>
                        <div class="trainingTabContent">

                            <div class="tab-content" id="tab-Content">
                                <div class="tab-pane fade show active" id="allTab" role="tabpanel">
                                    <div class="box p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover table table-bordered mt-2 ">
                                                <thead class="theadLight">
                                                    <tr>
                                                        <th>Training Name</th>
                                                        <th class="text-center">Training Status</th>
                                                        <th class="text-center">Course count</th>
                                                        <th class="text-center">Test count</th>
                                                        <th class="text-center">Number of Trainee</th>
                                                        <th class="text-center">Minimum Passing Score</th>
                                                        <th class="text-center">Report</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @if ($allTraining)
                                                        @foreach ($allTraining as $training)
                                                            @php
                                                                // Count the number of training courses with a not-null "test_id"
                                                                $countCoursesWithTestId = $training->training_courses->whereNotNull('test_id')->count();
                                                                $averageMinimumMarks = $training->training_courses->whereNotNull('test_id')
                                                                    ->avg(function ($course) {
                                                                        return optional($course->test)->minimum_marks ?? 0;
                                                                    });
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $training->title }} </td>
                                                                @if ($training->status == 0)
                                                                    <td class="text-center">Ongoing </td>
                                                                @elseif($training->status == 2)
                                                                    <td class="text-center">Upcoming</td>
                                                                @else
                                                                    <td class="text-center">Completed </td>
                                                                @endif
                                                                <td class="text-center">
                                                                    {{ count($training->training_courses) ?? '0' }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ $countCoursesWithTestId ?? '0' }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ count($training->training_participants) ?? '0' }}
                                                                </td>
                                                                <td class="text-center">{{ $averageMinimumMarks }}%
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
                                                            Training Not Created
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

        </div> --}}

    </div>

    <style>
        .paddingtop {
            padding-top: 11px;
        }
    </style>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: 'your_controller_url', // Replace with your actual controller URL
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Assuming response.data is the array of user data
                    var users = response.data;

                    // Loop through the users and append rows to the table
                    $.each(users, function(index, user) {
                        var row = '<tr>' +
                            '<td>' + user.id + '</td>' +
                            '<td>' + user.fullname + '</td>' +
                            '<td>' + user.created_at + '</td>' +
                            '<td><span class="tag tag-success">Approved</span></td>' +
                            '<td>' + user.designation + '</td>' +
                            '</tr>';

                        $('#userTable tbody').append(row);
                    });
                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                }
            });
        });
    </script>

    <script>
        $('#training_id').select2({
            width: '100%', // Adjust the width of the dropdown
            // placeholder: 'Select options', // Placeholder text when nothing is selected
            // allowClear: true, // Allow clearing the selection
            // minimumResultsForSearch: Infinity // Hide the search input when the number of options is less than this value
        });
        $('#test_id').select2({
            width: '100%', // Adjust the width of the dropdown
            // placeholder: 'Select options', // Placeholder text when nothing is selected
            // allowClear: true, // Allow clearing the selection
            // minimumResultsForSearch: Infinity // Hide the search input when the number of options is less than this value
        });
    </script>
@stop
