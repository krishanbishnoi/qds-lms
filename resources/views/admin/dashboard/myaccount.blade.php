@extends('admin.layouts.default')
@section('content')
<style>
	.no-click {
    pointer-events: none;
}
	</style>
<?php
	$userInfo	=	Auth::user();
	$first_name	=	(isset($userInfo->first_name)) ? $userInfo->first_name : '';
	$last_name	=	(isset($userInfo->last_name)) ? $userInfo->last_name : '';


	$email		=	(isset($userInfo->email)) ? $userInfo->email : '';
	$image		=	(isset($userInfo->image)) ? $userInfo->image : '';
	$password	=	(isset($userInfo->password)) ? $userInfo->password : '';
?>
<div class="content-wrapper">
    <div class="page-header">
    <h1 class="text-center">My Account </h1>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
				{{ Form::open(['role' => 'form','url' => 'admin/myaccount','class' => 'mws-form','files'=>'true']) }}
					<div class="form-group <?php echo (!empty($errors->first('first_name'))?"has-error":''); ?>">
						{!! Html::decode( Form::label('first_name', trans("First Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::text('first_name', $first_name, ['class' => 'form-control']) }}
							<div class="error-message help-inline">
								<?php echo $errors->first('first_name'); ?>
							</div>
						</div>
					</div>
					<div class="form-group <?php echo (!empty($errors->first('last_name'))?"has-error":''); ?>">
						{!! Html::decode( Form::label('last_name', trans("Last Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::text('last_name', $last_name, ['class' => 'form-control']) }}
							<div class="error-message help-inline">
								<?php echo $errors->first('last_name'); ?>
							</div>
						</div>
					</div>
					<div class="form-group <?php echo (!empty($errors->first('email'))?"has-error":''); ?>">
						{!! Html::decode( Form::label('email', trans("Email").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::text('email', $email, ['class' => 'form-control no-click ','readonly'=>'readonly']) }}
							<div class="error-message help-inline">
								<?php echo $errors->first('email'); ?>
							</div>
						</div>
					</div>
					<div class="form-group <?php echo (!empty($errors->first('pass'))?"has-error":''); ?>">
						{!! Html::decode( Form::label('pass', trans("password").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							<input type="password" value="$password" id="myInput"  class = 'form-control no-click ' readonly ='readonly'>
							<div class="error-message help-inline">
								<?php echo $errors->first('pass'); ?>
							</div>
						</div>
					</div>
					<div class="form-group <?php echo ($errors->first('image')?'has-error':''); ?>">
						<div class="mws-form-row">
							{!! HTML::decode( Form::label('image', trans(" Image").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
								<div class="mws-form-item">
									{{ Form::file('image', ['class' => '']) }}
									<div class="error-message help-inline">
										<?php echo $errors->first('image'); ?>
									</div>
									<div class='lightbox'>
									@if(USER_IMAGE_URL.$image != "")
										<br />
										<img height="100" width="100" src="{{ USER_IMAGE_URL.$image }}" />
									@endif
									</div>
								</div>
							</div>
						</div>
					<div class="mws-button-row">
						<input type="submit" value="{{ trans('Save') }}" class="btn btn-danger">
						<a href="{{URL::to('admin/myaccount')}}" class="btn btn-primary"><i class=\"icon-refresh\"></i> {{ trans('Reset')  }}</a>
					</div>
				{{ Form::close() }}
                </div>
            </div>
        </div>
    <div>
<div>


@stop

