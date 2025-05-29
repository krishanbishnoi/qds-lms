@extends('admin.layouts.default')
@section('content')
<script>
$(function() {
    $('#date_of_birth').datetimepicker({
        format: 'YYYY-MM-DD',
    });

});
</script>
<div class="content-wrapper">
    <div class="page-header">
        <h2 class="page-title">Edit {{ $sectionNameSingular }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <!-- <li class="breadcrumb-item"><a href="#">Forms</a></li> -->
                <!-- <li class="breadcrumb-item active" aria-current="page">Forget Password</li> -->

                <li class="breadcrumb-item"><a href="{{ route('dashboard')}}"><i
                            class=" fa fa-dashboard"></i>Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route($modelName.'.index')}}">{{ $sectionName }}</a></li>
                <li class="breadcrumb-item active">Edit {{ $sectionNameSingular }}</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['role' => 'form','url' =>  route("$modelName.edit",$model->id),'class' => 'mws-form', 'files' => true,"autocomplete"=>"off"]) }}
                    <div class="form-group <?php echo ($errors->first('employee_id')?'has-error':''); ?>">
                        <div class="mws-form-row">
                            {!! Html::decode( Form::label('employee_id', trans("Employee ID").'<span class="requireRed">
                                * </span>', ['class' => 'mws-form-label'])) !!}
                            <div class="mws-form-item">
                                {{ Form::text('employee_id',$model->employee_id, ['class' => 'form-control small','readonly'=>'readonly']) }}
                                <div class="error-message help-inline">
                                    <?php echo $errors->first('employee_id'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group <?php echo ($errors->first('first_name')?'has-error':''); ?>">
                        <div class="mws-form-row">
                            {!! Html::decode( Form::label('first_name', trans("First Name").'<span class="requireRed"> *
                            </span>', ['class' => 'mws-form-label'])) !!}
                            <div class="mws-form-item">
                                {{ Form::text('first_name',$model->first_name, ['class' => 'form-control small']) }}
                                <div class="error-message help-inline">
                                    <?php echo $errors->first('first_name'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group <?php echo ($errors->first('last_name')?'has-error':''); ?>">
                        <div class="mws-form-row">
                            {!! Html::decode( Form::label('last_name', trans("Last Name").'<span class="requireRed"> *
                            </span>', ['class' => 'mws-form-label'])) !!}
                            <div class="mws-form-item">
                                {{ Form::text('last_name',$model->last_name, ['class' => 'form-control small']) }}
                                <div class="error-message help-inline">
                                    <?php echo $errors->first('last_name'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group <?php echo ($errors->first('mobile_number')?'has-error':''); ?>">
                        <div class="mws-form-row">
                            {!! Html::decode( Form::label('mobile_number', trans("Mobile Number").'<span
                                class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
                            <div class="mws-form-item">
                                {{ Form::text('mobile_number',$model->mobile_number, ['class' => 'form-control small']) }}
                                <div class="error-message help-inline">
                                    <?php echo $errors->first('mobile_number'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group <?php echo ($errors->first('email')?'has-error':''); ?>">
                        <div class="mws-form-row">
                            {!! Html::decode( Form::label('email', trans("Email").'<span class="requireRed"> * </span>',
                            ['class' => 'mws-form-label'])) !!}
                            <div class="mws-form-item">
                                {{ Form::text('email',$model->email, ['class' => 'form-control small','readonly'=>'readonly']) }}
                                <div class="error-message help-inline">
                                    <?php echo $errors->first('email'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group <?php echo ($errors->first('lob')?'has-error':''); ?>">
                        <div class="mws-form-row">
                            {!! Html::decode( Form::label('lob', trans("LOB").'<span class="requireRed"> * </span>',
                            ['class' => 'mws-form-label'])) !!}
                            <div class="mws-form-item">
                                {{ Form::select('lob',$lob,$model->lob, ['class' => 'form-control ']) }}
                                <div class="error-message help-inline">
                                    <?php echo $errors->first('lob'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group <?php echo ($errors->first('region')?'has-error':''); ?>">
                        <div class="mws-form-row">
                            {!! Html::decode( Form::label('region', trans("Region").'<span class="requireRed"> *
                            </span>', ['class' => 'mws-form-label'])) !!}
                            <div class="mws-form-item">
                                {{ Form::select('region',$region,$model->region, ['class' => 'form-control ']) }}
                                <div class="error-message help-inline">
                                    <?php echo $errors->first('region'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group <?php echo ($errors->first('circle')?'has-error':''); ?>">
                        <div class="mws-form-row">
                            {!! Html::decode( Form::label('circle', trans("Circle").'<span class="requireRed"> *
                            </span>', ['class' => 'mws-form-label'])) !!}
                            <div class="mws-form-item">
                                {{ Form::select('circle',$circle,$model->circle, ['class' => 'form-control ']) }}
                                <div class="error-message help-inline">
                                    <?php echo $errors->first('circle'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="submit" value="{{ trans('Save') }}" class="btn btn-danger">
                    <a href='{{ route("$modelName.edit",$model->id)}}' class="btn btn-primary reset_form"><i
                            class=\"icon-refresh\"></i> {{ trans('Clear') }}</a>
                    <a href="{{ route($modelName.'.index') }}" class="btn btn-info"><i class=\"icon-refresh\"></i>
                        {{ trans('Cancel')  }}</a>

                    {{ Form::close() }}
                </div>
            </div>
        </div>
        <div>
            <div>
                @stop
