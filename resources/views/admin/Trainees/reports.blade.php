@extends('admin.layouts.default')
@section('content')


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">

    <!-- Include Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script src="https://cdn.ckeditor.com/4.15.0/standard-all/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <div class="content-wrapper">
        <div class="page-header mb-4">
            <h1>User Report's</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-white p-2 rounded">
                    <li class="breadcrumb-item">
                        <a href="{{ URL::to('admin/dashboard') }}">
                            <i class="fa fa-dashboard"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">User Report's</li>
                </ol>
            </nav>
        </div>

        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light border-bottom">
                        <h3 class="mb-0 text-dark font-weight-bold">Test's List</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Test Name</th>
                                        <th class="text-center">Passing Score</th>
                                        <th class="text-center">Obtained Score</th>
                                        <th class="text-center">Result</th>
                                        <th class="text-center">View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($testResults) && !empty($testResults))
                                        @foreach ($testResults as $result)
                                            <tr>
                                                <td>{{ $result['test_details']->title }}</td>
                                                <td class="text-center">{{ $result['test_details']->minimum_marks }}%</td>
                                                <td class="text-center">
                                                    {{ optional($result['test_results'])->percentage !== null ? $result['test_results']->percentage . '%' : '--' }}
                                                </td>
                                                <td class="text-center">{{ $result['test_results']->result ?? '--' }}</td>
                                                <td class="text-center">
                                                    @if (!empty($result['test_results']->result))
                                                        <a href="{{ route('Trainees.Test.report', ['user_id' => $result['test_results']->user_id, 'test_id' => $result['test_results']->test_id]) }}"
                                                            class="btn btn-info btn-sm" title="View Test Report">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    @else
                                                        --
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Test Not Found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light border-bottom">
                        <h3 class="mb-0 text-dark font-weight-bold">Training List</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Training Name</th>
                                        <th class="text-center">Total Courses</th>
                                        <th class="text-center">Avg. Passing Score</th>
                                        <th class="text-center">Avg. Obtained Score</th>
                                        <th class="text-center">Overall Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($trainingResults as $tr)
                                        <tr>
                                            <td>{{ $tr['training']->title }}</td>
                                            <td class="text-center">{{ $tr['total_courses'] }}</td>
                                            <td class="text-center">{{ $tr['average_minimum_mark'] }}%</td>
                                            <td class="text-center">{{ $tr['average_obtain_marks'] }}%</td>
                                            <td class="text-center">
                                                @if ($tr['overall_status'] == 'Passed')
                                                    <span class="badge badge-success">Passed</span>
                                                @else
                                                    <span class="badge badge-danger">Failed</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No Trainings Assigned</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

  

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
