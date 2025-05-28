@extends('trainer.layouts.default')
@section('content')
<?php
	$userInfo	=	Auth::user();
	$full_name	=	(isset($userInfo->full_name)) ? $userInfo->full_name : '';
	$username	=	(isset($userInfo->username)) ? $userInfo->username : '';
	$email		=	(isset($userInfo->email)) ? $userInfo->email : '';
?>
<div class="content-wrapper">
    <div class="page-header">
    <h1 class="text-center">Change Password </h1>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                {{ Form::open(['role' => 'form','url' => 'trainer/changed-password','class' => 'mws-form','id'=>'change_password_form','files'=>'true']) }}
                <div class="form-group <?php echo (!empty($errors->first('old_password'))?"has-error":''); ?>">
					{!! Html::decode( Form::label('old_password', trans("Old Password").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::password('old_password',['class' => 'form-control','id'=>'old_password']) }}
						<!-- Toll tip div end here -->
						<div class="error-message help-inline">
							<?php echo $errors->first('old_password'); ?>
						</div>
					</div>
				</div>
				<div class="form-group <?php echo (!empty($errors->first('new_password'))?"has-error":''); ?>">
					{!! Html::decode( Form::label('new_password', trans("New Password").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}

					<div class="mws-form-item">
						{{ Form::password('new_password', ['class' => 'form-control','id'=>'new_password']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('new_password'); ?>
						</div>
					</div>
				</div>
				<div class="form-group <?php echo (!empty($errors->first('confirm_password'))?"has-error":''); ?>">
					{!! Html::decode( Form::label('confirm_password', trans("Confirm Password").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label' ])) !!}

					<div class="mws-form-item">
						{{ Form::password('confirm_password', ['class' => 'form-control' ,'id'=>'confirm_password']) }}

						<div class="error-message help-inline">
							<?php echo $errors->first('confirm_password'); ?>
						</div>
					</div>
				</div>
				<div class="mws-button-row">
					<input type="submit" value="{{ trans('Save') }}" class="btn btn-danger">
					<a href="{{URL::to('trainer/change-password')}}" class="btn btn-primary"><i class=\"icon-refresh\"></i> {{ trans('Reset')  }}</a>
				</div>
				{{ Form::close() }}
                </div>
            </div>
        </div>
    <div>
<div>
@stop

