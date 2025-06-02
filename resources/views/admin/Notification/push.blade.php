@extends('admin.layouts.default')
@section('content')
<!-- JS & CSS library of MultiSelect plugin -->
<script src="https://phpcoder.tech/multiselect/js/jquery.multiselect.js"></script>
<link rel="stylesheet" href="https://phpcoder.tech/multiselect/css/jquery.multiselect.css">

<div class="content-wrapper">
	<div class="page-header">
		<h2 class="page-title">Send Push Notification</h2>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<!-- <li class="breadcrumb-item"><a href="#">Forms</a></li> -->
				<!-- <li class="breadcrumb-item active" aria-current="page">Forget Password</li> -->
				<li class="breadcrumb-item"><a href="{{ route('dashboard')}}"><i class=" fa fa-dashboard"></i>Dashboard</a></li>
				<li class="breadcrumb-item" ><a href="{{ route($modelName.'.index')}}">{{ $sectionName }}</a></li>
				<li class="breadcrumb-item active" >Send Push Notification</li>
			</ol>
		</nav>
	</div>
	<div class="row">
		<div class="col-md-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					{{ Form::open(['role' => 'form','url' => 'admin/add-notification','class' => 'mws-form']) }}
					<div class="mws-panel-body no-padding tab-content">


						<div class="form-group <?php echo ($errors->first('contest_users')?'has-error':''); ?>" >
							<div class="mws-form-row ">
								{!! Html::decode( Form::label('contest_users', trans("Select Users").'<span class="requireRed"></span>',['class' => 'mws-form-label'])) !!}
								<div class="mws-form-item">
									{{ Form::select("contest_users[]",$contest_users,'', ['class' => 'form-control selectpicker','id'=>'contest_users','multiple' ,'data-live-search'=>"true"]) }}
									<div class="error-message help-inline">
										<?php echo $errors->first('contest_users'); ?>
									</div>
								</div>
							</div>
					   </div>
						<div class="form-group <?php echo ($errors->first('Subject')?'has-error':''); ?>">
						<div class="mws-form-row">
						{!! Html::decode( Form::label('subject', trans("Subject").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::text('subject','', ['class' => 'form-control small','maxlength' => '120']) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('subject'); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group <?php echo ($errors->first('message')?'has-error':''); ?>">
							<div class="mws-form-row">
							{!! Html::decode( Form::label('message', trans("Message").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
								<div class="mws-form-item">
									{{ Form::textarea('message','', ['class' => 'form-control small','maxlength' => '300']) }}
									<div class="error-message help-inline">
										<?php echo $errors->first('message'); ?>
									</div>
								</div>
							</div>
					    </div>
						<div class="mws-button-row">
							<input type="submit" value="{{ trans('Send') }}" class="btn btn-danger">
							<a href="{{URL::to('admin/add-notification')}}" class="btn btn-primary"><i class=\"icon-refresh\"></i> {{ trans('Reset')  }}</a>
							<a href="{{URL::to('admin/add-notification')}}" class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans('Cancel')  }}</a>
						</div>
					</div>
					{{ Form::close() }}
				</div>
			</div>
		</div>
	<div>
<div>
	<script>
	jQuery('#contest_users').multiselect({
	//	columns: 1,
		placeholder: 'Select Users',
		search: true

	});
</script>
<style>
	.datetimepicker{ position: relative; }
</style>
<script type="text/javascript">








</script>
@stop
