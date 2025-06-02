@extends('admin.layouts.login_layout')
@section('content')
<script src="{{WEBSITE_JS_URL}}admin/jquery.min.js"></script>
<div class="form-box" id="login-box">
	<div class="header">Two-Factor authentication</div>
	{{ Form::open(['role' => 'form','url' => "login",'class' => 'mws-form','id'=>"two_step_form",'autocomplete'=>'off']) }}
	<div class="body bg-gray">
		<div class="alert alert-info">
			Attempting to log in as {{ Auth::user()->full_name }}.
		</div>
		<div class="form-group">
			<label class="dob_label"><b>Add 2FA Code</b></label>
			{{ Form::text('code', null, ['placeholder' => '000000', 'class' => 'form-control']) }}
			<div class="error-message help-inline">
				<?php echo $errors->first('code'); ?>
			</div>
		</div>
	</div>
	<div class="footer">                                                               
		<input type="button" class="btn bg-olive btn-block" value="Verify" onclick="verifyCode()"> 
	</div>
	{{ Form::close() }}
</div>

<script>
	function goToByScroll(id,form_id){
		$('html, body').animate({scrollTop:($("."+id).offset().top-80)}, 'slow');
	}

	function verifyCode(){
		$('#loader_img').show();
		$('.help-inline').html('');
		$('.help-inline').removeClass('error');
		$.ajax({
			headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			url: '{{ URL("aquaradiuspnlx/admin-verify-two-step-verification") }}',
			type:'POST',
			data: $('#two_step_form').serialize(),
			success: function(r){
				error_array 	= 	JSON.stringify(r);
				data			=	JSON.parse(error_array);
				if(data['success'] == 1) {
					window.location.href	 =	"{{ URL('aquaradiuspnlx/dashboard') }}";
				}else if(data['success'] == 2){
					$(".notification").removeClass("alert-success");
					$(".notification").addClass("alert-danger");
					$(".notification").show();
					goToByScroll('notification',"two_step_form");
					$(".notification").html(data['message']);
				}else{
					$.each(data['errors'],function(index,html){
						$("input[name = "+index+"]").next().addClass('error');
						$("input[name = "+index+"]").next().html(html);
					});
				}
				$('#loader_img').hide();
			}
		});
	}
	$(document).ready(function () {
		$('#two_step_form').each(function(){
			$(this).find('input').keypress(function(e){
			   if(e.which == 10 || e.which == 13) {
					verifyCode();
					return false;
				}
			});
        });
	});
</script>