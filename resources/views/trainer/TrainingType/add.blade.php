@extends('admin.layouts.default')
@section('content')
<script src="https://cdn.ckeditor.com/4.15.0/standard-all/ckeditor.js"></script>
<div class="content-wrapper">
    <div class="page-header">
        <h2 class="page-title">Add New {{ $sectionNameSingular }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <!-- <li class="breadcrumb-item"><a href="#">Forms</a></li> -->
                <!-- <li class="breadcrumb-item active" aria-current="page">Forget Password</li> -->

                <li class="breadcrumb-item"><a href="{{ route('dashboard')}}"><i class=" fa fa-dashboard"></i>Dashboard</a></li>
                <li class="breadcrumb-item" ><a href="{{ route($modelName.'.index')}}">{{ $sectionName }}</a></li>
                <li class="breadcrumb-item active" >Add New {{ $sectionNameSingular }}</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                {{ Form::open(['role' => 'form','route' => "$modelName.add",'class' => 'mws-form', 'files' => true,"autocomplete"=>"off"]) }}

                <div class="mws-panel-body no-padding tab-content">
                    <div class=" form-group <?php echo ($errors->first('type')?'has-error':''); ?>">
                        <div class="mws-form-row">
                        {!! Html::decode( Form::label('type', trans("Training Type").'<span class="requireRed">* </span>', ['class' => 'mws-form-label'])) !!}
                            <div class="mws-form-item">
                                {{ Form::text('type','',  ['class' => 'form-control','maxlength'=>"250"]) }}
                                <div class="error-message help-inline">
                                    <?php echo $errors->first('type'); ?>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="mws-button-row">
                        <input type="submit" value="{{ trans('Save') }}" class="btn btn-danger">
                        <a href="{{ route($modelName.'.add')}}" class="btn btn-primary reset_form"><i class=\"icon-refresh\"></i> {{ trans('Clear') }}</a>
                        <a href="{{ route($modelName.'.index') }}" class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans('Cancel')  }}</a>
                    </div>
                </div>

                {{ Form::close() }}
                </div>
            </div>
        </div>
    <div>
<div>

@stop

