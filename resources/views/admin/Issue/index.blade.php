@extends('admin.layouts.default')
@section('content')


    <section class="content-header">
        <h1>
            {{ trans('Issues') }}
        </h1>
        <br>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li class="active"> {{ trans('Issues') }}</li>
        </ol>

    </section>

    <section class="content">
        {{ Form::open(['method' => 'get', 'role' => 'form', 'route' => "$modelName.index", 'class' => 'mws-form', 'id' => 'index_search']) }}
        {{ Form::hidden('display') }}
        <div class="row">
            <div class="col-md-2 col-sm-2">
                <div class="form-group ">
                    {!! Html::decode(
                        Form::label('Select Status', trans('Select Status') . '<span class="requireRed"> </span>', [
                            'class' => 'mws-form-label',
                        ]),
                    ) !!}
                    {{ Form::select('status', ['' => trans('All'), 1 => trans('Resolved'), 0 => trans('Pending')], isset($searchVariable['status']) ? $searchVariable['status'] : '', ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-2 col-sm-2">
                <div class="form-group ">
                    {!! Html::decode(
                        Form::label('name', trans('Name') . '<span class="requireRed"> </span>', ['class' => 'mws-form-label']),
                    ) !!}
                    {{ Form::text('name', isset($searchVariable['name']) ? $searchVariable['name'] : '', ['class' => 'form-control small', 'placeholder' => 'Name']) }}
                </div>
            </div>
            <div class="col-md-2 col-sm-2">
                <div class="form-group ">
                    {!! Html::decode(
                        Form::label('subject', trans('Subject') . '<span class="requireRed"> </span>', ['class' => 'mws-form-label']),
                    ) !!}
                    {{ Form::text('subject', isset($searchVariable['subject']) ? $searchVariable['subject'] : '', ['class' => 'form-control small', 'placeholder' => 'Subject']) }}
                </div>
            </div>
            <div class="col-md-2 col-sm-2">
                <div class="form-group ">
                    {!! Html::decode(
                        Form::label('issue', trans('Issue') . '<span class="requireRed"> </span>', ['class' => 'mws-form-label']),
                    ) !!}
                    {{ Form::text('issue', isset($searchVariable['issue']) ? $searchVariable['issue'] : '', ['class' => 'form-control small', 'placeholder' => 'Issue']) }}
                </div>
            </div>
            <div class="col-md-3 col-sm-3 paddingtop">
                <button class="btn btn-primary"><i class='fa fa-search '></i> Search</button>
                <a href='{{ route("$modelName.index") }}' class="btn btn-primary btn-small"><i class="fa fa-refresh"></i>
                    {{ trans('Clear Search') }}</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2 col-sm-2 pull-right">
                <div class="form-group">

                </div>
            </div>
        </div>
        {{ Form::close() }}
        <div class="box">
            <div class="box-body">
                <table class="table table-hover brdrclr mt-2">
                    <thead class="theadLight">
                        <tr>
                            <th width="5%">{{ trans('Sr.') }}</th>
                            <th width="10%">
                                {!! link_to_route(
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
                                ) !!}
                            </th>
                            <th width="15%">
                                {!! link_to_route(
                                    "$modelName.index",
                                    trans('Email'),
                                    [
                                        'sortBy' => 'email',
                                        'order' => $sortBy == 'email' && $order == 'desc' ? 'asc' : 'desc',
                                    ],
                                    [
                                        'class' =>
                                            $sortBy == 'email' && $order == 'desc'
                                                ? 'sorting desc'
                                                : ($sortBy == 'email' && $order == 'asc'
                                                    ? 'sorting asc'
                                                    : 'sorting'),
                                    ],
                                ) !!}
                            </th>
                            <th width="15%">{{ trans('Subject') }}</th>
                            <th width="20%">{{ trans('Issue') }}</th>
                            <th width="10%">{{ trans('Status') }}</th>
                            <th width="10%">{{ trans('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $number = 1; ?>
                        @if (!$model->isEmpty())
                            <?php
                            $page = $model->currentPage();
                            $pagelimit = Config::get('Reading.records_per_page');
                            $number = $page * $pagelimit - $pagelimit;
                            $number++;
                            ?>
                            @foreach ($model as $result)
                                <tr class="items-inner">
                                    <td data-th='Name'>{{ $number++ }}</td>
                                    <td data-th='{{ trans('Name') }}'>{{ $result->name }}</td>
                                    <td data-th='{{ trans('Email') }}'>{{ $result->email }}</td>

                                    <!-- <td data-th='{{ trans('Email') }}'><a href="mailTo: {{ $result->email }} "> {{ $result->email }} </a></td> -->
                                    <td data-th='{{ trans('Subject') }}'>{{ $result->subject }} </td>
                                    <!-- <td data-th='{{ trans('Name') }}'>{{ $result->subject }}</td> -->
                                    <td data-th='{{ trans('Issue') }}'>
                                        {{ strip_tags(wordwrap(Str::limit($result->issue, 150), 25, "<br>\n")) }}</td>
                                    <td data-th=''>
                                        @if ($result->status == 1)
                                            <span class="label label-success">{{ trans('Resolved') }}</span>
                                        @else
                                            <span class="label label-warning">{{ trans('Pending') }}</span>
                                        @endif
                                    </td>
                                    <td data-th='{{ trans('Action') }}'>
                                        @if ($result->status == 0)
                                            <a title="Click To Resolve" onclick="resolveIssue({{ $result->id }});"
                                                data-confirm = 'Are you sure?' class="btn btn-success btn-small  "><span
                                                    class="fa fa-check"></span>
                                            </a>
                                        @endif
                                        <a href='{{ route("$modelName.view", "$result->id") }}' class="btn btn-info"
                                            title="View"> <i class="fa fa-eye"></i> </a>
                                        <!-- <a href='{{ route("$modelName.view", "$result->id") }}#reply' data-delete="delete" class="btn btn-success " title="Reply"> <i class="fa fa-share"></i> </a> -->
                                        <a href='{{ route("$modelName.delete", "$result->id") }}' data-delete="delete"
                                            class="delete_any_item btn btn-danger" title="Delete"
                                            data-confirm = 'Are you sure?'>
                                            <span class="fas fa-trash-alt   "></span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="12" class="alignCenterClass"> {{ trans('Record not found.') }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="box-footer clearfix">
                <div class="col-md-3 col-sm-4 "></div>
                <div class="col-md-9 col-sm-8 text-right ">@include('pagination.default', ['paginator' => $model])</div>
            </div>
        </div>
    </section>
    <style>
        .paddingtop {
            padding-top: 22px;
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

        function resolveIssue($issue_id) {
            //$('#myModal').trigger("reset");

            $('#myModal').modal('show');
            $("#add_category_form input[name=issue_id]").val($issue_id);
            //$('#myModal').modal('hide');
        }
    </script>


    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        {{ Form::open(['role' => 'form', 'url' => 'issue-status/resolve-issue', 'class' => 'mws-form', 'files' => 'true', 'id' => 'add_category_form']) }}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Comment Modal</h4>
                </div>
                <div class="modal-body">
                    <div class="  form-group">
                        <div class="mws-form-row">
                            {!! HTML::decode(
                                Form::label('comment', trans('Comment') . '<span class="requireRed">  </span>', ['class' => 'mws-form-label']),
                            ) !!}
                            <div class="mws-form-item">
                                {{ Form::hidden('issue_id', '', ['class' => 'form-control']) }}
                                {{ Form::textarea('comment', '', ['class' => 'form-control comment', 'required' => 'required']) }}
                                <div class="error-message help-inline">
                                    <?php echo $errors->first('comment'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>

@stop
