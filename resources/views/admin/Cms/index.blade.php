@extends('admin.layouts.default')
@section('content')


    <div class="content-wrapper">
        <div class="page-header">
            <h1>
                Cms Page
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i>
                            Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cms Page</li>

                </ol>
            </nav>
        </div>
        <div class="box search-panel collapsed-box">

            <div class="box-body">
                {{ Form::open(['method' => 'get', 'role' => 'form', 'url' => 'admin/cms-manager', 'class' => 'row mws-form']) }}
                {{ Form::hidden('display') }}
                <!-- <div class="col-md-2 col-sm-2">

                    <div class="form-group ">
                    {!! Html::decode(
                        Form::label('Select Status', trans('Select Status') . '<span class="requireRed"> </span>', [
                            'class' => 'mws-form-label',
                        ]),
                    ) !!}
                     {{ Form::select('is_active', ['' => trans('All'), 1 => trans('Active'), 0 => trans('Inactive')], isset($searchVariable['is_active']) ? $searchVariable['is_active'] : '', ['class' => 'form-control']) }}
                    </div>
                   </div> -->
                <div class="col-md-2 col-sm-2">
                    <div class="form-group ">
                        {!! Html::decode(
                            Form::label('title', trans('Title') . '<span class="requireRed"> </span>', ['class' => 'mws-form-label']),
                        ) !!}
                        {{ Form::text('title', isset($searchVariable['title']) ? $searchVariable['title'] : '', ['class' => 'form-control', 'placeholder' => 'Title']) }}
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 paddingtop">
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

                                <a href="{{ URL::to('admin/cms-manager/add-cms') }}"
                                    class="btn btn-success btn-small pull-right mb-2"> {{ trans('Add New Cms') }}</a>
                            </div>
                        </div>
                        <table class="table table-hover table table-bordered mt-2 ">
                            <thead class="theadLight">

                                <th>
                                    {{ link_to_route(
                                        'Cms.index',
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
                                        'Cms.index',
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
                                        'Cms.index',
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
                                            <td class="action-td" data-th='{{ trans('Action') }}'>
                                                <a title="Edit"
                                                    href="{{ URL::to('admin/cms-manager/edit-cms/' . $record->id) }}"
                                                    class="btn btn-primary"><span class="fas fa-edit"></span></a>
                                                <?php /* @if($record->is_active == 1)
                                                								<a  title="Click To Deactivate" href="{{URL::to('admin/cms-manager/update-status/'.$record->id.'/0')}}" class="btn btn-success btn-small status_any_item"><span class="fa fa-ban"></span>
                                                								</a>
                                                							@else
                                                								<a title="Click To Activate" href="{{URL::to('admin/cms-manager/update-status/'.$record->id.'/1')}}" class="btn btn-warning btn-small status_any_item"><span class="fa fa-check"></span>
                                                								</a>
                                                							@endif  */
                                                ?>
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
