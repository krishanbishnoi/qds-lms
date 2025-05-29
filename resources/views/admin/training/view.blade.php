@extends('admin.layouts.default')
@section('content')
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
                    <a href="{{ route($modelName.'.index')}}">
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
                                <td data-th='Category Name' class="txtFntSze">{{ $model->type }}</td>
                            </tr>
                            <tr>
                                <th width="30%" class="text-right txtFntSze">Skip</th>
                                <td data-th='Category Name' class="txtFntSze">{{ $model->skip }}</td>
                            </tr>
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
                <h3 class="box-title">Training Course List</h3>

            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-res-common">
                                <table class="table table-hover brdrclr" width="100%">
                                    <thead>
                                        <tr>


                                            <th>{{ trans("Sr.") }}</th>

                                            <th>{{ trans("Title") }}</th>
                                            <th>{{ trans("Start Date") }}</th>
                                            <th>{{ trans("End Date") }}</th>

                                        </tr>
                                    </thead>
                                    <tbody id="powerwidgets">
                                        <?php  $number  =  1;  ?>
                                        @if(!empty($courses))
                                        @foreach($courses as $course)
                                        <tr class="items-inner">
                                            <td>{{$number++}}</td>
                                            <td>{{$course->title}}</td>
                                            <td>{{$course->start_date_time}}</td>
                                            <td>{{$course->end_date_time}}</td>
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


                                            <th>{{ trans("Sr.") }}</th>

                                            <th>{{ trans("Name") }}</th>
                                            <th>{{ trans("Email") }}</th>
                                            <th>{{ trans("Mobile Number") }}</th>

                                        </tr>
                                    </thead>
                                    <tbody id="powerwidgets">
                                        <?php  $number  =  1;  ?>
                                        @if(!empty($manager_details))
                                        @foreach($manager_details as $manager_detail)
                                        <tr class="items-inner">
                                            <td>{{$number++}}</td>
                                            <td>{{$manager_detail->fullname}}</td>
                                            <td>{{$manager_detail->email}}</td>
                                            <td>{{$manager_detail->mobile_number}}</td>
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


                                            <th>{{ trans("Sr.") }}</th>

                                            <th>{{ trans("Name") }}</th>
                                            <th>{{ trans("Email") }}</th>
                                            <th>{{ trans("Mobile Number") }}</th>

                                        </tr>
                                    </thead>
                                    <tbody id="powerwidgets">
                                        <?php  $number  =  1;  ?>
                                        @if(!empty($trainer_details))
                                        @foreach($trainer_details as $trainer_detail)
                                        <tr class="items-inner">
                                            <td>{{$number++}}</td>
                                            <td>{{$trainer_detail->fullname}}</td>
                                            <td>{{$trainer_detail->email}}</td>
                                            <td>{{$trainer_detail->mobile_number}}</td>
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
                                            <th>{{ trans("Sr.") }}</th>

                                            <th>{{ trans("Name") }}</th>
                                            <th>{{ trans("Email") }}</th>
                                            <th>{{ trans("Mobile Number") }}</th>

                                        </tr>
                                    </thead>
                                    <tbody id="powerwidgets">
                                        <?php  $number  =  1;  ?>
                                        @if(!empty($trainee_details))
                                        @foreach($trainee_details as $trainee_detail)
                                        <tr class="items-inner">
                                            <td>{{$number++}}</td>
                                            <td>{{$trainee_detail->fullname}}</td>
                                            <td>{{$trainee_detail->email}}</td>
                                            <td>{{$trainee_detail->mobile_number}}</td>
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
    <style>
    .padding {
        padding-top: 20px;
    }
    </style>
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
