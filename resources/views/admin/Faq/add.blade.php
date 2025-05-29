@extends('admin.layouts.default')
@section('content')

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
                {{ Form::open(['role' => 'form','url' => 'admin/faqs-manager/add-faqs','class' => 'mws-form']) }}
                <div class="form-group <?php echo ($errors->first('question')?'has-error':''); ?>">
							<div class="mws-form-row">
								{!! Html::decode( Form::label('question',trans("Question").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
								<div class="mws-form-item">
									{{ Form::text('question','', ['class' => 'form-control']) }}
									<div class="error-message help-inline">
										<?php echo $errors->first('question'); ?>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group <?php echo $errors->first('answer') ? 'has-error' : ''; ?>">
							{!! Html::decode( Form::label('answer',trans("Answer").'<span class="requireRed"> *  </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::textarea("answer",'', ['class' => 'form-control textarea_resize','id' => 'answer' ,"rows"=>3,"cols"=>3]) }}
								<span class="error-message descriptionTypeError help-inline">
								<?php echo $errors->first('answer') ? $errors->first('answer') : ''; ?>
								</span>
							</div>
							<script>
								var answer =
								CKEDITOR.replace('answer', {
								extraAllowedContent: 'div',
								height: 300
								});

							</script>
						</div>

                         <input type="submit" value="{{ trans('Save') }}" class="btn btn-danger">
                                <a href='{{ route("$modelName.add") }}' class="btn btn-primary"><i
                                        class=\"icon-refresh\"></i> {{ trans('Reset') }}</a>
                                <a href="{{ URL::to('admin/faqs-manager') }}" class="btn btn-info"><i
                                        class=\"icon-refresh\"></i> {{ trans('Cancel') }}</a>
                {{ Form::close() }}
                </div>
            </div>
        </div>
    <div>
<div>
@stop

