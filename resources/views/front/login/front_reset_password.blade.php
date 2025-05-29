@extends('front.layouts.login_layout')

@section('content')

<style>
.bg-olive {
    background-color: #ff8400 !important;
}
.btn {
    padding: 12px 9px;
    margin-bottom: 15px !important;
}
.form-box {
  margin:0 !important;
}
.form-box {
  width: 420px;
  height: auto;
}
.form-box .body input.form-control:focus {
  border-bottom: 1px solid rgba(255, 132, 0, 0.4196078431372549) !important;
}
.box-body01 {
  max-width: 300px;
  right: 0;
}
.bg-olive {
    background-color: #ff8400 !important;
}
.btn {
    padding: 12px 9px;
    margin-bottom: 15px !important;
}
.login-page .icon {
	top:11px
}
</style>

<div class="loginpat1"></div>
<div class="loginpat2"></div>
<div class="loginpat3"></div>

<div class="form-box" id="login-box">
	<div class="logo-block">
    	<img src="{{WEBSITE_IMG_URL}}logo-small.png" alt="">
  	</div>
	<div class="header">Reset Password</div>
	{{ Form::open(['role' => 'form','url' => '/reset_password/'.$validate_string]) }}
	<div class="body">
		<div class="form-group relative">
			<i class="icon ion-ios-locked-outline"></i>
			{{ Form::password('new_password',  ['placeholder' => 'New Password', 'class' => 'form-control']) }}
			<div class="error-message help-inline">
				<?php echo $errors->first('new_password'); ?>
			</div>
		</div>
		<div class="form-group relative">
			<i class="icon ion-ios-locked-outline"></i>
		   {{ Form::password('new_password_confirmation', ['placeholder' => 'Confirm Password', 'class' => 'form-control']) }}
		   <div class="error-message help-inline">
				<?php echo $errors->first('new_password_confirmation'); ?>	
			</div>
		</div>
	</div>
	<div class="footer">                                                               
		<button type="submit" class="btn bg-olive btn-block">Submit</button> 
	</div>
	{{ Form::close() }}
</div>

@stop


