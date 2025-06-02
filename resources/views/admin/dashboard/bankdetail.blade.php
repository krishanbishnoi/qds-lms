@extends('admin.layouts.default')
@section('content')
<?php
	
	$account_holder_name	=	(isset($userInfo->account_holder_name)) ? $userInfo->account_holder_name : '';
	$bank_name	=	(isset($userInfo->bank_name)) ? $userInfo->bank_name : '';

	
	$account_number		=	(isset($userInfo->account_number)) ? $userInfo->account_number : '';
	$account_type		=	(isset($userInfo->account_type)) ? $userInfo->account_type : '';
	$iban_number	=	(isset($userInfo->iban_number)) ? $userInfo->iban_number : '';
?>
<section class="content-header">
	<h1 class="text-center">Bank Details
		<!-- <span class="pull-right">
			<a href="{{URL::to('admin/change-password')}}" class="btn btn-danger"><i class=\"icon-refresh\"></i>Change Password</a>
		</span> -->
	</h1>
	<div class="clearfix"></div>
</section>
<section class="content">
    <div class="box">
		<div class="box-body">
			<div class="row">
				<div class="col-md-6">
				{{ Form::open(['role' => 'form','url' => 'admin/bankdetail','class' => 'mws-form','files'=>'true']) }}
					<div class="form-group <?php echo (!empty($errors->first('account_holder_name'))?"has-error":''); ?>">
						{!! Html::decode( Form::label('account_holder_name', trans("Account Holder Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::text('account_holder_name', $account_holder_name, ['class' => 'form-control']) }}  
							<div class="error-message help-inline">
								<?php echo $errors->first('account_holder_name'); ?>
							</div>
						</div>
					</div>
					<div class="form-group <?php echo (!empty($errors->first('bank_name'))?"has-error":''); ?>">
						{!! Html::decode( Form::label('bank_name', trans("Bank Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::text('bank_name', $bank_name, ['class' => 'form-control']) }}  
							<div class="error-message help-inline">
								<?php echo $errors->first('bank_name'); ?>
							</div>
						</div>
					</div>
					<div class="form-group <?php echo (!empty($errors->first('account_number'))?"has-error":''); ?>">
						{!! Html::decode( Form::label('account_number', trans("Account Number").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::text('account_number', $account_number, ['class' => 'form-control']) }}  
							<div class="error-message help-inline">
								<?php echo $errors->first('account_number'); ?>
							</div>
						</div>
					</div>
					<div class="form-group <?php echo (!empty($errors->first('account_type'))?"has-error":''); ?>">
						{!! Html::decode( Form::label('account_type', trans("Account Type").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::text('account_type', $account_type, ['class' => 'form-control']) }}  
							<div class="error-message help-inline">
								<?php echo $errors->first('account_type'); ?>
							</div>
						</div>
					</div>
					<div class="form-group <?php echo (!empty($errors->first('iban_number'))?"has-error":''); ?>">
						{!! Html::decode( Form::label('iban_number', trans("Iban Number").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::text('iban_number', $iban_number, ['class' => 'form-control']) }}  
							<div class="error-message help-inline">
								<?php echo $errors->first('iban_number'); ?>
							</div>
						</div>
					</div>
					
					<div class="mws-button-row">
						<input type="submit" value="{{ trans('Save') }}" class="btn btn-danger">
						<a href="{{URL::to('admin/bankdetail')}}" class="btn btn-primary"><i class=\"icon-refresh\"></i> {{ trans('Reset')  }}</a> 
					</div>
				{{ Form::close() }}
				</div>
			</div> 
	    </div>
	</div> 
</section>
<style>
#MyaccountAddress {
	resize: vertical; /* user can resize vertically, but width is fixed */
}
.error-message{
	color: #f56954 !important;
}
</style>


@stop