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
                {{ Form::open(['method' => 'get', 'role' => 'form', 'url' => route("$modelName.index", $test_id), 'class' => 'row mws-form']) }}
                {{ Form::hidden('display') }}
                <!-- <div class="col-md-2 col-sm-2">
                                                                                                <div class="form-group ">
                                                                                                    {{ Form::select('is_active', ['' => trans('All'), 1 => trans('Active'), 0 => trans('Inactive')], isset($searchVariable['is_active']) ? $searchVariable['is_active'] : '', ['class' => 'form-control']) }}
                                                                                                </div>
                                                                                            </div> -->
                <div class="col-md-2 col-sm-2">
                    <div class="form-group ">
                        {{ Form::text('question', isset($searchVariable['question']) ? $searchVariable['question'] : '', ['class' => ' form-control', 'placeholder' => 'Title']) }}
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 ">
                    <div class="d-flex">
                        <button class="btn btn-primary mr-2 px-4"><i class='fa fa-search '></i> Search</button>
                        <a href='{{ route("$modelName.index", $test_id) }}' class="btn btn-primary"> <i
                                class="fa fa-refresh "></i> {{ trans('Clear Search') }}</a>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
        @livewire('question-bulk', ['test_id' => $test_id])
        {{--  <div class="row">
            <div class="box search-panel collapsed-box">
                <div class="box-body mb-2">
                    <hr>
                    <div class="d-md-flex justify-content-between align-items-center gap-3">
                        <a class="btn btn-primary mt-2" href="{{ route('download.sample.file.questions') }}"> Download
                            sample file</a>
                        <form action="{{ route('import.questions', $test_id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group d-flex flex-column justify-content-center ">
                                <input type="file" name="file" required>
                                <button class="btn btn-primary mt-2" type="submit">Import Question</button>
                        </form>
                    </div>
                </div>
                <hr>
            </div>
        </div>  --}}
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="box-header with-border pd-custom">
                        <div class="listing-btns">
                            <h1 class="box-title">{{ $sectionName }}'s List</h1>

                            <a href='{{ route("$modelName.add", $test_id) }}'
                                class="btn btn-success btn-small pull-right mb-2">
                                {{ trans('Add New ') }}{{ $sectionNameSingular }} </a>
                        </div>
                    </div>
                    <table class="table table-hover table table-bordered mt-2 ">
                        <thead class="theadLight">
                            <tr>
                                <th>
                                    {{ link_to_route(
                                        "$modelName.index",
                                        trans('Question'),
                                        [
                                            'sortBy' => 'question',
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
                                {{-- <th>
                                    {{ link_to_route(
                                        "$modelName.index",
                                        trans('Time Limit'),
                                        [
                                            'sortBy' => 'time_limit',
                                            'order' => $sortBy == 'time_limit' && $order == 'desc' ? 'asc' : 'desc',
                                            $query_string,
                                        ],
                                        [
                                            'class' =>
                                                $sortBy == 'time_limit' && $order == 'desc'
                                                    ? 'sorting desc'
                                                    : ($sortBy == 'time_limit' && $order == 'asc'
                                                        ? 'sorting asc'
                                                        : 'sorting'),
                                        ],
                                    ) }}
                                </th> --}}
                                <th>
                                    {{ link_to_route(
                                        "$modelName.index",
                                        trans('Marks'),
                                        [
                                            'sortBy' => 'marks',
                                            'order' => $sortBy == 'marks' && $order == 'desc' ? 'asc' : 'desc',
                                            $query_string,
                                        ],
                                        [
                                            'class' =>
                                                $sortBy == 'marks' && $order == 'desc'
                                                    ? 'sorting desc'
                                                    : ($sortBy == 'marks' && $order == 'asc'
                                                        ? 'sorting asc'
                                                        : 'sorting'),
                                        ],
                                    ) }}
                                </th>
                                <th>
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
                                <th{ trans('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="powerwidgets">
                            @if (!$results->isEmpty())
                                @foreach ($results as $result)
                                    <tr class="items-inner">
                                        <td data-th='question'>{{ $result->question }}</td>
                                        <!-- <td data-th='question'>{{ $result->time_limit }}</td> -->
                                        <td data-th='question'>{{ $result->marks }}</td>
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
                                            <a href='{{ route("$modelName.edit", [$test_id, $result->id]) }}'
                                                class="btn btn-primary" title="Edit"> <span
                                                    class="fas fa-edit"></span></a>
                                            <a href='{{ route("$modelName.delete", "$result->id") }}' data-delete="delete"
                                                class="delete_any_item btn btn-danger" title="Delete">

                                                <span class="fas fa-trash-alt"></span></a>
                                            <a href='{{ route("$modelName.view", [$test_id, $result->id]) }}'
                                                class="btn btn-success" title="View"> <span class="fas fa-eye"></span></a>

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
