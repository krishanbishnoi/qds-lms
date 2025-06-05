@extends('admin.layouts.default')
@section('content')
    <script>
        $(function() {
            $('#start_from').datetimepicker({
                format: 'YYYY-MM-DD',
            });
            $('#start_to').datetimepicker({
                format: 'YYYY-MM-DD',
                useCurrent: false
            });
            $("#start_from").on("dp.change", function(e) {
                $('#start_to').data("DateTimePicker").minDate(e.date);
            });
            $("#start_to").on("dp.change", function(e) {
                $('#start_from').data("DateTimePicker").maxDate(e.date);
            });
        });

        $(function() {
            $('#publish_date_from').datetimepicker({
                format: 'YYYY-MM-DD',
            });
            $('#publish_date_to').datetimepicker({
                format: 'YYYY-MM-DD',
                useCurrent: false
            });
            $("#publish_date_from").on("dp.change", function(e) {
                $('#publish_date_to').data("DateTimePicker").minDate(e.date);
            });
            $("#publish_date_to").on("dp.change", function(e) {
                $('#publish_date_from').data("DateTimePicker").maxDate(e.date);
            });
        });
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
            <div class="box-body ">
                {{ Form::open(['method' => 'get', 'role' => 'form', 'route' => "$modelName.index", 'class' => 'row mws-form']) }}
                {{ Form::hidden('display') }}
                <div class="col-md-2 col-sm-2">

                    <div class="form-group ">
                        {!! Html::decode(
                            Form::label('Select Status', trans('Select Status') . '<span class="requireRed"> </span>', [
                                'class' => 'mws-form-label',
                            ]),
                        ) !!}
                        {{ Form::select('status', ['' => trans('All'), 1 => trans('Waiting'), 2 => trans('Publish'), 3 => trans('Active'), 4 => trans('Ended'), 5 => trans('Cancelled'), 6 => trans('Payment Done')], isset($searchVariable['status']) ? $searchVariable['status'] : '', ['class' => 'form-control']) }}
                    </div>
                </div>
                {{-- <div class="col-md-2 col-sm-2">
                    <div class="form-group ">
                        {!! Html::decode(Form::label('admin_cut', trans('Admin Cut Range') . '<span class="requireRed"> </span>', ['class' => 'mws-form-label'])) !!}
                        {{ Form::text('admin_cut', isset($searchVariable['admin_cut']) ? $searchVariable['admin_cut'] : '', ['class' => 'form-control','id' => 'admin_cut','placeholder' => trans('Admin Cut Range')]) }}
                    </div>
                </div> --}}
                <div class="col-md-2 col-sm-2">
                    <div class="form-group ">
                        {!! Html::decode(
                            Form::label('name', trans('Name') . '<span class="requireRed"> </span>', ['class' => 'mws-form-label']),
                        ) !!}
                        {{ Form::text('name', isset($searchVariable['name']) ? $searchVariable['name'] : '', ['class' => 'form-control', 'id' => 'name', 'placeholder' => trans('Name')]) }}
                    </div>
                </div>
                <div class="col-md-2 col-sm-2">
                    <div class="form-group ">
                        {!! Html::decode(
                            Form::label('entry_fee', trans('Entry Fee') . '<span class="requireRed"> </span>', ['class' => 'mws-form-label']),
                        ) !!}
                        {{ Form::text('entry_fee', isset($searchVariable['entry_fee']) ? $searchVariable['entry_fee'] : '', ['class' => 'form-control', 'id' => 'entry_fee', 'placeholder' => trans('Entry Fee')]) }}
                    </div>
                </div>
                <div class="col-md-2 col-sm-2">
                    <div class="form-group ">
                        {!! Html::decode(
                            Form::label('date_from', trans('Live Date From') . '<span class="requireRed"> </span>', [
                                'class' => 'mws-form-label',
                            ]),
                        ) !!}
                        {{ Form::text('date_from', isset($searchVariable['date_from']) ? $searchVariable['date_from'] : '', ['class' => 'form-control', 'id' => 'start_from', 'placeholder' => trans('Date From')]) }}
                    </div>
                </div>
                <div class="col-md-2 col-sm-2">
                    <div class="form-group ">
                        {!! Html::decode(
                            Form::label('date_to', trans('Live Date To') . '<span class="requireRed"> </span>', ['class' => 'mws-form-label']),
                        ) !!}
                        {{ Form::text('date_to', isset($searchVariable['date_to']) ? $searchVariable['date_to'] : '', ['class' => 'form-control', 'id' => 'start_to', 'placeholder' => trans('Date To')]) }}
                    </div>
                </div>
                <div class="col-md-2 col-sm-2">
                    <div class="form-group ">
                        {!! Html::decode(
                            Form::label('publish_date_from', trans('Publish Date From') . '<span class="requireRed"> </span>', [
                                'class' => 'mws-form-label',
                            ]),
                        ) !!}
                        {{ Form::text('publish_date_from', isset($searchVariable['publish_date_from']) ? $searchVariable['publish_date_from'] : '', ['class' => 'form-control', 'id' => 'publish_date_from', 'placeholder' => trans('Publish Date From')]) }}
                    </div>
                </div>
                <div class="col-md-2 col-sm-2">
                    <div class="form-group ">
                        {!! Html::decode(
                            Form::label('publish_date_to', trans('Publish Date To') . '<span class="requireRed"> </span>', [
                                'class' => 'mws-form-label',
                            ]),
                        ) !!}
                        {{ Form::text('publish_date_to', isset($searchVariable['publish_date_to']) ? $searchVariable['publish_date_to'] : '', ['class' => 'form-control', 'id' => 'publish_date_to', 'placeholder' => trans('Publish Date To')]) }}
                    </div>
                </div>


                <div class="col-md-3 col-sm-3 paddingtop">

                    <button class="btn btn-primary mr-2 px-4"><i class='fa fa-search '></i> Search</button>
                    <a href='{{ route("$modelName.index") }}' class="btn btn-primary"> <i class="fa fa-refresh "></i>
                        {{ trans('Clear Search') }}</a>
                </div>
                {{ Form::close() }}
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="box-header with-border pd-custom">
                            <div class="listing-btns">
                                <h1 class="box-title">{{ $sectionName }}'s List</h1>
                                <a href='{{ route("$modelName.add") }}' class="btn btn-success btn-small pull-right mb-2">
                                    {{ trans('Add New ') }}{{ $sectionNameSingular }} </a>
                            </div>
                        </div>
                        <table class="table table-hover table table-bordered mt-2 ">
                            <thead class="theadLight">

                                <tr>
                                    <th>{{ trans('SN.') }}</th>
                                    <th>
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('Contest Id'),
                                            [
                                                'sortBy' => 'id',
                                                'order' => $sortBy == 'id' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'id' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'id' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>
                                    <th>
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('Name'),
                                            [
                                                'sortBy' => 'name',
                                                'order' => $sortBy == 'name' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'name' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'name' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>
                                    <th>
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('Category'),
                                            [
                                                'sortBy' => 'id',
                                                'order' => $sortBy == 'id' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'id' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'id' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>

                                    <th>
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('Entry Fee'),
                                            [
                                                'sortBy' => 'entry_fee',
                                                'order' => $sortBy == 'entry_fee' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'entry_fee' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'entry_fee' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>

                                    <th>
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('Publish Date'),
                                            [
                                                'sortBy' => 'publish_date_time',
                                                'order' => $sortBy == 'publish_date_time' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'publish_date_time' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'publish_date_time' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>
                                    <th>
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('Registration Start Date'),
                                            [
                                                'sortBy' => 'registration_start_date_time',
                                                'order' => $sortBy == 'registration_start_date_time' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'registration_start_date_time' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'registration_start_date_time' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>
                                    <th>
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('Registration Close Date'),
                                            [
                                                'sortBy' => 'registration_close_date_time',
                                                'order' => $sortBy == 'registration_close_date_time' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'registration_close_date_time' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'registration_close_date_time' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>
                                    <th class="action-th">
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('Status'),
                                            [
                                                'sortBy' => 'status',
                                                'order' => $sortBy == 'status' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'status' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'status' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>
                                    <th>{{ trans('Change Status') }}</th>
                                    <th>{{ trans('Action') }}</th>
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
                                    <?php //echo "<pre>"; print_r($results);die();
                                    ?>
                                    @foreach ($results as $result)
                                        <tr class="items-inner">
                                            <td data-th='Name'>{{ $number++ }}</td>
                                            <td data-th='Name'>{{ $result->id }}</td>
                                            <td data-th='Name'>{{ $result->name }}</td>
                                            <td data-th='Name'>{{ $result->category_id }}</td>
                                            <td data-th='Name'>{{ $result->entry_fee }}</td>


                                            <td data-th='Name'>
                                                {{ date(Config::get('Reading.date_format'), strtotime($result->publish_date_time)) }}
                                            </td>
                                            <td data-th='Name'>
                                                {{ date(Config::get('Reading.date_format'), strtotime($result->registration_start_date_time)) }}
                                            </td>
                                            @if (!empty($result->registration_close_date_time))
                                                <td data-th='Name'>
                                                    {{ date(Config::get('Reading.date_format'), strtotime($result->registration_close_date_time)) }}
                                                </td>
                                            @else
                                                <td data-th='Name'>
                                                    {{ 'N/A' }}
                                                </td>
                                            @endif


                                            <td style="font-weight: bolder;color:black;">

                                                {{ streek_status[$result->status] }}

                                            </td>
                                            <td data-th='' class="action-td">
                                                @if ($result->status == 1)
                                                    <a title="Click To Publish"
                                                        href='{{ route('Contests.status', [$result->id, 2]) }}'
                                                        data-confirm='Are you sure?'
                                                        class="btn btn-warning btn-small status_publish">
                                                        <span class="fa fa-plus"></span>
                                                    </a>
                                                    <a disabled data-confirm='Are you sure?'
                                                        class="btn btn-success btn-small"><span class="fa fa-ban"></span>
                                                    </a>
                                                    <a disabled data-confirm='Are you sure?'
                                                        class="btn btn-danger btn-small"><span class="fa fa-ban"></span>
                                                    </a>
                                                @elseif ($result->status == 2)
                                                    <a disabled data-confirm='Are you sure?'
                                                        class="btn btn-warning btn-small"><span class="fa fa-ban"></span>
                                                    </a>
                                                    <a title="Click To Live"
                                                        href='{{ route('Contests.status', [$result->id, 3]) }}'
                                                        data-confirm='Are you sure?'
                                                        class="btn btn-success btn-small status_active"><span
                                                            class="fa fa-check"></span>
                                                    </a>
                                                    <a title="Click To Cancel"
                                                        href='{{ route('Contests.status', [$result->id, 5]) }}'
                                                        data-confirm='Are you sure?'
                                                        class="btn btn-danger btn-small status_cancel"><span
                                                            class="fa fa-times"></span>
                                                    </a>
                                                @elseif ($result->status == 3)
                                                    <a disabled data-confirm='Are you sure?'
                                                        class="btn btn-warning btn-small"><span class="fa fa-ban"></span>
                                                    </a>
                                                    <a disabled data-confirm='Are you sure?'
                                                        class="btn btn-success btn-small"><span class="fa fa-ban"></span>
                                                    </a>
                                                    <a title="Click To Cancel"
                                                        href='{{ route('Contests.status', [$result->id, 5]) }}'
                                                        data-confirm='Are you sure?'
                                                        class="btn btn-danger btn-small status_cancel"><span
                                                            class="fa fa-times"></span>
                                                    </a>
                                                @elseif ($result->status == 4)
                                                    <a disabled data-confirm='Are you sure?'
                                                        class="btn btn-warning btn-small"><span class="fa fa-ban"></span>
                                                    </a>
                                                    <a disabled data-confirm='Are you sure?'
                                                        class="btn btn-success btn-small"><span class="fa fa-ban"></span>
                                                    </a>
                                                    <a disabled data-confirm='Are you sure?'
                                                        class="btn btn-danger btn-small"><span class="fa fa-ban"></span>
                                                    </a>
                                                @else
                                                    <a disabled data-confirm='Are you sure?'
                                                        class="btn btn-warning btn-small"><span class="fa fa-ban"></span>
                                                    </a>
                                                    <a disabled data-confirm='Are you sure?'
                                                        class="btn btn-success btn-small"><span class="fa fa-ban"></span>
                                                    </a>
                                                    <a disabled data-confirm='Are you sure?'
                                                        class="btn btn-danger btn-small"><span class="fa fa-ban"></span>
                                                    </a>
                                                @endif

                                            </td>

                                            <td data-th='' class="action-td">
                                                @if ($result->status < 4)
                                                    <a href='{{ route("$modelName.edit", "$result->id") }}'
                                                        class="btn btn-primary" title="Edit"> <span
                                                            class="fas fa-edit">
                                                        </span></a>
                                                @endif
                                                <a href='{{ route("$modelName.view", "$result->id") }}'
                                                    class="btn btn-info" title="View"> <span
                                                        class="fa fa-eye"></span></a>
                                                @if ($result->status < 2)
                                                    <a href='{{ route("$modelName.delete", "$result->id") }}'
                                                        data-delete="delete" class="delete_any_item btn btn-danger"
                                                        title="Delete" data-confirm='Are you sure?'>
                                                        <span class="fas fa-trash-alt   "></span>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="12" class="alignCenterClass"> {{ trans('Record not found.') }}
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="box-footer clearfix">
                            <!-- <div class="col-md-3 col-sm-4 "></div> -->
                            <div class="col-md-12 col-sm-12 text-right ">@include('pagination.default', ['paginator' => $results])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .paddingtop {
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
            $(document).on('click', '.status_publish', function(e) {
                e.stopImmediatePropagation();
                url = $(this).attr('href');
                bootbox.confirm("Do you really want to publish the Contest?",
                    function(result) {
                        if (result) {
                            window.location.replace(url);

                        }
                    });
                e.preventDefault();
            });
            $(document).on('click', '.status_active', function(e) {
                e.stopImmediatePropagation();
                url = $(this).attr('href');
                bootbox.confirm(
                    "Do you want to change the Active Date to now and make this will Contest Active Today?",
                    function(result) {
                        if (result) {
                            window.location.replace(url);
                        }
                    });
                e.preventDefault();
            });
            $(document).on('click', '.status_cancel', function(e) {
                e.stopImmediatePropagation();

                // return false;
                url = $(this).attr('href');
                bootbox.confirm("Do you really want to Cancel the Contest?",
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
