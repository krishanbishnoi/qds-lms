@extends('trainer.layouts.default')
@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.10/clipboard.min.js"></script>



    <div class="content-wrapper">
        <div class="page-header">
            <h1>
                {{ $sectionName }}
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ URL::to('trainer/dashboard') }}"><i class="fa fa-dashboard"></i>
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
                            Form::label('title', trans('Title') . '<span class="requireRed"> </span>', ['class' => 'mws-form-label']),
                        ) !!}
                        {{ Form::text('title', isset($searchVariable['title']) ? $searchVariable['title'] : '', ['class' => 'form-control', 'placeholder' => 'Title']) }}
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
                                <a class="btn btn-success" href="{{ route('export.tests') }}">Export Tests</a>

                                <a href='{{ route("$modelName.add") }}' class="btn btn-success btn-small pull-right mb-2">
                                    {{ trans('Add New ') }}{{ $sectionNameSingular }} </a>
                            </div>
                        </div>
                        <table class="table table-hover table table-bordered mt-2 ">
                            <thead class="theadLight">
                                <tr>
                                    <th width="12%">
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('Title'),
                                            [
                                                'sortBy' => 'title',
                                                'order' => $sortBy == 'title' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'title' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'title' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>
                                    @if (Auth::user()->user_role_id == SUPER_ADMIN_ROLE_ID)
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
                                    @endif
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
                                            trans('Start Date Time'),
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
                                    <th width="15%">
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('End Date Time'),
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
                                    <th width="15%">
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

                                    {{-- <th  width="10%" >
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
                                    </th> --}}
                                    <th width="28%">{{ trans('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="powerwidgets">
                                @if (!$results->isEmpty())
                                    @foreach ($results as $record)
                                        <tr class="items-inner">
                                            <td data-th="{{ trans('Page Name') }}">{{ $record->title }}</td>
                                            @if (Auth::user()->user_role_id == SUPER_ADMIN_ROLE_ID)
                                                <td data-th="{{ trans('Page Name') }}">{{ $record->created_by }}</td>
                                            @endif
                                            <!-- <td data-th="{{ trans('Page Name') }}">{{ $record->type }}</td> -->

                                            <!-- <td data-th="{{ trans('Page Name') }}">{{ $record->minimum_marks }}</td> -->
                                            <td data-th="{{ trans('Page Name') }}">{{ $record->start_date_time }}</td>
                                            <td data-th="{{ trans('Page Name') }}">{{ $record->end_date_time }}</td>
                                            <td data-th='{{ trans('Status') }}'>
                                                @if ($record->status == 0)
                                                    <span class="btn btn-danger ">{{ trans('Upcoming') }}</span>
                                                @elseif($record->status == 1)
                                                    <span class="btn btn-warning">{{ trans('Ongoing ') }}</span>
                                                @else
                                                    <span class="btn btn-success">{{ trans('Completed') }}</span>
                                                @endif
                                            </td>

                                            {{-- <td data-th='{{ trans('Modified') }}'>
                                                {{ date(Config::get('Reading.date_format'), strtotime($record->updated_at)) }}
                                            </td> --}}
                                            <td data-th='' class="action-td">
                                                <!-- @if ($record->is_active == 1)
    <a  title="Click To Deactivate" href='{{ route("$modelName.status", [$record->id, 0]) }}' class="btn btn-success btn-small status_any_item "><span class="fa fa-ban"></span>
                     </a>
@else
    <a title="Click To Activate" href='{{ route("$modelName.status", [$record->id, 1]) }}' class="btn btn-warning btn-small status_any_item"><span class="fa fa-check"></span>
                     </a>
    @endif  -->
                                                <a href='{{ route("$modelName.edit", "$record->id") }}'
                                                    class="btn btn-primary" title="Edit"> <span
                                                        class="fas fa-edit"></span></a>


                                                <a href='{{ route("$modelName.delete", "$record->id") }}'
                                                    data-delete="delete" class="delete_any_item btn btn-danger"
                                                    title="Delete" data-confirm='Are you sure?'>
                                                    <span class="fas fa-trash-alt   "></span>
                                                </a>

                                                <a href='{{ route("$modelName.view", "$record->id") }}'
                                                    class="btn btn-warning" title="View"> <span
                                                        class="fas fa-eye"></span></a>
                                                <a href='{{ route('TrainerQuestion.index', "$record->id") }}'
                                                    class="btn btn-info" title="View Questions"> <span
                                                        class="fa fa-question"></span></a>
                                                <a href='{{ route('import.importTrainerTestsParticipants', "$record->id") }}'
                                                    class="btn btn-success" title="Import Tests Participants"> <span
                                                        class="fa fa-plus"></span></a>

                                                <a id="copyButton" class="btn btn-info" title="Get Test Link"
                                                    data-clipboard-text="{{ route('userTestDetails.index.link.copied', $record->id) }}">
                                                    <span class="fas fa-link"></span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="alignCenterClass" colspan="10">{{ trans('No Record Found') }}</td>
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
@stop
