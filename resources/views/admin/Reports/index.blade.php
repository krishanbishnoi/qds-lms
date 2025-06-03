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
                            <div class="listing-btns">
                                <h1 class="box-title">Test's List</h1>
                            </div>
                        </div>
                        <div class="trainingTabContent">

                            <div class="tab-content" id="tab-Content">
                                <div class="tab-pane fade show active" id="allTab" role="tabpanel">
                                    <div class="box p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover brdrclr mt-2">
                                                <thead class="theadLight">
                                                    <tr>
                                                        <th>Test Name</th>
                                                        <th class="text-center">Test Status</th>
                                                        <th class="text-center">Passing Score</th>
                                                        <th class="text-center">Total Trainee</th>
                                                        <th class="text-center">Total Test Attendee</th>
                                                        <th class="text-center">Passed Trainee</th>
                                                        <th class="text-center">Report</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @if ($allTest)
                                                        @foreach ($allTest as $test)
                                                            <tr>
                                                                <td>{{ $test->title }} </td>
                                                                @if ($test->status == 0)
                                                                    <td class="text-center">Ongoing </td>
                                                                @elseif($test->status == 2)
                                                                    <td class="text-center">Upcoming</td>
                                                                @else
                                                                    <td class="text-center">Completed </td>
                                                                @endif
                                                                <td class="text-center">{{ $test->minimum_marks }}%</td>
                                                                <td class="text-center">
                                                                    {{ count($test->test_participants) ?? '0' }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ count($test->test_results) ?? '0' }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ count($test->test_results->where('result','Passed')) ?? '0' }}</td>
                                                                <td class="text-center">
                                                                    <div class="actionGroup"><a
                                                                            href="{{ route('Reports.downloads', $test->id) }}"
                                                                            class="deletBtn"><span
                                                                                class="fas fa-download"></span></a>
                                                                    </div>
                                                                    {{-- <a class="btn btn-primary px-3" href="{{ route('Test.report', $model->id) }}">Report
                                                                        <span class="fas fa-download"style="margin-left:10px"></span>
                                                                    </a> --}}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            Test Not Created
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
        <div class="row">
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
                                            <table class="table table-hover brdrclr mt-2">
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

        </div>
        {{-- <div class="box search-panel collapsed-box">

            <div class="box-body">
                {{ Form::open(['method' => 'get', 'role' => 'form', 'route' => "$modelName.index", 'class' => 'row mws-form']) }}
                {{ Form::hidden('display') }}
                <div class="col-md-4 col-sm-4">
                    <div class="form-group ">
                        {!! Html::decode(
                            Form::label(
                                'test_id',
                                trans('Test') .
                                    '<span
                                                    class="requireRed"></span>',
                                ['class' => 'mws-form-label'],
                            ),
                        ) !!}
                        <div class="mws-form-item">
                            {{ Form::select('test_id[]', $tests, '', ['class' => 'form-control', 'id' => 'test_id', 'multiple']) }}
                            <div class="error-message help-inline">
                                <?php echo $errors->first('test_id'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="form-group ">
                        {!! Html::decode(
                            Form::label(
                                'training_id',
                                trans('Training') .
                                    '<span
                                                    class="requireRed"></span>',
                                ['class' => 'mws-form-label'],
                            ),
                        ) !!}
                        <div class="mws-form-item">
                            {{ Form::select('training_id[]', $trainings, '', ['class' => 'form-control', 'id' => 'training_id', 'multiple']) }}
                            <div class="error-message help-inline">
                                <?php echo $errors->first('training_id'); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-4 paddingtop">
                    <div class="d-flex">
                        <button class="btn btn-primary mr-2 px-4"><i class='fa fa-search '></i> Download</button>
                        <a href='{{ route("$modelName.index") }}' class="btn btn-primary"> <i class="fa fa-refresh "></i>
                            {{ trans('Clear') }}</a>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div> --}}

    </div>

    <style>
        .paddingtop {
            padding-top: 11px;
        }
    </style>

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
