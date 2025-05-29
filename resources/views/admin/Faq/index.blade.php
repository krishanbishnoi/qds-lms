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
                    <li class="breadcrumb-item active" aria-current="page">Faqs</li>

                </ol>
            </nav>
        </div>
        <div class="box search-panel collapsed-box">

            <div class="box-body">
                {{ Form::open(['method' => 'get', 'role' => 'form', 'url' => 'admin/faqs-manager', 'class' => 'row mws-form']) }}
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
                <div class="col-md-3 col-sm-3">
                    <div class="form-group ">
                        {!! Html::decode(
                            Form::label('question', trans('Question') . '<span class="requireRed"> </span>', ['class' => 'mws-form-label']),
                        ) !!}
                        {{ Form::text('question', isset($searchVariable['question']) ? $searchVariable['question'] : '', ['class' => 'form-control', 'placeholder' => 'Question']) }}
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

                                <a href="{{ URL::to('admin/faqs-manager/add-faqs') }}"
                                    class="btn btn-success btn-small pull-right mb-2"> {{ trans('Add Faq') }}</a>
                            </div>
                        </div>
                        <table class="table table-hover brdrclr mt-2">
                            <thead class="theadLight">
                                <th width="">
                                    {{ link_to_route(
                                        'Faq.index',
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
                                <th width="">{{ trans('Answer') }}</th>
                                <!-- <th width="10%">
            {{ link_to_route(
                'Faq.index',
                trans('Answer'),
                [
                    'sortBy' => 'answer',
                    'order' => $sortBy == 'answer' && $order == 'desc' ? 'asc' : 'desc',
                    $query_string,
                ],
                [
                    'class' =>
                        $sortBy == 'answer' && $order == 'desc'
                            ? 'sorting desc'
                            : ($sortBy == 'answer' && $order == 'asc'
                                ? 'sorting asc'
                                : 'sorting'),
                ],
            ) }}
           </th>
           <th width="25%">{{ trans('Arabic Answer') }}</th> -->
                                <?php /* <th>
								{{
									link_to_route(
									"Faq.index",
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
                                <th width="10%">
                                    {{ link_to_route(
                                        'Faq.index',
                                        trans('Created On'),
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
                                <th width="20%">{{ trans('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="powerwidgets">
                                @if (!$result->isEmpty())
                                    @foreach ($result as $record)
                                        <tr class="items-inner">
                                            <td>{!! strip_tags(wordwrap($record->question, 25, "<br>\n")) !!}</td>
                                            <td>{!! strip_tags(wordwrap($record->answer, 25, "<br>\n")) !!}</td>
                                            <!-- <td >{{ strip_tags(Str::limit($record->ar_question, 50)) }}</td>
            <td >{{ strip_tags(Str::limit($record->ar_answer, 50)) }}</td> -->
                                            <?php /* <td data-th='{{ trans("Status") }}'>
								@if($record->is_active	== 1)
									<span class="label label-success" >{{ trans("Activated") }}</span>
								@else
									<span class="label label-warning" >{{ trans("Deactivated") }}</span>
								@endif
								</td> */
                                            ?>
                                            <td data-th='{{ trans('Modified') }}'>
                                                {{ date(Config::get('Reading.date_format'), strtotime($record->created_at)) }}
                                            </td>
                                            <td class="action-td" data-th='{{ trans('Action') }}'>
                                                <a title="Edit"
                                                    href="{{ URL::to('admin/faqs-manager/edit-faqs/' . $record->id) }}"
                                                    class="btn btn-primary"><span class="fas fa-edit"></span></a>
                                                @if ($record->is_active == 1)
                                                    <a title="Click To Deactivate"
                                                        href="{{ URL::to('admin/faqs-manager/update-status/' . $record->id . '/0') }}"
                                                        class="btn btn-success btn-small status_any_item"><span
                                                            class="fa fa-ban"></span>
                                                    </a>
                                                @else
                                                    <a title="Click To Activate"
                                                        href="{{ URL::to('admin/faqs-manager/update-status/' . $record->id . '/1') }}"
                                                        class="btn btn-warning btn-small status_any_item"><span
                                                            class="fa fa-check"></span>
                                                    </a>
                                                @endif
                                                <a href='{{ route('Faq.delete', "$record->id") }}' data-delete="delete"
                                                    class="delete_any_item btn btn-danger" title="Delete"
                                                    data-confirm = 'Are you sure?'>
                                                    <span class="fas fa-trash-alt   "></span>
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
                        <div class="box-footer clearfix">
                            <div class="col-md-3 col-sm-4 "></div>
                            <div class="col-md-9 col-sm-8 text-right ">@include('pagination.default', ['paginator' => $result])</div>
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

    <style>
        .pull-right {
            float: right !important;
        }
    </style>
@stop
