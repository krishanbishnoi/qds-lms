@extends('admin.layouts.default')
@section('content')
<section class="content-header">
	<h1>
		View Issue
	</h1>
	<br>
	<ol class="breadcrumb">
		<li><a href="{{ route('dashboard')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><a href="{{ route($modelName.'.index')}}">Isuess </a></li>
		<li class="active">View Issue</li>
	</ol>
</section>
<br>
<section class="content"> 
	<div class="box">
		<div class="box-body">
			<div class="box-header with-border">
				<h3 class="box-title">Isues Info</h3>				
			</div>	
			<div class="row">		
				<div class="col-md-12 col-sm-6">		
					<div id="info1"></div>						 						
					<table class="table table-striped table-responsive"> 
						<tbody>
						<tr>
							<th width="30%" class="text-right">{{ trans("Name") }}</th>
							<td data-th='{{ trans("Name") }}'>{{ $model->name }}</td>
						</tr>
						<tr>
							<th width="30%" class="text-right">{{ trans("Email") }}</th>
							<td data-th='{{ trans("Email") }}'>{{ $model->email }}</td>
						</tr>
						<tr>
							<th width="30%" class="text-right">{{ trans("Subject") }}</th>
							<td data-th='{{ trans("subject") }}'>{{ $model->subject }}</td>
						</tr>
						<tr>
							<th width="30%" valign="top" class="text-right">{{ trans("Issue") }}</th>
							<td data-th='{{ trans("Issue") }}'>{!! nl2br($model->issue) !!}</td>
						</tr>
						<tr>
							<th width="30%" valign="top" class="text-right">{{ trans("Comments") }}</th>
							<td data-th='{{ trans("Issue") }}'>{!! nl2br($model->comment) !!}</td>
						</tr>
						<tr>
							<th width="30%" class="text-right">{{ trans("Created") }}</th>
							<td data-th='{{ trans("Created") }}'>{{ date(Config::get("Reading.date_format") , strtotime($model->created_at)) }}</td>
						</tr>
							<tr>
								<th width="30%" class="text-right txtFntSze">Status</th>
								<td data-th='Status' class="txtFntSze">
									@if($model->status	==1)
									<span class="label label-success" >{{ trans("Resolved") }}</span>
									@else
									<span class="label label-warning" >{{ trans("Pending") }}</span>
									@endif 
								</td>
							</tr> 
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>

@stop
