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
                            Form::label('report', trans('Report Name') . '<span class="requireRed"> </span>', [
                                'class' => 'mws-form-label',
                            ]),
                        ) !!}

                        {{ Form::text('report_name', isset($searchVariable['report_name']) ? $searchVariable['report_name'] : '', ['class' => 'form-control', 'placeholder' => 'Search']) }}

                    </div>

                </div>



                <div class="col-md-4 col-sm-4 paddingtop">

                    <div class="d-flex">

                        <button class="btn btn-primary mr-2"><i class='fa fa-search '></i> Search</button>

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

                            <div class="listing-btns d-flex justify-content-between mb-3">

                                <h1 class="box-title">{{ $sectionName }} List</h1>
                                @if (Auth::user()->user_role_id == 4)
                                    <a href='{{ route("$modelName.add") }}' class="btn btn-success btn-small pull-right">
                                        {{ trans('Add New ') }}{{ $sectionNameSingular }} </a>
                                @endif
                            </div>

                        </div>

                        <div class="w-100 ctsmTableDesign">
                            <table class="table table-hover" width="100%">

                                <thead>

                                    <tr>

                                        <th width="12%">

                                            {{ link_to_route(
                                                "$modelName.index",
                                            
                                                trans('Report name'),
                                            
                                                [
                                                    'sortBy' => 'report_name',
                                            
                                                    'order' => $sortBy == 'report_name' && $order == 'desc' ? 'asc' : 'desc',
                                            
                                                    $query_string,
                                                ],
                                            
                                                [
                                                    'class' =>
                                                        $sortBy == 'report_name' && $order == 'desc'
                                                            ? 'sorting desc'
                                                            : ($sortBy == 'report_name' && $order == 'asc'
                                                                ? 'sorting asc'
                                                                : 'sorting'),
                                                ],
                                            ) }}

                                        </th>


                                        <th width="12%">

                                            {{ link_to_route(
                                                "$modelName.index",
                                            
                                                trans('Created By'),
                                            
                                                [
                                                    'sortBy' => 'created_by',
                                            
                                                    'order' => $sortBy == 'created_by' && $order == 'desc' ? 'asc' : 'desc',
                                            
                                                    $query_string,
                                                ],
                                            
                                                [
                                                    'class' =>
                                                        $sortBy == 'created_by' && $order == 'desc'
                                                            ? 'sorting desc'
                                                            : ($sortBy == 'created_by' && $order == 'asc'
                                                                ? 'sorting asc'
                                                                : 'sorting'),
                                                ],
                                            ) }}

                                        </th>

                                        <!-- <th width="12%">

                                                                    {{ link_to_route(
                                                                        "$modelName.index",
                                                                    
                                                                        trans('Training Type'),
                                                                    
                                                                        [
                                                                            'sortBy' => 'type',
                                                                    
                                                                            'order' => $sortBy == 'type' && $order == 'desc' ? 'asc' : 'desc',
                                                                    
                                                                            $query_string,
                                                                        ],
                                                                    
                                                                        [
                                                                            'class' =>
                                                                                $sortBy == 'type' && $order == 'desc'
                                                                                    ? 'sorting desc'
                                                                                    : ($sortBy == 'type' && $order == 'asc'
                                                                                        ? 'sorting asc'
                                                                                        : 'sorting'),
                                                                        ],
                                                                    ) }}

                                                                </th> -->

                                        <!-- <th width="12%">

                                                                    {{ link_to_route(
                                                                        "$modelName.index",
                                                                    
                                                                        trans('Minimum Marks'),
                                                                    
                                                                        [
                                                                            'sortBy' => 'minimum_marks',
                                                                    
                                                                            'order' => $sortBy == 'minimum_marks' && $order == 'desc' ? 'asc' : 'desc',
                                                                    
                                                                            $query_string,
                                                                        ],
                                                                    
                                                                        [
                                                                            'class' =>
                                                                                $sortBy == 'minimum_marks' && $order == 'desc'
                                                                                    ? 'sorting desc'
                                                                                    : ($sortBy == 'minimum_marks' && $order == 'asc'
                                                                                        ? 'sorting asc'
                                                                                        : 'sorting'),
                                                                        ],
                                                                    ) }}

                                                                </th> -->

                                        <th width="15%">

                                            {{ link_to_route(
                                                "$modelName.index",
                                            
                                                trans('Report Date'),
                                            
                                                [
                                                    'sortBy' => 'report_from',
                                            
                                                    'order' => $sortBy == 'report_from' && $order == 'desc' ? 'asc' : 'desc',
                                            
                                                    $query_string,
                                                ],
                                            
                                                [
                                                    'class' =>
                                                        $sortBy == 'report_from' && $order == 'desc'
                                                            ? 'sorting desc'
                                                            : ($sortBy == 'report_from' && $order == 'asc'
                                                                ? 'sorting asc'
                                                                : 'sorting'),
                                                ],
                                            ) }}

                                        </th>

                                        {{--  <th width="15%">

                                        {{ link_to_route(
                                            "$modelName.index",

                                            trans('Report to'),

                                            [
                                                'sortBy' => 'report_to',

                                                'order' => $sortBy == 'report_to' && $order == 'desc' ? 'asc' : 'desc',

                                                $query_string,
                                            ],

                                            [
                                                'class' =>
                                                    $sortBy == 'report_to' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'report_to' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}

                                    </th>  --}}





                                        <th width="28%">{{ trans('Action') }}</th>

                                    </tr>

                                </thead>

                                <tbody id="powerwidgets">

                                    @if (!$results->isEmpty())

                                        @foreach ($results as $record)
                                            <tr class="items-inner">

                                                <td data-th="{{ trans('Page Name') }}">{{ $record->report_name }}</td>

                                                <td data-th="{{ trans('Page Name') }}">{{ $record->created_by }}</td>

                                                <!-- <td data-th="{{ trans('Page Name') }}">{{ $record->type }}</td> -->



                                                <!-- <td data-th="{{ trans('Page Name') }}">{{ $record->minimum_marks }}</td> -->

                                                <td data-th="{{ trans('Page Name') }}">{{ $record->report_from }}</td>

                                                {{--  <td data-th="{{ trans('Page Name') }}">{{ $record->report_to }}</td>  --}}


                                                <td data-th='' class="action-td">

                                                    <!-- @if ($record->is_active == 1)
    <a  title="Click To Deactivate" href='{{ route("$modelName.status", [$record->id, 0]) }}' class="btn btn-success btn-small status_any_item "><span class="fa fa-ban"></span>

                                         </a>
@else
    <a title="Click To Activate" href='{{ route("$modelName.status", [$record->id, 1]) }}' class="btn btn-warning btn-small status_any_item"><span class="fa fa-check"></span>

                                         </a>
    @endif  -->
                                                    @if (Auth::user()->user_role_id == 4)
                                                        <a href='{{ route("$modelName.edit", "$record->id") }}'
                                                            class="btn btn-primary" title="Edit"> <span
                                                                class="fas fa-edit"></span></a>
                                                    @endif

                                                    <a href='{{ route("$modelName.view", "$record->id") }}'
                                                        class="btn btn-warning" title="View"> <span
                                                            class="fas fa-eye"></span></a>



                                                    <form action="{{ route("$modelName.delete", $record->id) }}"
                                                        method="POST" class="delete_any_item" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger rounded-circle"
                                                            title="Delete" data-confirm="Are you sure?">
                                                            <span class="fas fa-trash-alt"></span>
                                                        </button>
                                                    </form>

                                                    </a>


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

                        </div>
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

            padding-top: 22px;
        }
    </style>

@stop
