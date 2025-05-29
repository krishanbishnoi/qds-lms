@extends('admin.layouts.default')

@section('content')
<style>
	.mws-form .mws-form-row .mws-form-item .small.radio_small,.mws-form .mws-form-row .mws-form-item .small.checked_div {
		margin-left: 1px !important;
		margin-right: 5px !important;
		width: 0 !important;
	}
	.mws-form-item > label {
		padding-right: 10px;
	}
	
	.textarea_resize {
		resize: vertical;
	}
	
</style>
	<!-- 
<section class="content-header">
	 <h1>
		@if($prefix == 'Social')
			{{ trans("Social Media") }}
		@else
			{{ $prefix }} {{ trans("messages.settings.setting") }}
		@endif
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li>
		@if($prefix == 'Social')
			{{ trans("Social Media") }}
		@else
			{{ $prefix }} {{ trans("messages.settings.setting") }}
		@endif
		</li>
	</ol> 
</section>-->

{{ Form::open(['role' => 'form','route' => [$modelName.'.prefix',$prefix],'class' => 'mws-form','id'=>'settingsForm']) }}
<div class="content-wrapper">
 <div class="box-body ">
	<div class="row">
		<div class="col-md-6">	
			<?php 
				if(!empty($result)){
					 $i = 0;
						$half = floor(count($result)/2);
					foreach ($result AS $setting) {
						$text_extention 	= 	'';
						$key				= 	$setting['key'];
						$keyE 				= 	explode('.', $key);
						$keyTitle 			= 	$keyE['1'];
				
						$label = $keyTitle;
						if ($setting['title'] != null) {
							$label = $setting['title'];
						}

						$inputType = 'text';
						if ($setting['input_type'] != null) {
							$inputType = $setting['input_type'];
						} ?>
						
						{{ Form::hidden("Setting[$i]['type']",$inputType) }}
						{{ Form::hidden("Setting[$i]['id']",$setting['id']) }}
						{{ Form::hidden("Setting[$i]['key']",$setting['key']) }}
						<?php 
							
							switch($inputType){
								case 'checkbox':
						?>	
						
						<div class="form-group">
							<label class="mws-form-label" style="width:300px;"><?php echo $label; ?></label>
							<div class="mws-form-item clearfix">
								<ul class="mws-form-list inline">
									<?php 	
										$checked = ($setting['value'] == 1 )? true: false;
										$val	 = (!empty($setting['value'])) ? $setting['value'] : 0;
									?>
									{{ Form::checkbox("Setting[$i]['value']",$val,$checked) }} 
								</ul>
							</div>
						</div>
						
						<?php
								break;	
								
								case 'text':
								
						?>
						
						<div class="form-group">
							<label class="mws-form-label"  style="width:300px;"><?php echo $label; ?></label>
							{{ Form::{$inputType}("Setting[$i]['value']",$setting['value'], ['class' => 'form-control valid','id'=>$key]) }} 
							<div class="error-message help-inline"></div>
						</div>
						<?php
							break;	
							case 'textarea':	
						?>
						
						<div class="form-group">
							<label class="mws-form-label"  style="width:300px;"><?php echo $label; ?></label>
							{{ Form::textarea("Setting[$i]['value']",$setting['value'], ['class' => 'form-control textarea_resize',"rows"=>3,"cols"=>3 ,'id'=>$key] ) }} 
						</div>
						<?php	
							break;
								
						}
						if($i == $half) echo '</div><div class="col-md-6">';
						$i++;
							
					}
				}
			?>	
			</div> 
		</div>
		<div class="mws-button-row">
			<input type="button" onclick="submit_form();" value="{{ trans('Save') }}" class="btn btn-danger">
			
			<a href="{{route($modelName.'.prefix',$prefix)}}" class="btn btn-primary"><i class=\"icon-refresh\"></i> {{ trans('Reset')  }}</a>
		</div>
</div>
</section>
{{ Form::close() }} 


<script type="text/javascript">
	function isEmail(email) {
	  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	  return regex.test(email);
	}

	function verifyPhoneNumber(phone_number){
	  var regex = new RegExp('^([0|+[0-9]{1,5})?([7-9][0-9]{9})$');
	  return regex.test(phone_number);
	}

	// function verifyPageNumber(records_per_page){
	//   var regex = new RegExp("^[1-9]{1,1000}$");
	//   return regex.test(records_per_page);
	// }


	function verifyTextField(address){

		var regex = /^([a-zA-Z0-9!*@#$©[<>{}?&()\\-`-+.:;+,~'/\"]+\s)*[a-zA-Z0-9!@#$©[*&{}<>?()\\-`-+.+,;:'/\"]+$/;
	  return regex.test(address);
	}
	


	

	function validateUrl(twitter){
	    var urlregex = new RegExp(
	          "^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.){1}([0-9A-Za-z]+\.)");
	    return urlregex.test(twitter);
	}

	// function emojisNotAllowed(youtube){
	//     var urlregex = ^(?=.{1,55}$)([a-zA-Z0-9,\/@]{1})+([a-zA-Z0-9,\/@\s]{0,1})+([a-zA-Z0-9,\/@)]{1,55})$;
	//     return urlregex.test(youtube);
	// }

	// function validateUrl(facebook){
	//     var urlregex = new RegExp(
	//           "^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.){1}([0-9A-Za-z]+\.)");
	//     return urlregex.test(facebook);
	// }

	// function validateUrl(google){
	//     var urlregex = new RegExp(
	//           "^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.){1}([0-9A-Za-z]+\.)");
	//     return urlregex.test(google);
	// }

	// function validateUrl(instagram){
	//     var urlregex = new RegExp(
	//           "^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.){1}([0-9A-Za-z]+\.)");
	//     return urlregex.test(instagram);
	// }


	var empty_msg				=	'This field is required';
	var numuric_empty_msg		=	'This field is allow only numuric value';
	var image_validation		=	'Please upload a valid image. Valid extensions are jpg, jpeg, png, jpeg';
	var allowedExtensions		=	['gif','GIF','jpeg','JPEG','PNG','png','jpg','JPG'];
	function submit_form() {
		var $inputs = $('.mws-form :input.valid');
		var error  =	0;
		$inputs.each(function() { 
			if($(this).val().trim() == '' ){
				$(this).next().html(empty_msg);
				error	=	1;
			}else {
				if($(this).attr('id') == 'Site.email' ){
					if(!isEmail($(this).val().trim())) { 
						$(this).next().html("Please enter a valid email");
						error	=	1;
					}else {
						$(this).next().html("");
					}
				}else if($(this).attr('id') == 'Contact.email' ){
					if(!isEmail($(this).val().trim())) { 
						$(this).next().html("Please enter a valid email");
						error	=	1;
					}else {
						$(this).next().html("");
					}
				}else if($(this).attr('id') == 'Contact.address' ){
					if(!verifyTextField($(this).val().trim())) { 
						$(this).next().html("Please enter a valid format");
						error	=	1;
					}else {
						$(this).next().html("");
					}
				}else if($(this).attr('id') == 'Site.copyright_text' ){
					if(!verifyTextField($(this).val().trim())) { 
						$(this).next().html("Please enter a valid format");
						error	=	1;
					}else {
						$(this).next().html("");
					}
				}else if($(this).attr('id') == 'Site.currencyCode' ){
					if(!verifyTextField($(this).val().trim())) { 
						$(this).next().html("Please enter a valid format");
						error	=	1;
					}else {
						$(this).next().html("");
					}
				}else if($(this).attr('id') == 'Site.title' ){
					if(!verifyTextField($(this).val().trim())) { 
						$(this).next().html("Please enter a valid format");
						error	=	1;
					}else {
						$(this).next().html("");
					}
				}else if($(this).attr('id') == 'Contact.phone_number' ){
					if(!verifyPhoneNumber($(this).val().trim())) { 
						$(this).next().html("Please enter valid mobile number.");
						error	=	1;
					}else {
						$(this).next().html("");
					}
				}else if($(this).attr('id') == 'Reading.date_time_format' ){
					if(!verifyTextField($(this).val().trim())) { 
						$(this).next().html("Please enter a valid format");
						error	=	1;
					}else {
						$(this).next().html("");
					}
				}else if($(this).attr('id') == 'Reading.date_format' ){
					if(!verifyTextField($(this).val().trim())) { 
						$(this).next().html("Please enter a valid format");
						error	=	1;
					}else {
						$(this).next().html("");
					}
					
				}else if($(this).attr('id') == 'Contact.fax' ){
					if(!verifyPhoneNumber($(this).val().trim())) { 
						$(this).next().html("Please enter valid mobile number.");
						error	=	1;
					}else {
						$(this).next().html("");
					}
				}else if($(this).attr('id') == 'Social.twitter' ){
					if(!validateUrl($(this).val().trim())) { 
						$(this).next().html("Please enter valid url.");
						error	=	1;
					}else if(!verifyTextField($(this).val().trim())) { 
						$(this).next().html("Please enter a valid format");
						error	=	1;
					}else {
						$(this).next().html("");
					}
				}else if($(this).attr('id') == 'Social.youtube' ){
					if(!validateUrl($(this).val().trim())) { 
						$(this).next().html("Please enter valid url.");
						error	=	1;
					}else if(!verifyTextField($(this).val().trim())) { 
						$(this).next().html("Please enter a valid format");
						error	=	1;

					}else {
						$(this).next().html("");
					}
				}else if($(this).attr('id') == 'Social.facebook' ){
					if(!validateUrl($(this).val().trim())) { 
						$(this).next().html("Please enter valid url.");
						error	=	1;
					}else if(!verifyTextField($(this).val().trim())) { 
						$(this).next().html("Please enter a valid format");
						error	=	1;
					}else {
						$(this).next().html("");
					}
				}else if($(this).attr('id') == 'Social.google' ){
					if(!validateUrl($(this).val().trim())) { 
						$(this).next().html("Please enter valid url.");
						error	=	1;
					}else if(!verifyTextField($(this).val().trim())) { 
						$(this).next().html("Please enter a valid format");
						error	=	1;
					}else {
						$(this).next().html("");
					}
				}else if($(this).attr('id') == 'Social.instagram' ){
					if(!validateUrl($(this).val().trim())) { 
						$(this).next().html("Please enter valid url.");
						error	=	1;
					}else if(!verifyTextField($(this).val().trim())) { 
						$(this).next().html("Please enter a valid format");
						error	=	1;
					}else {
						$(this).next().html("");
					}
				}else {
					$(this).next().html("");
				}
			}
		});
		if(error == 0){
			$('.mws-form').submit();
		}
	}
	$('#settingsForm').each(function() {
		$(this).find('input').keypress(function(e) {
           if(e.which == 10 || e.which == 13) {
				submit_form();
				return false;
            }
        });
	});
</script>
@stop
