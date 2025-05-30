@extends('admin.layouts.default')
@section('content')


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
        <div class="box search-panel collapsed-box">

            <div class="box-body">
                {{ Form::open(['method' => 'get', 'role' => 'form', 'route' => "$modelName.index", 'class' => 'row mws-form']) }}
                {{ Form::hidden('display') }}
                <div class="col-md-2 col-sm-2">
                    <div class="form-group ">
                        {!! Html::decode(
                            Form::label('designation', trans('Designation') . '<span class="requireRed"> </span>', [
                                'class' => 'mws-form-label',
                            ]),
                        ) !!}
                        {{ Form::text('designation', isset($searchVariable['designation']) ? $searchVariable['designation'] : '', ['class' => 'form-control', 'placeholder' => 'Designation']) }}
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 paddingtop">
                    <div class="d-flex">
                        <button class="btn btn-primary mr-2 px-4"><i class='fa fa-search '></i> Search</button>
                        <a href='{{ route("$modelName.index") }}' class="btn btn-primary"> <i class="fa fa-refresh "></i>
                            {{ trans('Clear Search') }}</a>
                    </div>
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
                        <table class="table table-hover brdrclr mt-2">
                            <thead class="theadLight">
                                <tr>
                                    <th width="25%">
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
                                    <th width="25%"> Status</th>

                                    <th width="40%">
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('Modified'),
                                            [
                                                'sortBy' => 'updated_at',
                                                'order' => $sortBy == 'updated_at' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'updated_at' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'updated_at' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>
                                    <th>{{ trans('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="powerwidgets">
                                @if (!$results->isEmpty())
                                    @foreach ($results as $record)
                                        <tr class="items-inner">
                                            <td data-th='{{ trans('Page Name') }}'>{{ $record->designation }}</td>
                                            <td> <span
                                                    class="badge {{ $record->is_active ? 'text-success' : 'text-danger' }}">
                                                    {{ config('constants.STATUS_LIST')[$record->is_active] ?? 'Unknown Status' }}
                                                </span></td>
                                            <td data-th='{{ trans('Modified') }}'>
                                                {{ date(Config::get('Reading.date_format'), strtotime($record->updated_at)) }}
                                            </td>
                                            <td data-th='' class="action-td">

                                                <a href='{{ route("$modelName.edit", "$record->id") }}'
                                                    class="btn btn-primary" title="Edit"> <span
                                                        class="fas fa-edit"></span></a>

                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="alignCenterClass" colspan="4">{{ trans('No Record Found') }}</td>
                                    </tr>
                                @endif
                            </tbody>


                        </table>
                        <div class="box-footer clearfix">
                            <!-- <div class="col-md-3 col-sm-4 "></div> -->
                            <div class="col-md-12 col-sm-12 text-right ">@include('pagination.default', ['paginator' => $results])</div>
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
@stop
