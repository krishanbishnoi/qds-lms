@extends('admin.layouts.default')

@section('content')

<script type="text/javascript">
$(function(){
	$('[data-delete]').click(function(e){

	     		e.preventDefault();
		// If the user confirm the delete
		if (confirm('Do you really want to delete the element ?')) {
			// Get the route URL
			var url = $(this).prop('href');
			// Get the token
			var token = $(this).data('delete');
			// Create a form element
			var $form = $('<form/>', {action: url, method: 'post'});
			// Add the DELETE hidden input method
			var $inputMethod = $('<input/>', {type: 'hidden', name: '_method', value: 'delete'});
			// Add the token hidden input
			var $inputToken = $('<input/>', {type: 'hidden', name: '_token', value: token});
			// Append the inputs to the form, hide the form, append the form to the <body>, SUBMIT !
			$form.append($inputMethod, $inputToken).hide().appendTo('body').submit();
		}
	});
});
</script>
<se ction class="content-header">
	<h1>{{ trans('Setting') }}</h1>
	<br>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li class="active">{{ trans("Setting") }}</li>
	</ol>
	<br>
</section>
<section class="content">
	<div class="row">
		<div id="mws-t hemer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i>
			<i class="icon-bended-arrow-right"></ i>
		</div>
		</div>
		{{ Form::open(['method' => 'get','role' => 'form','route' => $modelName.'.listSetting','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<div class="col-md-3 col-sm-3">
			<div class="form-group ">
				{{ Form::text('title',((isset($searchVariab  le['title'])) ? $searchVariable['title'] : ''), ['class' => 'form-control', 'placeholder' => 'Title']) }}
			</div>
		</div>
		<!--<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>-->
		<div class="col-md-4 col-sm-4">
			<button class="btn btn-primary"><i class='fa fa-search '></i> Search</button>
			<a href="{{route($modelName.'.listSetting')}}" class="btn btn-primary"><i class='fa fa-refresh '></i> Reset</a>
		</div>
		{{ Form::close() }}
		<div class="col-md-5 col-sm-5 ">
			<div class="form-group pull-right">
				<a href="{{route($modelName.'.add')}}" class="btn btn-suc  cess btn-small align">{{ trans("Add New Setting") }} </a>
			</div>
		</div>
	</div>

	<div class="box">
		<div class="box-body ">
			    <table class="table table-hover table table-bordered mt-2 ">
                            <thead class="theadLight">
					<tr>
						<th width="10%" >
						{{
							link_to_route(
								$modelName.'.listSetting',
								'Id',
								array(
									'sortBy' => 'id',
									'order' => ($sortBy == 'id' && $order == 'desc') ? 'asc' : 'desc'
								),
							   array('class' => (($sortBy == 'id' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'id' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
						</th>
						<th width="30%">
						{{
							link_to_route(
								$modelName.'.listSetting',
								'Title',
								array(
									'sortBy' => 'title',
									'order' => ($sortBy == 'title' && $order == 'desc') ? 'asc' : 'desc'
								),
							   array('class' => (($sortBy == 'title' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'title' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
						</th>
						<th width="20%">
						{{
							link_to_route(
									$modelName.'.listSetting',
									'Key',
									array(
										'sortBy' => 'key',
										'order' => ($sortBy == 'key' && $order == 'desc') ? 'asc' : 'desc'
									),
								   array('class' => (($sortBy == 'key' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'key' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								)
						}}
						</th>
						<th width="20%">
						{{
							link_to_route(
								$modelName.'.listSetting',
								'Value',
								array(
									'sortBy' => 'value',
									'order' => ($sortBy == 'value' && $order == 'desc') ? 'asc' : 'desc'
								),
							   array('class' => (($sortBy == 'value' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'value' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
						</th>
						<th>{{ 'Action' }}</th>
					</tr>
				</thead>
				<tbody>
					@if(!$result->isEmpty())
					@foreach($result as $record)
					<?php
						$key = $record->key;
						$keyE = explode('.', $key);
						$keyPrefix = $keyE['0'];
						if (isset($keyE['1'])) {
							$keyTitle = '.' . $keyE['1'];
						} else {
							$keyTitle = '';
						}
						?>
					<tr>
						<td data-th='Id'>{{ $record->id }}</td>
						<td data-th='Title'>{{ $record->title }}</td>
						<td data-th='Key'>
							<a target="_blank" href="{{route($modelName.'.prefix',$keyPrefix)}}" >{{ $keyPrefix }}</a>{{ $keyTitle }}
						</td>
						<td data-th='Value'>
							{{ strip_tags(Str::limit($record->value, 20)) }}
						</td>
						<td data-th='Action'>
							<a href="{{route($modelName.'.edit',$record->id)}}" class="btn btn-info btn-small">{{ trans('Edit') }} </a>
								<a href="{{route($modelName.'.delete',$record->id)}}"  class="btn btn-danger btn-small no-ajax delete_any_item"> {{{ trans('Delete') }}} </a>
						</td>
					</tr>
					 @endforeach
					 @else
						<tr>
							<td class="alignCenterClass" colspan="5" >{{{ trans('No Records Found') }}}</td>
						</tr>
					@endif
				</tbody>
			</t  able>
		</div>
		<div class="box-footer clearfix">
			<div class="col-md-3 col-sm-4 "></div>
			<div class="col-md-9 col-sm-8 text-right ">@ include('pagination.default',['paginator' => $result])</div>
		</div>
	</d	iv>
</section>
@stop
