@extends('admin.layouts.default')
@section('content')
<script>

	/* For open Email detail popup */
	function getPopupClient(id){
		$.ajax({
			url: '<?php echo URL::to('/admin/email-logs/email_details')?>/'+id,
			type: "POST",
			headers: {
					'X-CSRF-TOKEN': "{{ csrf_token() }}"
					},

			success : function(r){
				$("#getting_basic_list_popover").html(r);
				$("#getting_basic_list_popover").modal('show');
			}
		});
	}

</script>
<div aria-hidden="false" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="getting_basic_list_popover" class="modal fade in" style="display: none;">
</div>
<div class="content-wrapper">
    <div class="page-header">
        <h1>
        Email Logs
		  </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{URL::to('admin/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Email Logs</li>

            </ol>
        </nav>
    </div>
	<div class="box search-panel collapsed-box">

		<div class="box-body" >
        {{ Form::open(['method' => 'get','role' => 'form','url' => '/admin/email-logs','class' => 'row mws-form']) }}
			{{ Form::hidden('display') }}
			<?php
				$email_to	=	Request::get('email_to');
				$subject	=	Request::get('subject');
                $keyword	=	Request::get('keyword');
			?>
				<div class="col-md-2 col-sm-2">
					<div class="form-group ">
					{!! Html::decode( Form::label('email_to',trans("Email To").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
						{{ Form::text(
								'email_to',
								 isset($email_to) ? Request::get('email_to') :'',
								 ['class' =>'form-control','id'=>'country','placeholder'=>'Email'])
						}}
					</div>
				</div>
                <div class="col-md-2 col-sm-2">
					<div class="form-group ">
					{!! Html::decode( Form::label('subject',trans("Subject").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
						{{ Form::text(
								'subject',
								 isset($subject) ? $subject :'',
								 ['class' =>'form-control','id'=>'country','placeholder'=>'Subject'])
						}}
					</div>
				</div>
                <div class="col-md-2 col-sm-2">
					<div class="form-group ">
					{!! Html::decode( Form::label('keyword',trans("Keyword").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
						{{ Form::text(
								'keyword',
								 isset($keyword) ? $keyword :'',
								 ['class' =>'form-control','id'=>'country','placeholder'=>'Keyword'])
						}}
					</div>
				</div>
				<div class="col-md-4 col-sm-4 padding" >
                <div class="d-flex">
					<button class="btn btn-primary mr-2 px-4"><i class='fa fa-search '></i> {{ trans('Search') }}</button>
					<a href="{{URL::to('/admin/email-logs')}}"  class="btn btn-primary"><i class="fa fa-refresh "></i> {{ trans('Clear Search') }}</a>
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
						<h1 class="box-title"> Email Logs</h1>


						</div>
					</div>
					    <table class="table table-hover brdrclr mt-2">
                            <thead class="theadLight">
                        <tr>
                            <th width="5%">{{ trans('Sr.') }}</th>
                            <th width="20%">
                                {{ trans('Email To') }}
                            </th>
                            <th width="20%">
                                {{ trans('Email From') }}
                            </th>
                            <th width="20%">
                                {{ trans('Subject') }}
                            </th>
                            <th width="20%">
                                {{ link_to_route(
                                    'EmailLogs.listEmail',
                                    'Mail Sent On',
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
                            <th width="10%">
                                {{ trans('Action') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $number = 1; ?>
                        @if (!$result->isEmpty())
                            <?php
                            $page = $result->currentPage();
                            $pagelimit = Config::get('Reading.records_per_page');
                            $number = $page * $pagelimit - $pagelimit;
                            $number++;
                            ?>
                            @foreach ($result as $data)
                                <tr class="items-inner">
                                    <td data-th='Name'>{{ $number++ }}</td>
                                    <td data-th="{{ trans('Email To') }}">{{ $data->email_to }}</td>
                                    <td data-th="{{ trans('Email From') }}">{{ $data->email_from }}</td>
                                    <td data-th="{{ trans('Subject') }}">{{ $data->subject }}</td>
                                    <td data-th="{{ trans('messages.system_management.created') }}">
                                        {{ date(Config::get('Reading.date_format'), strtotime($data->created_at)) }}</td>
                                    <td class="action-td" data-th="{{ trans('Action') }}">
                                        <a href='javascript:void(0);' class="btn btn-info" title='{{ trans('View') }}'
                                            onclick="getPopupClient({{ $data->id }})"> <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" style="text-align:center;font-weight:bold;">
                                    {{ trans('Record not found.') }}
                                </td>
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
	.padding{
		padding-top:20px;
	}
	</style>
@stop

