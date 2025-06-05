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
                            Form::label('category', trans('Category') . '<span class="requireRed"> </span>', ['class' => 'mws-form-label']),
                        ) !!}
                        {{ Form::text('category', isset($searchVariable['category']) ? $searchVariable['category'] : '', ['class' => 'form-control', 'placeholder' => 'Category']) }}
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
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        {{ link_to_route(
                                            'Notification.index',
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
                                    <th>
                                        {{ link_to_route(
                                            'Notification.index',
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
                                    <th>{{ trans('Description') }}</th>
                                    <?php /* <th>
                                    {{
                                        link_to_route(
                                        "Cms.index",
                                        trans("Status"),
                                        array(
                                            'sortBy' => 'is_active',
                                            'order' => ($sortBy == 'is_active' && $order == 'desc') ? 'asc' : 'desc',
                                            $query_string
                                        ),
                                        array('class' => (($sortBy == 'is_active' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'is_active' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
                                        )
                                    }}
                                </th> */
                                    ?>
                                    <th>
                                        {{ link_to_route(
                                            'Notification.index',
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
                                @if (!$result->isEmpty())
                                    @foreach ($result as $record)
                                        <tr class="items-inner">
                                            <td data-th='{{ trans('Page Name') }}'>{{ $record->title }}</td>
                                            <td data-th='{{ trans('Page Name') }}'>{{ $record->name }}</td>
                                            <td data-th='{{ trans('Description') }}'>
                                                {{ strip_tags(wordwrap(Str::limit($record->description, 150), 25, "<br>\n")) }}
                                            </td>
                                            <?php /* <td data-th='{{ trans("Status") }}'>
                                @if($record->is_active	== 1)
                                    <span class="label label-success" >{{ trans("Activated") }}</span>
                                @else
                                    <span class="label label-warning" >{{ trans("Deactivated") }}</span>
                                @endif
                                </td> */
                                            ?>
                                            <td data-th='{{ trans('Modified') }}'>
                                                {{ date(Config::get('Reading.date_format'), strtotime($record->updated_at)) }}
                                            </td>
                                            <td data-th='{{ trans('Action') }}'>
                                                <a title="Edit"
                                                    href="{{ URL::to('admin/notification-template/edit-notification-template/' . $record->id) }}"
                                                    class="btn btn-primary"><span class="fas fa-edit"></span></a>

                                                <a title="Delete"href="{{ URL::to('admin/notification-template/delete-notification/' . $record->id) }}"
                                                    data-delete="delete" class="delete_any_item btn btn-danger"
                                                    title="Delete" data-confirm = 'Are you sure?'><span
                                                        class="fas fa-trash-alt   "></span></a>

                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="alignCenterClass" colspan="5">{{ trans('No Record Found') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="box-footer clearfix">
                            <!-- <div class="col-md-3 col-sm-4 "></div> -->
                            <div class="col-md-12 col-sm-12 text-right ">@include('pagination.default', ['paginator' => $result])</div>
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
