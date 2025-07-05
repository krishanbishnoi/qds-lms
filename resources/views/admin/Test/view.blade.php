@extends('admin.layouts.default')
@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.10/clipboard.min.js"></script>

    <script>
        jQuery(document).ready(function() {
            $('#start_from').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#start_to').datetimepicker({
                format: 'YYYY-MM-DD'
            });

        });
    </script>
    <div class="content-wrapper">
        <div class="page-header">
            <h1>
                View {{ $sectionName }}
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ URL::to('admin/dashboard') }}">
                            Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route($modelName . '.index') }}">
                            Users</a>
                    </li>
                    <li class="active"> / View {{ $sectionNameSingular }}</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between gap-1 align-items-center mb-3">
                            <h3 class="box-title">Test Details</h3>
                            <a id="copyButton" class="btn btn-primary px-3"
                                data-clipboard-text="{{ route('userTestDetails.index.link.copied', $model->id) }}">
                                Get Test Link
                                <span class="fas fa-link" style="margin-left:10px"></span>
                            </a>
                            <a class="btn btn-primary px-3" href="{{ route('Test.report', $model->id) }}">Report
                                <span class="fas fa-download"style="margin-left:10px"></span>
                            </a>
                        </div>
                        <table class="table table-hover brdrclr" width="100%">
                            <tbody>


                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Title</th>
                                    <td data-th='Category Name' class="txtFntSze">{{ ucfirst($model->title) }}</td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Created By</th>
                                    <td data-th='Category Name' class="txtFntSze">{{ $model->created_by }}</td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Minimum Marks</th>
                                    <td data-th='Category Name' class="txtFntSze">{{ $model->minimum_marks }}</td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Number Of Attempts</th>
                                    <td data-th='Category Name' class="txtFntSze">{{ $model->number_of_attempts }}</td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Description</th>
                                    <td data-th='Category Name' class="txtFntSze">{!! ucfirst($model->description) !!}</td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Type</th>
                                    <td data-th='Category Name' class="txtFntSze">
                                        @if ($model->type == 'training_test')
                                            Training Test
                                        @elseif($model->type == 'feedback_test')
                                            Feedback Test
                                        @else
                                            Regular Test
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Is Active</th>
                                    <td data-th='Category Name' class="txtFntSze">{{ $model->is_active }}</td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Status</th>
                                    <td data-th='Status' class="txtFntSze">
                                        @if ($model->is_active == 1)
                                            <span class="label label-success">{{ trans('Activated') }}</span>
                                        @else
                                            <span class="label label-warning">{{ trans('Deactivated') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="box">
            <div class="box-body">
                <div class="box-header with-border">
                    <h3 class="box-title">Assign Question List</h3>

                </div>
                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive table-res-common">
                                    <table class="table table-hover brdrclr" width="100%">
                                        <thead>
                                            <tr>


                                                <th>{{ trans('SN.') }}</th>

                                                <th>{{ trans('Question') }}</th>
                                                <th>{{ trans('Type') }}</th>
                                                <!-- <th>{{ trans('View Options') }}</th> -->

                                            </tr>
                                        </thead>
                                        <tbody id="powerwidgets">
                                            <?php $number = 1; ?>
                                            @if (!empty($questions))
                                                @foreach ($questions as $question)
                                                    <tr class="items-inner">
                                                        <td>{{ $number++ }}</td>
                                                        <td>{{ $question->question }}</td>
                                                        <td>{{ $question->question_type }}</td>
                                                        <!-- <td>{{ $question->mobile_number }}</td> -->
                                                    </tr>
                                                @endforeach
                                            @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box">
            <div class="box-body">
                <div class="box-header with-border">
                    <h3 class="box-title">Assign Manager List</h3>

                </div>
                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive table-res-common">
                                    <table class="table table-hover brdrclr" width="100%">
                                        <thead>
                                            <tr>


                                                <th>{{ trans('SN.') }}</th>

                                                <th>{{ trans('Name') }}</th>
                                                <th>{{ trans('Email') }}</th>
                                                <th>{{ trans('Mobile Number') }}</th>

                                            </tr>
                                        </thead>
                                        <tbody id="powerwidgets">
                                            <?php $number = 1; ?>
                                            @if (!empty($manager_details))
                                                @foreach ($manager_details as $manager_detail)
                                                    <tr class="items-inner">
                                                        <td>{{ $number++ }}</td>
                                                        <td>{{ $manager_detail->fullname }}</td>
                                                        <td>{{ $manager_detail->email }}</td>
                                                        <td>{{ $manager_detail->mobile_number }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="box">
            <div class="box-body">
                <div class="box-header with-border">
                    <h3 class="box-title">Assign Trainer List</h3>

                </div>
                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive table-res-common">
                                    <table class="table table-hover brdrclr" width="100%">
                                        <thead>
                                            <tr>


                                                <th>{{ trans('SN.') }}</th>

                                                <th>{{ trans('Name') }}</th>
                                                <th>{{ trans('Email') }}</th>
                                                <th>{{ trans('Mobile Number') }}</th>

                                            </tr>
                                        </thead>
                                        <tbody id="powerwidgets">
                                            <?php $number = 1; ?>
                                            @if (!empty($trainer_details))
                                                @foreach ($trainer_details as $trainer_detail)
                                                    <tr class="items-inner">
                                                        <td>{{ $number++ }}</td>
                                                        <td>{{ $trainer_detail->fullname }}</td>
                                                        <td>{{ $trainer_detail->email }}</td>
                                                        <td>{{ $trainer_detail->mobile_number }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box">
            <div class="box-body">
                <div class="box-header with-border">
                    <h3 class="box-title">Assign Trainees List</h3>
                </div>
                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive table-res-common">
                                    <table class="table table-hover brdrclr" width="100%">
                                        <thead>
                                            <tr>
                                                <th>{{ trans('SN.') }}</th>
                                                <th>{{ trans('Employee Id') }}</th>
                                                <th>{{ trans('Name') }}</th>
                                                <th>{{ trans('Email') }}</th>
                                                <th>{{ trans('Mobile Number') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="powerwidgets">
                                            <?php $number = 1; ?>
                                            @if (!empty($trainee_details))
                                                @foreach ($trainee_details as $trainee_detail)
                                                    <tr class="items-inner">
                                                        <td>{{ $number++ }}</td>
                                                        <td>{{ $trainee_detail->olms_id }}</td>
                                                        <td>{{ $trainee_detail->fullname }}</td>
                                                        <td>{{ $trainee_detail->email }}</td>
                                                        <td>{{ $trainee_detail->mobile_number }}</td>
                                                        <td>
                                                            {{-- @if (
                                                                $trainee_detail->answers->contains(function ($answer) {
                                                                    return !empty($answer->free_text_answer);
                                                                }))
                                                                <a href='{{ route("$modelName.view.answer", ['test_id' => $model->id, 'trainee_id' => $trainee_detail->id]) }}'
                                                                    class="btn btn-warning"
                                                                    title="Check Subjective Answer"> <span
                                                                        class="fas fa-edit"></span></a>
                                                            @endif --}}
                                                            <a href="{{ route('test.wise.report', ['user_id' => $trainee_detail->id, 'test_id' => $model->id]) }}"
                                                                class="btn btn-info"
                                                                title="View All Question & Answers"><span
                                                                    class="fa fa-question"></span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var clipboard = new ClipboardJS('#copyButton');

        clipboard.on('success', function(e) {
            console.log('Text copied to clipboard: ' + e.text);
            alert('Link copied to clipboard!');
            e.clearSelection();
        });

        clipboard.on('error', function(e) {
            alert('Unable to copy text to clipboard', e);
            e.clearSelection();
        });
    </script>

    <script>
        // $('.confirm').on('click', function (e) {
        //         if (confirm($(this).data('confirm'))) {
        //             return true;
        //         }
        //         else {
        //             return false;
        //         }
        //     });

        $(function() {
            $(document).on('click', '.delete_any_item', function(e) {
                e.stopImmediatePropagation();
                url = $(this).attr('href');
                bootbox.confirm("Are you sure want to delete this ?",
                    function(result) {
                        if (result) {
                            window.location.replace(url);
                        }
                    });
                e.preventDefault();
            });

            /**
             * Function to change status
             *
             * @param null
             *
             * @return void
             */
            $(document).on('click', '.status_any_item', function(e) {


                e.stopImmediatePropagation();
                url = $(this).attr('href');
                bootbox.confirm("Are you sure want to change status ?",
                    function(result) {
                        if (result) {
                            window.location.replace(url);
                        }
                    });
                e.preventDefault();
            });
        });
    </script>
@stop
