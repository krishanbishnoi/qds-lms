@extends('admin.layouts.default')
@section('content')


    <div class="content-wrapper">
        <div class="page-header">
            <h1>
                Email Templates
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i>
                            Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Email Templates</li>

                </ol>
            </nav>
        </div>
        <div class="box search-panel collapsed-box">

            <div class="box-body">
                {{ Form::open(['method' => 'get', 'role' => 'form', 'url' => 'admin/email-manager', 'class' => 'row']) }}
                {{ Form::hidden('display') }}
                <div class="col-md-3 col-sm-3">
                    <div class="form-group ">
                        {!! Html::decode(
                            Form::label('name', trans('Name') . '<span class="requireRed">  </span>', ['class' => 'mws-form-label']),
                        ) !!}
                        {{ Form::text('name', isset($searchVariable['name']) ? $searchVariable['name'] : '', ['class' => 'form-control', 'placeholder' => 'Name']) }}
                    </div>
                </div>
                <div class="col-md-3 col-sm-3">
                    <div class="form-group ">
                        {!! Html::decode(
                            Form::label('subject', trans('Subject') . '<span class="requireRed">  </span>', ['class' => 'mws-form-label']),
                        ) !!}
                        {{ Form::text('subject', isset($searchVariable['subject']) ? $searchVariable['subject'] : '', ['class' => 'form-control', 'placeholder' => 'Subject']) }}
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 padding">
                    <div class="d-flex">
                        <button class="btn btn-primary me-2"><i class='fa fa-search '></i> Search</button>
                        <a href="{{ URL::to('admin/email-manager') }}" class="btn btn-primary"><i
                                class="fa fa-refresh "></i> {{ trans('Clear Search') }}</a>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 padding">


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

                                    <a href="{{ URL::to('admin/email-manager/add-template') }}"
                                        class="btn btn-success btn-small  pull-right mb-2">{{ trans('Add Email Template') }}
                                    </a>
                                </div>
                            </div>
                            <table class="table table-hover brdrclr mt-2">
                                <thead class="theadLight">
                                    <tr>
                                        <th width="5%">{{ trans('Sr.') }}</th>
                                        <th>
                                            {{ link_to_route(
                                                'EmailTemplate.index',
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
                                        <th>
                                            {{ link_to_route(
                                                'EmailTemplate.index',
                                                trans('Subject'),
                                                [
                                                    'sortBy' => 'subject',
                                                    'order' => $sortBy == 'subject' && $order == 'desc' ? 'asc' : 'desc',
                                                    $query_string,
                                                ],
                                                [
                                                    'class' =>
                                                        $sortBy == 'subject' && $order == 'desc'
                                                            ? 'sorting desc'
                                                            : ($sortBy == 'subject' && $order == 'asc'
                                                                ? 'sorting asc'
                                                                : 'sorting'),
                                                ],
                                            ) }}
                                        </th>
                                        <th>

                                            {{ link_to_route(
                                                'EmailTemplate.index',
                                                trans('Created'),
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
                                        <th>{{ trans('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="powerwidgets">
                                    <?php $number = 1; ?>
                                    <?php
							if(!$result->isEmpty()){
								$page = $result->currentPage();
									$pagelimit = Config::get("Reading.records_per_page");
									$number = ($page * $pagelimit) - ($pagelimit);
									$number++;
							foreach($result as $record){?>
                                    <tr class="items-inner">
                                        <td data-th='Name'>{{ $number++ }}</td>
                                        <td>{{ $record->name }}</td>
                                        <td>{{ $record->subject }}</td>
                                        <td>{{ date(Config::get('Reading.date_format'), strtotime($record->created_at)) }}
                                        </td>
                                        <td class="action-td">
                                            <a title="Edit"
                                                href="{{ URL::to('admin/email-manager/edit-template/' . $record->id) }}"
                                                class ="btn btn-primary">
                                                <span class="fas fa-edit"></span>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>

                                <?php }else{
						?>
                                <tr>
                                    <td align="center" style="text-align:center;" colspan="4"> No Result Found</td>
                                </tr>
                                <?php
					} ?>
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
            .padding {
                padding-top: 20px;
            }
        </style>
    @stop
