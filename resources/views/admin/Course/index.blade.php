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
                {{ Form::open(['method' => 'get', 'role' => 'form', 'url' => route("$modelName.index", $training_id), 'class' => 'row mws-form']) }}
                {{ Form::hidden('display') }}

                <div class="col-md-2 col-sm-2">
                    <div class="form-group ">
                        {{ Form::text('title', isset($searchVariable['title']) ? $searchVariable['title'] : '', ['class' => ' form-control', 'placeholder' => 'Title']) }}
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 ">

                    <div class="d-flex">
                        <button class="btn btn-primary mr-2 px-4"><i class='fa fa-search '></i> Search</button>
                        <a href='{{ route("$modelName.index", $training_id) }}' class="btn btn-primary"> <i
                                class="fa fa-refresh "></i> {{ trans('Clear Search') }}</a>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
        <div class="row">
            <div class="box-body">
                <div class="d-flex">

                </div>
            </div>
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="box-header with-border pd-custom">
                            <div class="listing-btns">
                                <h1 class="box-title">{{ $sectionName }}'s List</h1>

                                <a href='{{ route("$modelName.add", $training_id) }}'
                                    class="btn btn-success btn-small pull-right mb-2">
                                    {{ trans('Add New ') }}{{ $sectionNameSingular }} </a>
                            </div>
                        </div>
                            <table class="table table-hover brdrclr mt-2">
                            <thead class="theadLight">

                                <tr>

                                    <th width="15%">
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('Title'),
                                            [
                                                'sortBy' => 'title',
                                                'order' => $sortBy == 'question' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'question' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'question' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>

                                    <th width="10%">
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('Start Date'),
                                            [
                                                'sortBy' => 'start_date_time',
                                                'order' => $sortBy == 'start_date_time' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'start_date_time' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'start_date_time' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>
                                    <th width="10%">
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('End Date'),
                                            [
                                                'sortBy' => 'end_date_time',
                                                'order' => $sortBy == 'end_date_time' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'end_date_time' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'end_date_time' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>
                                    <th width="10%">
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
                                    <th width="10%">{{ trans('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="powerwidgets">
                                @if (!$results->isEmpty())
                                    @foreach ($results as $result)
                                        <tr class="items-inner">
                                            <td data-th='question'>{{ $result->title }}</td>
                                            <td data-th='question'>{{ $result->start_date_time }}</td>
                                            <td data-th='question'>{{ $result->end_date_time }}</td>
                                            <td data-th="{{ trans('Modified') }}">
                                                {{ date(Config::get('Reading.date_format'), strtotime($result->updated_at)) }}
                                            </td>
                                            <!-- <td  data-th=''>
              @if ($result->is_active == 1)
    <span class="label label-success" >{{ trans('Activated') }}</span>
@else
    <span class="label label-warning" >{{ trans('Deactivated') }}</span>
    @endif
             </td>								 -->
                                            <td data-th='' class="action-td">
                                                <!-- @if ($result->is_active == 1)
    <a  title="Click To Deactivate" href='{{ route("$modelName.status", [$result->id, 0]) }}' class="btn btn-success btn-small status_any_item"><span class="fas fa-ban"></span>
               </a>
@else
    <a title="Click To Activate" href='{{ route("$modelName.status", [$result->id, 1]) }}' class="btn btn-warning btn-small status_any_item"><span class="fas fa-check"></span>
               </a>
    @endif  -->
                                                <a href='{{ route("$modelName.edit", [$training_id, $result->id]) }}'
                                                    class="btn btn-primary" title="Edit"> <span
                                                        class="fas fa-edit"></span></a>
                                                <a href='{{ route("$modelName.delete", "$result->id") }}'
                                                    data-delete="delete" class="delete_any_item btn btn-danger"
                                                    title="Delete">
                                                    <span class="fas fa-trash-alt"></span></a>

                                                <a href='{{ route("$modelName.view", [$training_id, $result->id]) }}'
                                                    class="btn btn-success" title="View Training Courses"> <span
                                                        class="fas fa-eye"></span></a>
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
