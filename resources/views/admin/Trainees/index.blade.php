@extends('admin.layouts.default')
@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script>
        // jQuery(document).ready(function() {
        //     $('#start_from').datetimepicker({
        //         format: 'YYYY-MM-DD'
        //     });
        //     $('#start_to').datetimepicker({
        //         format: 'YYYY-MM-DD'
        //     });

        // });
    </script>
    <div class="content-wrapper">
        <div class="page-header">
            <h1>
                {{ $sectionName }}
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i>
                            Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $sectionName }}</li>
                </ol>
            </nav>
        </div>
        <div class="box search-panel collapsed-box">
            <div class="box-body">
                {{ Form::open(['method' => 'get', 'role' => 'form', 'route' => "$modelName.index", 'class' => 'row mws-form']) }}
                {{ Form::hidden('display') }}

                {{-- <div class="col-md-2 col-sm-2">
                <div class="form-group ">
                    {!! Html::decode(
                    Form::label(
                    'Select Certified Status',
                    trans('Select Certified Status') .
                    '<span class="requireRed">
                    </span>',
                    ['class' => 'mws-form-label'],
                    ),
                    ) !!}
                    {{ Form::select('is_certified', ['' => trans('All'), 1 => trans('Certified'), 0 => trans('Not Certified')], isset($searchVariable['is_certified']) ? $searchVariable['is_certified'] : '', ['class' => 'form-control']) }}
                </div>
            </div> --}}

                <div class="col-md-2 col-sm-2">
                    <div class="form-group ">
                        {!! Html::decode(
                            Form::label(
                                'Select Status',
                                trans('Select Status') .
                                    '<span class="requireRed">
                                                                                                                                                                                                                                                                                                                                                                                                                                            </span>',
                                ['class' => 'mws-form-label'],
                            ),
                        ) !!}
                        {{ Form::select('is_active', ['' => trans('All'), 1 => trans('Active'), 0 => trans('Inactive')], isset($searchVariable['is_active']) ? $searchVariable['is_active'] : '', ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="col-md-2 col-sm-2">
                    <div class="form-group ">
                        {!! Html::decode(
                            Form::label(
                                'olms_id',
                                trans('Id') .
                                    '<span class="requireRed">
                                                                                                                                                                                                                                                                                                                                                                                                                                            </span>',
                                ['class' => 'mws-form-label'],
                            ),
                        ) !!}
                        {{ Form::text('olms_id', isset($searchVariable['olms_id']) ? $searchVariable['olms_id'] : '', ['class' => ' form-control', 'placeholder' => 'Id']) }}
                    </div>
                </div>
                <!-- <div class="col-md-2 col-sm-2">
                                                                                            <div class="form-group ">
                                                                                                {!! Html::decode(
                                                                                                    Form::label('email', trans('Email') . '<span class="requireRed"> </span>', ['class' => 'mws-form-label']),
                                                                                                ) !!}
                                                                                                {{ Form::text('email', isset($searchVariable['email']) ? $searchVariable['email'] : '', ['class' => ' form-control', 'placeholder' => 'Email']) }}
                                                                                            </div>
                                                                                        </div> -->
                <div class="col-md-2 col-sm-2">
                    <div class="form-group ">
                        {!! Html::decode(
                            Form::label(
                                'mobile_number',
                                trans('Mobile Number') .
                                    '<span class="requireRed">
                                                                                                                                                                                                                                                                                                                                                                                                                                            </span>',
                                ['class' => 'mws-form-label'],
                            ),
                        ) !!}
                        {{ Form::text('mobile_number', isset($searchVariable['mobile_number']) ? $searchVariable['mobile_number'] : '', ['class' => ' form-control', 'placeholder' => 'Mobile Number']) }}
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 padding">

                    <div class="d-flex">

                        <button class="btn btn-primary mr-2 px-4"><i class='fa fa-search '></i> Search</button>
                        <a href='{{ route("$modelName.index") }}' class="btn btn-primary"> <i class="fa fa-refresh "></i>
                            {{ trans('Clear Search') }}</a>
                    </div>

                </div>
                {{ Form::close() }}
            </div>
        </div>
        <div class="box search-panel collapsed-box">
            <div class="box-body mb-4">
                <form action="{{ route('import.trainees') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="d-md-flex justify-content-between align-items-center gap-3">
                        <a class="btn btn-primary" href="{{ asset('sample-files/user-upload-sample-file.xlsx') }}">
                            Download
                            sample file</a>
                        <div class="form-group d-flex flex-column justify-content-center">
                            <input type="file" name="file" required>
                            <button class="btn btn-primary mt-2" type="submit">Upload Users</button>
                        </div>
                        <a class="btn btn-success" href="{{ route('export.trainees') }}">Download Users</a>
                    </div>
                </form>
            </div>
        </div>
        {{-- <hr>
        <div class="box search-panel collapsed-box">
            <div class="box-body mb-4">
                <form action="{{ route('import.change.designation') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="d-md-flex justify-content-between align-items-center gap-3">
                        <a class="btn btn-primary" onclick="downloadFileChangeDesignation()"> Download sample file</a>
                        <div class="form-group d-flex flex-column justify-content-center ps-2">
                            <input type="file" name="file" required>
                            <button class="btn btn-primary  mt-2" type="submit">Upload Change Designation Users</button>
                        </div>
                        <a class="btn btn-success" href="{{ route('export.trainees.all') }}">Download All Users</a>
                    </div>
                </form>
            </div>
        </div> --}}
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="box-header with-border pd-custom">
                            <div class="listing-btns">
                                <h1 class="box-title">{{ $sectionName }}'s List</h1>
                                <a href='{{ route("$modelName.add") }}' class="btn btn-success btn-small pull-right mb-2">
                                    {{ trans('Add New ') }}{{ $sectionNameSingular }} </a>
                                <button type="submit" name="submit" class="btn btn-danger pull-right trigger_event mb-2"
                                    title="Release Selected Games" id="trigger_event">Delete Users</button>
                            </div>
                        </div>
                        <table class="table table-hover brdrclr mt-2 " width="100%">
                            <thead class="theadLight">
                                <tr>
                                    <th width=:15%;> <input type="checkbox" id="checkAll" class='check_box_all'>All</th>

                                    <th width="5%">{{ trans('Sr.') }}</th>
                                    <th>
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('Id'),
                                            [
                                                'sortBy' => 'olms_id',
                                                'order' => $sortBy == 'olms_id' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'employee_id' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'employee_id' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>
                                    <th>
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('Full Name'),
                                            [
                                                'sortBy' => 'fullname',
                                                'order' => $sortBy == 'fullname' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'fullname' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'fullname' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>

                                    <th>
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('Designation'),
                                            [
                                                'sortBy' => 'designation',
                                                'order' => $sortBy == 'designation' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'designation' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'designation' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>
                                    <th>
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('Email'),
                                            [
                                                'sortBy' => 'email',
                                                'order' => $sortBy == 'email' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'email' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'email' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>
                                    <th>
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('Mobile Number'),
                                            [
                                                'sortBy' => 'mobile_number',
                                                'order' => $sortBy == 'mobile_number' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'mobile_number' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'mobile_number' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>

                                    {{-- <th>
                                    {{ link_to_route(
                                            "$modelName.index",
                                            trans('Registered On'),
                                            [
                                                'sortBy' => 'created_at',
                                                'order' => $sortBy == 'created_at' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'created_at' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'created_at' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                </th> --}}
                                    {{-- <th class="action-th">
                                    {{ link_to_route(
                                            "$modelName.index",
                                            trans('Certified Status'),
                                            [
                                                'sortBy' => 'is_certified',
                                                'order' => $sortBy == 'is_certified' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'is_certified' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'is_certified' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                </th> --}}
                                    <th class="action-th">
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('Status'),
                                            [
                                                'sortBy' => 'is_active',
                                                'order' => $sortBy == 'is_active' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'is_active' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'is_active' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>
                                    <th width="25%">{{ trans('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="powerwidgets">
                                <?php $number = 1; ?>
                                @if (!$results->isEmpty())
                                    <?php
                                    $page = $results->currentPage();
                                    $pagelimit = Config::get('Reading.records_per_page');
                                    $number = $page * $pagelimit - $pagelimit;
                                    $number++;
                                    ?>
                                    @foreach ($results as $result)
                                        <tr class="items-inner">
                                            <td><input type="checkbox" name="user_ids[]" value="{{ $result->id }}"></td>
                                            <td data-th='Name'>{{ $number++ }}</td>
                                            <td data-th='Name'>{{ $result->employee_id }}</td>
                                            <td data-th='Name'>{{ $result->fullname }}</td>
                                            <td data-th='Name'>{{ $result->designation }}</td>
                                            <td data-th='Email'>{{ $result->email }}</td>
                                            <td data-th='Mobile Number'>{{ $result->mobile_number }}</td>
                                            {{--
                                <td>{{ date(Config::get('Reading.date_format'), strtotime($result->created_at)) }}
                                </td> --}}
                                            {{-- <td data-th=''>
                                    @if ($result->is_certified == 1)
                                    <span class="btn btn-success">{{ trans('Certified') }}</span>
                                    @else
                                    <span class="btn btn-warning">{{ trans('Not Certified') }}</span>
                                    @endif
                                </td> --}}
                                            <td data-th=''>
                                                @if ($result->is_active == 1)
                                                    <span class="badge text-success">Active</span>
                                                @else
                                                    <span class="badge text-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td data-th='' class="action-td">
                                                @if ($result->is_active == 1)
                                                    <a title="Click To Deactivate"
                                                        href='{{ route("$modelName.status", [$result->id, 0]) }}'
                                                        data-confirm='Are you sure?'
                                                        class="btn btn-success btn-small status_any_item "><span
                                                            class="fa fa-ban"></span>
                                                    </a>
                                                @else
                                                    <a title="Click To Activate"
                                                        href='{{ route("$modelName.status", [$result->id, 1]) }}'
                                                        data-confirm='Are you sure?'
                                                        class="btn btn-warning btn-small status_any_item"><span
                                                            class="fa fa-check"></span>
                                                    </a>
                                                @endif
                                                <a href='{{ route("$modelName.edit", "$result->id") }}'
                                                    class="btn btn-primary" title="Edit"> <span class="fas fa-edit">
                                                    </span></a>
                                                <a href='{{ route("$modelName.view", "$result->id") }}'
                                                    class="btn btn-info" title="View"> <span class="fa fa-eye"></span></a>
                                                <a href='{{ route("$modelName.delete", "$result->id") }}'
                                                    data-delete="delete" class="delete_any_item btn btn-danger"
                                                    title="Delete" data-confirm='Are you sure?'>
                                                    <span class="fas fa-trash-alt   "></span>
                                                </a>
                                                @if (Auth::user()->user_role_id == SUPER_ADMIN_ROLE_ID)
                                                    <a onclick="ChangeDesignation({{ $result->id }})"
                                                        class="btn btn-success" title="Change Designation"> <span
                                                            class="fas fa-angle-double-up"></span></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="alignCenterClass"> {{ trans('Record not found.') }}
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="box-footer clearfix">
                            <!--  <div class="col-md-3 col-sm-4 "></div> -->
                            <div class="col-md-12 col-sm-12 text-right ">@include('pagination.default', ['paginator' => $results])
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
        // Download sample excel file Script
        function downloadUploadUserFile() {
            console.log('abc');
            var fileUrl = '{{ asset('sample-files/trainee-sample-file.xlsx') }}';
            var link = document.createElement('a');
            link.href = fileUrl;
            link.download = 'users-sample-file.xlsx';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
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

        function downloadFileChangeDesignation() {
            var fileUrl =
                '{{ asset('
                                                                                                                                                                                                                                                                                sample - files / change - designation - sample.xlsx ') }}';
            var link = document.createElement('a');
            link.href = fileUrl;
            link.download = 'change-designation-sample-file.xlsx';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>


    <div class="modal fade" id="ChangeDesignationModel" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Designation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {{ Form::open(['role' => 'form', 'url' => 'admin/users/change-designation', 'class' => 'mws-form', 'id' => 'ChangeDesignationForm', 'files' => true, 'autocomplete' => 'off']) }}

                <div class="modal-body">

                    <!-- Your form group with the multiselect dropdown -->
                    <div class="form-group">
                        {{ Form::hidden('user_id', '', ['class' => 'form-control', 'id' => 'user_id']) }}
                        <div class="mws-form-row">
                            {!! Html::decode(
                                Form::label('designation', trans('Select Designation') . '<span class="requireRed"></span>', [
                                    'class' => 'mws-form-label',
                                ]),
                            ) !!}
                            <div class="mws-form-item">
                                {{ Form::select('designation', $designation, '', ['class' => 'form-control', 'id' => 'designation']) }}
                                <div class="error-message help-inline">
                                    <?php echo $errors->first('designation'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary">Assign</button> -->
                    <input type="submit" value="{{ trans('Change') }}" class="btn btn-danger">
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

    <script>
        function ChangeDesignation(user_id) {
            $('#user_id').val(user_id);

            $("#ChangeDesignationModel").modal('show');
        }





        $("#checkAll").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $(document).ready(function() {
            var $submit = $("#trigger_event").hide(),
                $cbs = $('input[name="user_ids[]"]').click(function() {
                    $submit.toggle($cbs.is(":checked"));
                });

        });
        $(document).ready(function() {
            var $submit = $("#trigger_event").hide(),
                $cbs = $('.check_box_all').click(function() {
                    $submit.toggle($cbs.is(":checked"));
                });

        });

        $(document).ready(function() {
            $("#trigger_event").click(function() {
                var test = new Array();
                $("input[name='user_ids[]']:checked").each(function() {
                    test.push($(this).val());
                });
            });
        });



        $(document).on('click', '.trigger_event', function(e) {
            e.stopImmediatePropagation();
            url = $(this).attr('href');
            bootbox.confirm("Are you sure want to delete all these users ?",
                function(result) {
                    if (result) {
                        var test = new Array();
                        $("input[name='user_ids[]']:checked").each(function() {
                            test.push($(this).val());
                        });
                        $.ajax({
                            url: '{{ url('admin/users/delete-multiple') }}',
                            type: 'post',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "data": test,

                            },
                            success: function(r) {

                                error_array = JSON.stringify(r);
                                data = JSON.parse(error_array);
                                if (data['success'] == '1') {
                                    toastr.success('Invalid Request.');
                                } else {
                                    toastr.success('Users has been deleted successfully');
                                    window.setTimeout(function() {
                                        location.reload();
                                    }, 1000);
                                }
                            }
                        });
                    }
                });
            e.preventDefault();
        });
    </script>
    <style>
        .paddingtop {
            padding-top: 20px;
        }

        .select2-container--open {
            z-index: 1060;
            /* Adjust this value if needed */
        }
    </style>
@stop
