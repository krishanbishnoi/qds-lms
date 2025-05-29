@extends('admin.layouts.default')
@section('content')
<script src="https://cdn.ckeditor.com/4.15.0/standard-all/ckeditor.js"></script>
<div class="content-wrapper">
    <div class="page-header">
        <h2 class="page-title">Edit {{ $sectionNameSingular }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <!-- <li class="breadcrumb-item"><a href="#">Forms</a></li> -->
                <!-- <li class="breadcrumb-item active" aria-current="page">Forget Password</li> -->

                <li class="breadcrumb-item"><a href="{{ route('dashboard')}}"><i class=" fa fa-dashboard"></i>Dashboard</a></li>
                <li class="breadcrumb-item" ><a href="{{ route($modelName.'.index')}}">{{ $sectionName }}</a></li>
                <li class="breadcrumb-item active" >Edit {{ $sectionNameSingular }}</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                {{ Form::open(['role' => 'form','url' => 'admin/cms-manager/edit-cms/'.$adminCmspage->id,'files' => true,'class' => 'mws-form']) }}
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
								{{ Form::text('title',$adminCmspage->title, ['class' => 'form-control']) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('title'); ?>
								</div>
							</div>
						</div>
						<div class="form-group <?php echo ($errors->first('en_name')?'has-error':''); ?>">
							<div class="mws-form-row">
								{!! Html::decode( Form::label('name',trans("Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
								<div class="mws-form-item">
									{{ Form::text('name',$adminCmspage->name, ['class' => 'form-control']) }}
									<div class="error-message help-inline">
										<?php echo $errors->first('name'); ?>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group <?php echo $errors->first('description') ? 'has-error' : ''; ?>">
							{!! Html::decode( Form::label('description',trans("Description").'<span class="requireRed"> *  </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::textarea("description",$adminCmspage->description, ['class' => 'form-control textarea_resize','id' => 'description' ,"rows"=>3,"cols"=>3]) }}
								<span class="error-message descriptionTypeError help-inline">
								<?php echo $errors->first('description') ? $errors->first('description') : ''; ?>
								</span>
							</div>
							<script>
								var description =
								CKEDITOR.replace('description', {
								extraAllowedContent: 'div',
								height: 300,
								filebrowserUploadUrl : '<?php echo URL::to('base/uploder'); ?>',
										// filebrowserImageWindowWidth : '640',
										// filebrowserImageWindowHeight : '480',
										enterMode : CKEDITOR.ENTER_BR
								});

							</script>
						</div>
						<div class="form-group <?php echo ($errors->first('image')?'has-error':''); ?>">
						<div class="mws-form-row">

						{!! HTML::decode( Form::label('image', trans("Icon").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::file('image', ['class' => '' ,'accept'=>"image/*" ,'data-type'=>'image','id'=>"picture"]) }}
								<p id="error1" style="display:none; color:#FF0000;">
									Invalid Image Format! Image Format Must Be JPG, JPEG OR PNG .
									</p>
									<p id="error2" style="display:none; color:#FF0000;">
									Maximum File Size Limit is 5MB.
									</p>
								<div class="error-message help-inline">
									<?php echo $errors->first('image'); ?>
								</div>
							</div>
							@if($adminCmspage->image != "")
										<br />
									<img height="50" width="50" src="{{CMS_IMAGE_URL.$adminCmspage->image }}" />
							@endif


						</div>
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
							<a href='{{ url("admin/cms-manager/edit-cms",$adminCmspage->id)}}' class="btn btn-primary"><i class=\"icon-refresh\"></i> {{ trans('Reset')  }}</a>
							<a href="{{URL::to('admin/cms-manager')}}" class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans('Cancel')  }}</a>
						</div>
					</div>
					{{ Form::close() }}
                </div>
            </div>
        </div>
    <div>
<div>

<script>
		$('#picture').bind('change', function() {
		if ($('input:submit').attr('disabled',false)){
			$('input:submit').attr('disabled',true);
			}
		var ext = $('#picture').val().split('.').pop().toLowerCase();
			if ($.inArray(ext, ['gif','png','jpg','jpeg']) == -1){
				$('#error1').slideDown("slow");
				$('#error2').slideUp("slow");
				a=0;
			}else{
				var picsize = (this.files[0].size);
				if (picsize > 5000000){
				$('#error2').slideDown("slow");
				a=0;
				}else{
				a=1;
				$('#error2').slideUp("slow");
				}
				$('#error1').slideUp("slow");
				if (a==1){
					$('input:submit').attr('disabled',false);
					}
			}
		});
		</script>
@stop

