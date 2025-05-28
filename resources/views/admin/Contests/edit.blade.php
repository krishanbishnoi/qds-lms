@extends('admin.layouts.default')
@section('content')
<!-- JS & CSS library of MultiSelect plugin -->
<script src="https://phpcoder.tech/multiselect/js/jquery.multiselect.js"></script>
<link rel="stylesheet" href="https://phpcoder.tech/multiselect/css/jquery.multiselect.css">
<style>
	.no-click {
	pointer-events: none;
	}
</style>
<div class="content-wrapper">
		<div class="page-header">
			<h2 class="page-title">Edit {{ $sectionNameSingular }}</h2>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<!-- <li class="breadcrumb-item"><a href="#">Forms</a></li> -->
					<!-- <li class="breadcrumb-item active" aria-current="page">Forget Password</li> -->
					<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
						class=" fa fa-dashboard"></i>Dashboard</a></li>
					<li class="breadcrumb-item"><a href="{{ route($modelName . '.index') }}">{{ $sectionName }}</a></li>
					<li class="breadcrumb-item active">Edit {{ $sectionNameSingular }}</li>
				</ol>
			</nav>
		</div>
	<div class="row">
		<div class="col-md-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">

					{{ Form::open(['role' => 'form', 'url' => route("$modelName.edit", $model->id), 'class' => 'mws-form', 'files' => true, 'autocomplete' => 'off']) }}

					<div class="form-group <?php echo ($errors->first('category_id')?'has-error':''); ?>">
						<div class="mws-form-row">
							{!! Html::decode( Form::label('category_id', trans("Category").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::select('category_id',$categories,$model->category_id, ['class' => 'form-control ' ,'placeholder'=>'Please select category']) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('category_id'); ?>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group <?php echo $errors->first('id') ? 'has-error' : ''; ?>">
						<div class="mws-form-row">
							{!! Html::decode(Form::label('id', trans('Unique Id') . '<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::text('id', $model->id, ['class' => 'form-control small no-click', 'readonly' => 'true']) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('id'); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group <?php echo $errors->first('name') ? 'has-error' : ''; ?>">
						<div class="mws-form-row">
							{!! Html::decode(Form::label('name', trans('Name') . '<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::text('name', $model->name, ['class' => 'form-control small']) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('name'); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group <?php echo $errors->first('entry_fee') ? 'has-error' : ''; ?>">
						<div class="mws-form-row">
							{!! Html::decode(Form::label('entry_fee', trans('Entry Fee') . '<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::text('entry_fee', $model->entry_fee, ['class' => 'form-control small no-click', 'maxlength' => '10', 'readonly' => 'readonly', 'oninput' => "this.value=this.value.replace(/[^0-9]/g,'');"]) }}
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
								{{ Form::text('minimum_users',$model->minimum_users, ['class' => 'form-control small','maxlength'=>"10",'id'=>'minimum_users','oninput'=>"this.value=this.value.replace(/[^0-9]/g,'');"]) }}
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
								{{ Form::text('maximum_users',$model->maximum_users, ['class' => 'form-control small','maxlength'=>"10",'id'=>'maximum_users','oninput'=>"this.value=this.value.replace(/[^0-9]/g,'');"]) }}
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
								{{ Form::text('budget',$model->budget, ['class' => 'form-control small','maxlength'=>"10",'oninput'=>"this.value=this.value.replace(/[^0-9]/g,'');"]) }}
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
								{{ Form::text('number_of_stock',$model->number_of_stock, ['class' => 'form-control small','id'=>'number_of_stock','maxlength'=>"10",'oninput'=>"this.value=this.value.replace(/[^0-9]/g,'');"]) }}
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
								{{ Form::text('publish_date_time',$model->publish_date_time, ['class' => 'form-control small' ,'id' => 'publish_date_time']) }}
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
								{{ Form::text('registration_start_date_time',$model->registration_start_date_time, ['class' => 'form-control small', 'id' => 'registration_start_date_time' ]) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('registration_start_date_time'); ?>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group <?php echo ($errors->first('registration_close_date_time')?'has-error':''); ?>" id="end_date" >
						<div class="mws-form-row">
							{!! Html::decode( Form::label('registration_close_date_time', trans("Registration Close Date").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::text('registration_close_date_time',$model->registration_close_date_time, ['class' => 'form-control small' ,'id' => 'registration_close_date_time','readonly'=>'readonly']) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('registration_close_date_time'); ?>
								</div>
							</div>
						</div>
					</div>
					{{--
					<div class="form-group <?php echo $errors->first('paid_position') ? 'has-error' : ''; ?>">
						<div class="mws-form-row">
							{!! Html::decode( Form::label('paid_position', trans("Position Paid").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::text('paid_position',$model->paid_position, ['class' => 'form-control small','oninput'=>"this.value=this.value.replace(/[^0-9]/g,'');"]) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('paid_position'); ?>
								</div>
							</div>
						</div>
					</div>
					--}}
					{!! Html::decode(Form::label('paid_position', trans('Paid Positions') . '<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="project_detailSection">
						<?php $i = 0; ?>
						@if (!$attribute->isEmpty())
						@foreach ($attribute as $order)
						<?php $i++; ?>
						<div class="projectDetailsInnerSection_<?php echo $i; ?> ace_left_sec">
							<table>
								<tr>
									<td width="300px">
										<div class="form-group <?php echo $errors->first('position') ? 'has-error' : ''; ?>">
											{!! Html::decode(Form::label('position', trans('position') . '<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
											<div class="mws-form-item">
												{{ Form::text('data[' . $i . '][position]', isset($order->position) ? $order->position : '', ['class' => 'form-control', 'oninput' => "this.value=this.value.replace(/[^0-9]/g,'');", 'readonly' => 'readonly']) }}
												<div class="error-message help-inline">
													<?php echo $errors->first('position'); ?>
												</div>
											</div>
										</div>
									</td>
									<td width="300px">
										<div class="form-group <?php echo $errors->first('amount') ? 'has-error' : ''; ?>">
											{!! HTML::decode(Form::label('amount', trans('amount') . '<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
											<div class="mws-form-item">
												{{ Form::text('data[' . $i . '][amount]', isset($order->amount) ? $order->amount : '', ['class' => 'form-control', 'maxlength' => '10', 'oninput' => "this.value=this.value.replace(/[^0-9]/g,'');", 'readonly' => 'readonly']) }}
												<div class="error-message help-inline">
													<?php echo $errors->first('amount'); ?>
												</div>
											</div>
										</div>
									</td>
									{{-- @if ($i == 1)
									<td style=" padding-top: 20px;" width="300px">
										<div class="form-group">
											<a href="javascript:void(0);" id="addMore" class="btn btn-primary add_new_btn" value="Add More">Add More</a>
										</div>
									</td>
									@else
									<td style=" padding-top: 20px;" width="300px">
										<div class="form-group">
											<input type="hidden" name="data[<?php echo $i; ?>][entryID]" id="entryID_<?php echo $i; ?>" value="{{$order->id}}">
											<?php if($i !=1){?>
											<a href="javascript:void(0);" class="btn btn-danger position-right" onclick="removeTableEntry('<?php echo $i; ?>')" id="remove_'+new_count+'">Remove</a>
											<?php } ?>
									</td>
									@endif --}}
								</tr>
							</table>
							</div>
							@endforeach
							<input type="hidden" name="count" value="<?php echo $i; ?>" id="add_more_count">
							@else
							<div class="projectDetailsInnerSection">
								<table>
									<tr>
										<td width="300px">
											<div class="form-group <?php echo $errors->first('position') ? 'has-error' : ''; ?>">
												{!! Html::decode(Form::label('position', trans('position') . '<span class="requireRed">  </span>', ['class' => 'mws-form-label', 'oninput' => "this.value=this.value.replace(/[^0-9]/g,'');"])) !!}
												<div class="mws-form-item">
													{{ Form::text('data[1][position]', '', ['class' => 'form-control']) }}
													<div class="error-message help-inline">
														<?php echo $errors->first('position'); ?>
													</div>
												</div>
											</div>
										</td>
										<td width="300px">
											<div class="form-group <?php echo $errors->first('amount') ? 'has-error' : ''; ?>">
												{!! HTML::decode(Form::label('amount', trans('amount') . '<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
												<div class="mws-form-item">
													{{ Form::text('data[1][amount]', '', ['class' => 'form-control', 'maxlength' => '10', 'oninput' => "this.value=this.value.replace(/[^0-9]/g,'');"]) }}
													<div class="error-message help-inline">
														<?php echo $errors->first('amount'); ?>
													</div>
												</div>
											</div>
										</td>
										{{--
										<td style=" padding-top: 23px;" width="300px">
											<div class="form-group">
												<input type="hidden" name="count" value="1" id="add_more_count">
												<a href="javascript:void(0);" id="addMore" class="btn btn-primary add_new_btn add_more_new_supp" value="Add More">Add More</a>
											</div>
										</td>
										--}}
									</tr>
								</table>
							</div>
							<input type="hidden" name="count" value="1" id="add_more_count">
							@endif
						</div>
						<div class="form-group <?php echo $errors->first('image') ? 'has-error' : ''; ?>">
							<div class="mws-form-row">
								{!! HTML::decode(Form::label('image', trans('Icon') . '<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
								<div class="mws-form-item">
									{{ Form::file('image', ['class' => '', 'accept' => 'image/*', 'data-type' => 'image']) }}
									<div class="error-message help-inline">
										<?php echo $errors->first('image'); ?>
									</div>
									@if ($model->image != '')
									<br />
									<img height="50" width="50" src="{{ $model->image }}" />
									@endif
								</div>
							</div>
						</div>

						<div class="form-group <?php echo $errors->first('contest_stocks') ? 'has-error' : ''; ?>" >
							<div class="mws-form-row " >
								{!! Html::decode( Form::label('contest_stocks', trans("Select Stocks").'<span class="requireRed"></span>',['class' => 'mws-form-label'])) !!}
								<div class="mws-form-item" id="refresh">
									{{ Form::select("contest_stocks[]",$contest_stocks,$selected_stock, ['min'=>'2','class' => 'form-control selectpicker','id'=>'contest_stocks','multiple']) }}
									<div class="error-message help-inline">
										<?php echo $errors->first('contest_stocks'); ?>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group <?php echo $errors->first('description') ? 'has-error' : ''; ?>">
							{!! Html::decode(Form::label('description', trans('Description') . '<span class="requireRed"> *  </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::textarea('description', $model->description, ['class' => 'form-control textarea_resize', 'id' => 'description', 'rows' => 3, 'cols' => 3]) }}
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
						<input type="submit" value="{{ trans('Save') }}" class="btn btn-danger" id="submit_form">
						<a href='{{ route("$modelName.edit", $model->id) }}' class="btn btn-primary reset_form"><i
							class=\"icon-refresh\"></i> {{ trans('Clear') }}</a>
						<a href="{{ route($modelName . '.index') }}" class="btn btn-info"><i
							class=\"icon-refresh\"></i>
						{{ trans('Cancel') }}</a>
					</div>
				</div>
			</div>
		<div>
	<div>
<div>
	<script>
		jQuery('#contest_stocks').multiselect({
	//	columns: 1,
		placeholder: 'Select Stock',
		search: true

	});
		</script>
<script>
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
			// minDate:new Date()
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

		// $("#registration_start_date_time").on("dp.change", function (e) {
		// 	$('#registration_close_date_time').data("DateTimePicker").minDate(e.date);
		// });

		// $("#registration_close_date_time").on("dp.change", function (e) {
		// 	$('#registration_start_date_time').data("DateTimePicker").maxDate(e.date);
		// });
		// $("#registration_start_date_time").on("dp.change", function (e) {
		// 	$('#publish_date_time').data("DateTimePicker").maxDate(e.date);
		// });
	});


	jQuery(document).ready(function(){
		$("#maximum_users").keyup(function(){
			var maximum_users = 	$('#maximum_users').val();
			var minimum_users = 	$('#minimum_users').val();


			if (parseInt(minimum_users) >= parseInt(maximum_users)){
				$(".maximum_users").html('Maximum users should be greater than minimum users');
			}else{
				$(".maximum_users").html('');
			}
		});
	});
</script>
	<script type="text/javascript">
		$('#addMore').click(function() {
	    var count = $('#add_more_count').val();
	    var new_count = parseInt(count) + parseInt(1);
	    $.ajax({
	        url: '{{ URL('admin/streeks/add-more-payout') }}',
	        type: 'post',
	        data: {
	            "_token": "{{ csrf_token() }}",
	            "offset": new_count
	        },

	        async: false,
	        success: function(r) {
	            if (r) {
	                $('#add_more_count').val(new_count);
	                $('.project_detailSection').append(r);
	            } else {
	                alert('There is an error please try again.')
	            }
	            $('#loader_img').hide();
	        }
	    });
	});

	function removeSection(id) {
	    bootbox.confirm("Are you sure want to remove this?",
	        function(result) {
	            if (result) {
	                $('.projectDetailsInnerSection_' + id).remove();
	            }
	        });
	}



	function removeTableEntry(id) {
	    bootbox.confirm("Are you sure want to remove this?",
	        function(result) {
	            if (result) {
	                $('#loader_img').show();
	                var entID = $('#entryID_' + id).val();
	                $.ajax({
	                    url: '{{ URL('admin/streeks/delete-more-payout') }}',
	                    type: 'post',
	                    data: {
	                        'id': entID
	                    },
	                    data: {
	                        "_token": "{{ csrf_token() }}",
	                        'id': entID
	                    },
	                    async: false,
	                    success: function(r) {
	                        if (r == 1) {
	                            $('.projectDetailsInnerSection_' + id).remove();
	                        } else {
	                            alert('There is an error please try again.')
	                        }
	                        $('#loader_img').hide();
	                    }
	                });
	            }
	        });
	}

	$("#addMoreGame").click(function(e) {
	    e.preventDefault();
	    $('#addGamemodel').modal('show');
	    $("#addGameform").trigger("reset");

	})

	function storeGame() {
	    $('.help-inline').removeClass('error');
	    $('.help-inline').html('');
	    var dataString = $("#addGameform").serialize();
	    $.ajax({
	        url: '{{ url('admin/games/store-new-game') }}',
	        type: 'post',
	        data: dataString,
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
	        success: function(r) {
	            error_array = JSON.stringify(r);
	            data = JSON.parse(error_array);
	            if (data['success'] == 1) {
	                $.each(data['errors'], function(index, html) {
	                    $("input[name = " + index + "]").next().addClass('error');
	                    $("input[name = " + index + "]").next().html(html);
	                });
	            } else {
	                myArr = JSON.parse(r);

	                // $('.selectpicker').append('<option value="'+myArr.game_id+'">'+myArr.game_name+'</option>');

	                //  $('.selectpicker').selectpicker("refresh");
	                // $("#refresh").load(location.href+" #refresh>*","");

	                // $('.selectpicker').selectpicker('refresh');
	                // $("#addGameform").trigger("reset" );
	                //  $('#addGamemodel').modal('hide');
	                location.reload();
	                // toastr.success('Game has been added successfully');

	            }
	        }
	    });

	}




	$(function() {
	    $('#start_time').datetimepicker({
	        format: 'YYYY-MM-DD HH:mm:ss',
	    });
	    $('#end_time').datetimepicker({
	        format: 'YYYY-MM-DD HH:mm:ss',
	        useCurrent: false
	    });
	    $("#start_time").on("dp.change", function(e) {
	        $('#end_time').data("DateTimePicker").minDate(e.date);
	    });
	    $("#end_time").on("dp.change", function(e) {
	        $('#start_time').data("DateTimePicker").maxDate(e.date);
	    });
	});
</script>
@stop
