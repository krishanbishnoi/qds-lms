@extends('admin.layouts.default')

@section('content')

<section class="content-header">
	<h1>
		{{ trans("Edit Setting") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><a href="{{route($modelName.'.listSetting')}}">Setting</a></li>
		<li class="active">Edit Setting</li>
	</ol>
</section>
<section class="content"> 
	<div class="row pad">
		<div class="col-md-6">	
		{{ Form::open(['role' => 'form','route' => [$modelName.'.edit',$result->id],'class' => 'mws-form']) }}
		<div class="mws-panel-body no-padding tab-content">
			<div class="form-group <?php echo ($errors->first('title')?'has-error':''); ?>">
				<div class="mws-form-row">
					{!! Html::decode( Form::label('title',trans("Title").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::text('title',$result->title, ['class' => 'form-control']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('title'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group <?php echo ($errors->first('key')?'has-error':''); ?>">
				<div class="mws-form-row">
					{!! Html::decode( Form::label('key',trans("Key").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::text('key', $result->key, ['class' => 'form-control']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('key'); ?>
						</div>
						<small>e.g., 'Site.title'</small>
					</div>
				</div>
			</div>
			<div class="form-group <?php echo ($errors->first('value')?'has-error':''); ?>">
				<div class="mws-form-row">
					{!! Html::decode( Form::label('value',trans("Value").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::textarea('value',$result->value, ['class' => 'form-control small','rows'=>false,'cols'=>false,]) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('value'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group <?php echo ($errors->first('input_type')?'has-error':''); ?>">
				<div class="mws-form-row">
					{!! Html::decode( Form::label('input_type',trans("Input Type").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::text('input_type', $result->input_type, ['class' => 'form-control']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('input_type'); ?>
						</div>
						<small><em><?php echo "e.g., 'text' or 'textarea'";?></em></small>
					</div>
				</div>
			</div>
			<div class="form-group ">
				<div class="mws-form-row">
					{!!  Form::label('editable', 'Editable', ['class' => 'mws-form-label']) !!}
					<div class="mws-form-item">
						<div class="input-prepend">
							<span class="add-on"> 
								{{ Form::checkbox('editable', '1',($result->editable==1)?true:false, ['class' => 'small']) }}
							</span>
							<input type="text" size="16" name="prependedInput2" id="prependedInput2" value="<?php echo "Editable"; ?>" disabled="disabled" style="width:415px;" class="small">
						</div>
					</div>
				</div>
			</div>
			<div class="mws-button-row">
				<input type="submit" value="Save" class="btn btn-danger">
				
				<a href="{{route($modelName.'.edit',$result->id)}}" class="btn btn-primary"><i class=\"icon-refresh\"></i> {{ trans('Reset')  }}</a>
				
				<a href="{{route($modelName.'.listSetting')}}" class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans('Cancel')  }}</a>
			</div>
		</div>
		{{ Form::close() }}
		</div>    	
	</div>
<section>
@stop
