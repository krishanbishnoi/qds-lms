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
                    {!! Html::decode(Form::label('first_name', trans('First Name') . '<span class="requireRed">
                    </span>', ['class' => 'mws-form-label'])) !!}
                    {{ Form::text('first_name', isset($searchVariable['first_name']) ? $searchVariable['first_name'] : '', ['class' => ' form-control', 'placeholder' => 'First Name']) }}
                </div>
            </div>
            <div class="col-md-2 col-sm-2">
                <div class="form-group ">
                    {!! Html::decode(Form::label('email', trans('Email') . '<span class="requireRed"> </span>', ['class'
                    => 'mws-form-label'])) !!}
                    {{ Form::text('email', isset($searchVariable['email']) ? $searchVariable['email'] : '', ['class' => ' form-control', 'placeholder' => 'Email']) }}
                </div>
            </div>
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
        <div class="box-body">
            <div class="d-flex">
                <form action="{{ route('import.trainers') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-2 col-sm-2">
                        <div class="form-group ">
                            <input type="file" name="file" required>
                        </div>

                    </div>
                    <button class="btn btn-primary" type="submit">Import Users</button>
                </form>
            </div>
            <div class="col-md-4 col-sm-4 padding">

                    <a class="btn btn-primary" href="path_to_file" download="proposed_file_name">  Download sample file</a>

            </div>


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
                    <table class="table table-hover brdrclr" width="100%">
                        <thead class="theadLight">                            <tr>
                                <th width="5%">{{ trans('Sr.') }}</th>
                                <th>
                                    {{ link_to_route(
                                            "$modelName.index",
                                            trans('Employee ID'),
                                            [
                                                'sortBy' => 'employee_id',
                                                'order' => $sortBy == 'employee_id' && $order == 'desc' ? 'asc' : 'desc',
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

                                <th>
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
                                </th>
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
                                <th width="15%">{{ trans('Action') }}</th>
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
                                <td data-th='Name'>{{ $number++ }}</td>
                                <td data-th='Name'>{{ $result->employee_id }}</td>
                                <td data-th='Name'>{{ $result->fullname }}</td>
                                <td data-th='Email'>{{ $result->email }}</td>
                                <td data-th='Mobile Number'>{{ $result->mobile_number }}</td>

                                <td>{{ date(Config::get('Reading.date_format'), strtotime($result->created_at)) }}
                                </td>
                                <td data-th=''>
                                    @if ($result->is_active == 1)
                                    <span class="label label-success">{{ trans('Activated') }}</span>
                                    @else
                                    <span class="label label-warning">{{ trans('Deactivated') }}</span>
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
                                    <a href='{{ route("$modelName.edit", "$result->id") }}' class="btn btn-primary"
                                        title="Edit"> <span class="fas fa-edit">
                                        </span></a>
                                    <a href='{{ route("$modelName.view", "$result->id") }}' class="btn btn-info"
                                        title="View"> <span class="fa fa-eye"></span></a>
                                    <a href='{{ route("$modelName.delete", "$result->id") }}' data-delete="delete"
                                        class="delete_any_item btn btn-danger" title="Delete"
                                        data-confirm='Are you sure?'>
                                        <span class="fas fa-trash-alt   "></span>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="8" class="alignCenterClass"> {{ trans('Record not found.') }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        <!--  <div class="col-md-3 col-sm-4 "></div> -->
                        <div class="col-md-12 col-sm-12 text-right ">@include(
                            'pagination.default',
                            ['paginator' => $results]
                            )
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
