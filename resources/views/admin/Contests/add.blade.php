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
					{{ Form::open(['role' => 'form','route' => "$modelName.add",'class' => 'mws-form','enctype'=>'multipart/form-data','files' => true,"autocomplete"=>"off"]) }}
					<div class="form-group <?php echo ($errors->first('category_id')?'has-error':''); ?>">
						<div class="mws-form-row">
							{!! Html::decode( Form::label('category_id', trans("Category").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::select('category_id',$categories,'', ['class' => 'form-control small' ,'placeholder'=>'Please select category']) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('category_id'); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group <?php echo ($errors->first('name')?'has-error':''); ?>">
						<div class="mws-form-row">
							{!! Html::decode( Form::label('name', trans("Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::text('name','', ['class' => 'form-control small']) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('name'); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group <?php echo ($errors->first('entry_fee')?'has-error':''); ?>">
						<div class="mws-form-row">
							{!! Html::decode( Form::label('entry_fee', trans("Entry Fee").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::text('entry_fee','', ['class' => 'form-control small','maxlength'=>"10",'oninput'=>"this.value=this.value.replace(/[^0-9]/g,'');"]) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('entry_fee'); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group <?php echo ($errors->first('minimum_users')?'has-error':''); ?>">
						<div class="mws-form-row">
							{!! Html::decode( Form::label('minimum_users', trans("Minimum Users").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::text('minimum_users','', ['class' => 'form-control small','maxlength'=>"10",'id'=>'minimum_users','oninput'=>"this.value=this.value.replace(/[^0-9]/g,'');"]) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('minimum_users'); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group <?php echo ($errors->first('maximum_users')?'has-error':''); ?>">
						<div class="mws-form-row">
							{!! Html::decode( Form::label('maximum_users', trans("Maximum Users").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::text('maximum_users','', ['class' => 'form-control small','maxlength'=>"10",'id'=>'maximum_users','oninput'=>"this.value=this.value.replace(/[^0-9]/g,'');"]) }}
								<div class="error-message help-inline maximum_users">
									<?php echo $errors->first('maximum_users'); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group <?php echo ($errors->first('budget')?'has-error':''); ?>">
						<div class="mws-form-row">
							{!! Html::decode( Form::label('budget', trans("Budget").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::text('budget','', ['class' => 'form-control small','maxlength'=>"10",'oninput'=>"this.value=this.value.replace(/[^0-9]/g,'');"]) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('budget'); ?>
								</div>
							</div>
						</div>
					</div>

					{{-- <div class="form-group <?php echo ($errors->first('number_of_stock')?'has-error':''); ?>">
						<div class="mws-form-row">
							{!! Html::decode( Form::label('number_of_stock', trans("Number of Stock/Share").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::text('number_of_stock','', ['class' => 'form-control small','id'=>'number_of_stock','maxlength'=>"10",'oninput'=>"this.value=this.value.replace(/[^0-9]/g,'');"]) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('number_of_stock'); ?>
								</div>
							</div>
						</div>
					</div> --}}
					<div class="form-group <?php echo ($errors->first('publish_date_time')?'has-error':''); ?>" id="publish_date">
						<div class="mws-form-row">
							{!! Html::decode( Form::label('publish_date_time', trans("Publish Date").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::text('publish_date_time','', ['class' => 'form-control small' ,'id' => 'publish_date_time']) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('publish_date_time'); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group <?php echo ($errors->first('registration_start_date_time')?'has-error':''); ?>" id="live_date" >
						<div class="mws-form-row">
							{!! Html::decode( Form::label('registration_start_date_time', trans("Registration Start Date").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::text('registration_start_date_time','', ['class' => 'form-control small', 'id' => 'registration_start_date_time' ]) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('registration_start_date_time'); ?>
								</div>
							</div>
						</div>
					</div>

					{{-- <div class="form-group <?php echo ($errors->first('registration_close_date_time')?'has-error':''); ?>" id="end_date" >
						<div class="mws-form-row">
							{!! Html::decode( Form::label('registration_close_date_time', trans("Registration Close Date").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::text('registration_close_date_time','', ['class' => 'form-control small' ,'id' => 'registration_close_date_time']) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('registration_close_date_time'); ?>
								</div>
							</div>
						</div>
					</div> --}}

					{!! Html::decode( Form::label('paid_position', trans("Paid Positions").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class=" project_detailSection">
						<div class="projectDetailsInnerSection ace_left_sec" >
							<table  >
								<tr >
									<td width="300px">
										<div class="form-group <?php echo ($errors->first('position')) ? 'has-error' : ''; ?>">
											{!! Html::decode( Form::label('position', trans("position").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
											<div class="mws-form-item">
												{{ Form::text('data[1][position]',1,['class'=>'form-control','readonly'=>'readonly','oninput'=>"this.value=this.value.replace(/[^0-9]/g,'');"]) }}
												<div class="error-message help-inline">
													<?php echo $errors->first('position'); ?>
												</div>
											</div>
										</div>
									</td>
									<td width="300px">
										<div class="form-group <?php echo ($errors->first('amount')) ? 'has-error' : ''; ?>">
											{!! Html::decode( Form::label('amount', trans("Amount").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
											<div class="mws-form-item">
												{{ Form::text('data[1][amount]','',['class'=>'form-control','maxlength'=>"10", 'oninput'=>"this.value=this.value.replace(/[^0-9]/g,'');"]) }}
												<div class="error-message help-inline">
													<?php echo $errors->first('amount'); ?>
												</div>
											</div>
										</div>
									</td>
									<td style="padding-top: 23px;" width="300px">
										<div class="form-group">
											<input type="hidden" name="count" value="1" id="add_more_count">
											<a href="javascript:void(0);" id="addMore" class="btn btn-primary add_new_btn add_more_new_supp" value="Add More">Add More</a>
										</div>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="form-group <?php echo ($errors->first('image')?'has-error':''); ?>">
						<div class="mws-form-row">
							{!! HTML::decode( Form::label('image', trans("Icon").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::file('image', ['class' => '' ,'accept'=>"image/*" ,'data-type'=>'image']) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('image'); ?>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group <?php echo ($errors->first('contest_stocks')?'has-error':''); ?>" >
						<div class="mws-form-row ">
							{!! Html::decode( Form::label('contest_stocks', trans("Select Stocks").'<span class="requireRed"></span>',['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::select("contest_stocks[]",$contest_stocks,'', ['class' => 'form-control','id'=>'contest_stocks','multiple']) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('contest_stocks'); ?>
								</div>
							</div>
						</div>
					</div>

					{{--
					<div class="form-group">
						<button id="addMoreGame" class="btn btn-primary add_new_btn add_more_new_supp" value="Add More Game">Add More Game</button>
					</div>
					--}}
					<div class="form-group <?php echo $errors->first('description') ? 'has-error' : ''; ?>">
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
					<input type="submit" value="{{ trans('Save') }}" class="btn btn-danger">
					<a href="{{ route($modelName.'.add')}}" class="btn btn-primary reset_form"><i class=\"icon-refresh\"></i> {{ trans('Clear') }}</a>
					<a href="{{ route($modelName.'.index') }}" class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans('Cancel')  }}</a>
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



	jQuery(document).ready(function(){
		$("#maximum_users").keyup(function(){
			var maximum_users = 	$('#maximum_users').val();
			var minimum_users = 	$('#minimum_users').val()

            if (parseInt(minimum_users) >= parseInt(maximum_users)){
				$(".maximum_users").html('Maximum users should be greater than minimum users');
			}else{
				$(".maximum_users").html('');
			}
		});
	});


		// 	$('#datetimepicker4').datetimepicker({
		// 	onClose: function (dateText, inst) {
		// 		alert("date chosen");
		// 	}
		// });


	$(function () {
	   $('#publish_date_time').datetimepicker({ format: 'YYYY-MM-DD HH:mm:ss',icons: {
			time: "fa fa-clock-o",
			date: "fa fa-calendar",
			up: "fa fa-arrow-up",
			down: "fa fa-arrow-down",
			previous: "fa fa-chevron-left",
			next: "fa fa-chevron-right",
			today: "fa fa-clock-o",
			clear: "fa fa-trash-o"
			},
			useCurrent: false,
			minDate:new Date()
		});

		$('#registration_start_date_time').datetimepicker({ format: 'YYYY-MM-DD 15:00:00',icons: {
			time: "fa fa-clock-o",
			date: "fa fa-calendar",
			up: "fa fa-arrow-up",
			down: "fa fa-arrow-down",
			previous: "fa fa-chevron-left",
			next: "fa fa-chevron-right",
			today: "fa fa-clock-o",
			clear: "fa fa-trash-o"
			},
			useCurrent: false
		});

		// $('#registration_close_date_time').datetimepicker({ format: 'YYYY-MM-DD 08:30:00',icons: {
		// 	time: "fa fa-clock-o",
		// 	date: "fa fa-calendar",
		// 	up: "fa fa-arrow-up",
		// 	down: "fa fa-arrow-down",
		// 	previous: "fa fa-chevron-left",
		// 	next: "fa fa-chevron-right",
		// 	today: "fa fa-clock-o",
		// 	clear: "fa fa-trash-o"
		// 	},
		// 	useCurrent: false
		// });


		$("#publish_date_time").on("dp.change", function (e) {

			$('#registration_start_date_time').data("DateTimePicker").minDate(e.date);
		});
		$("#registration_start_date_time").on("dp.change", function (e) {
			$('#publish_date_time').data("DateTimePicker").minDate(e.date);
		});

		///$("#registration_start_date_time").on("dp.change", function (e) {


		// 	$('#registration_close_date_time').data("DateTimePicker").minDate(e.date);
		// });

		// $("#registration_close_date_time").on("dp.change", function (e) {
		// 	$('#registration_start_date_time').data("DateTimePicker").maxDate(e.date);
		// });
		// $("#live_date_time").on("dp.change", function (e) {
		// 	$('#publish_date_time').data("DateTimePicker").maxDate(e.date);
		// });
	});


	$('#addMore').click(function(){
		var count = $('#add_more_count').val();
		var new_count = parseInt(count)+parseInt(1);
		$.ajax({
			url: '{{ URL("admin/contests/add-more-payout") }}',
			type:'post',

			data: {
				"_token": "{{ csrf_token() }}",
				"offset": new_count
				},
			async : false,
			success: function(r){
				if(r ) {
					$('#add_more_count').val(new_count);
					$('.project_detailSection').append(r);
				}else{
					alert('There is an error please try again.')
				}
				$('#loader_img').hide();
			}
		});
	});

	function removeSection(id) {
		bootbox.confirm("Are you sure want to remove this?",
		function(result){
			if(result){
				$('.projectDetailsInnerSection_'+id).remove();
			}
		});
	}


</script>
@stop
