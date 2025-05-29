@extends('admin.layouts.default')
@section('content')
<!-- JS & CSS library of MultiSelect plugin -->
<script src="https://phpcoder.tech/multiselect/js/jquery.multiselect.js"></script>
<link rel="stylesheet" href="https://phpcoder.tech/multiselect/css/jquery.multiselect.css">

<div class="content-wrapper">
	<div class="page-header">
		<h2 class="page-title">Add {{ $sectionNameSingular }}</h2>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<!-- <li class="breadcrumb-item"><a href="#">Forms</a></li> -->
				<!-- <li class="breadcrumb-item active" aria-current="page">Forget Password</li> -->
				<li class="breadcrumb-item"><a href="{{ route('dashboard')}}"><i class=" fa fa-dashboard"></i>Dashboard</a></li>
				<li class="breadcrumb-item" ><a href="{{ route($modelName.'.index')}}">{{ $sectionName }}</a></li>
				<li class="breadcrumb-item active" >Add {{ $sectionNameSingular }}</li>
			</ol>
		</nav>
	</div>
	<div class="row">
		<div class="col-md-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					{{ Form::open(['role' => 'form','url' => 'admin/notification-template/add-notification-template','class' => 'mws-form']) }}
					<div class="mws-panel-body no-padding tab-content">
						<!-- <div class="form-group <?php echo ($errors->first('name')?'has-error':''); ?>">
							<div class="mws-form-row">
								{!! Html::decode( Form::label('name',trans("Page Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
								<div class="mws-form-item">
									{{ Form::text('name','', ['class' => 'form-control']) }}
									<div class="error-message help-inline">
										<?php echo $errors->first('name'); ?>
									</div>
								</div>
							</div>
						</div> -->
						<div class="form-group <?php echo ($errors->first('title')) ? 'has-error' : ''; ?>">
							{!! Html::decode( Form::label('title', trans("Title").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::text('title','', ['class' => 'form-control']) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('title'); ?>
								</div>
							</div>
						</div>
						<div class="form-group <?php echo ($errors->first('name')?'has-error':''); ?>">
							<div class="mws-form-row">
								{!! Html::decode( Form::label('name',trans("Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
								<div class="mws-form-item">
									{{ Form::text('name','', ['class' => 'form-control']) }}
									<div class="error-message help-inline">
										<?php echo $errors->first('name'); ?>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group <?php echo $errors->first('en_description') ? 'has-error' : ''; ?>">
							{!! Html::decode( Form::label('description',trans("Description").'<span class="requireRed"> *  </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::textarea("description",'', ['class' => 'form-control textarea_resize','id' => 'description' ,"rows"=>3,"cols"=>3]) }}
								<span class="error-message descriptionTypeError help-inline">
								<?php echo $errors->first('description') ? $errors->first('description') : ''; ?>
								</span>
							</div>
							<script>
								var description =
								CKEDITOR.replace('description', {
								extraAllowedContent: 'div',
								height: 300
								});

							</script>
						</div>

						<!-- <div class="form-group <?php echo ($errors->first('meta_title')) ? 'has-error' : ''; ?>">
							{!! Html::decode( Form::label('meta_title', trans("Meta Title").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::text('meta_title','', ['class' => 'form-control']) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('meta_title'); ?>
								</div>
							</div>
						</div>
						<div class="form-group <?php echo ($errors->first('meta_description')) ? 'has-error' : ''; ?>">
							{!! Html::decode( Form::label('meta_description', trans("Meta Description").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::text('meta_description','', ['class' => 'form-control']) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('meta_description'); ?>
								</div>
							</div>
						</div>
						<div class="form-group <?php echo ($errors->first('meta_keywords')) ? 'has-error' : ''; ?>">
							{!! Html::decode( Form::label('meta_keywords', trans("Meta Keywords").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::text('meta_keywords','', ['class' => 'form-control']) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('meta_keywords'); ?>
								</div>
							</div>
						</div> -->
						<div class="mws-button-row">
							<input type="submit" value="{{ trans('Save') }}" class="btn btn-danger">
							<a href="{{URL::to('admin/notification-template/add-notification-template')}}" class="btn btn-primary"><i class=\"icon-refresh\"></i> {{ trans('Reset')  }}</a>
							<a href="{{URL::to('admin/notification-template')}}" class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans('Cancel')  }}</a>
						</div>
					</div>
					{{ Form::close() }}
				</div>
			</div>
		</div>
	<div>
<div>
	<script>
	jQuery('#contest_stocks').multiselect({
	//	columns: 1,
		placeholder: 'Select Stocks',
		search: true

	});
</script>
<style>
	.datetimepicker{ position: relative; }
</style>
<script type="text/javascript">








</script>
@stop
