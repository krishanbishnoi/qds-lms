@extends('admin.layouts.default')
@section('content')
    <script src="https://cdn.ckeditor.com/4.15.0/standard-all/ckeditor.js"></script>
    @php
        $flag = 0;
        $heading = 'Add';
        if (isset($model) && !empty($model)) {
            $flag = 1;
            $heading = 'Update';
        }
    @endphp
    <div class="content-wrapper">
        <div class="page-header">
            <h2 class="page-title">{{ $heading }} New {{ $sectionNameSingular }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
                                class=" fa fa-dashboard"></i>Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route($modelName . '.index') }}">{{ $sectionName }}</a></li>
                    <li class="breadcrumb-item active">{{ $heading }} New {{ $sectionNameSingular }}</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        @if ($flag == 1)
                            {{ Form::model($model, ['url' => route("$modelName.save"), 'id' => 'edit-plan-form', 'class' => 'row g-3']) }}
                            {{ Form::hidden('id', null) }}
                        @else
                            {{ Form::open(['url' => route("$modelName.save"), 'id' => 'add-plan-form', 'class' => 'row g-3']) }}
                        @endif

                        <div class="mws-panel-body no-padding tab-content row">
                            <div class="col-md-6">
                                <div class=" form-group <?php echo $errors->first('name') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label('name', trans('Name') . '<span class="requireRed">* </span>', ['class' => 'mws-form-label']),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::text('name', null, ['class' => 'form-control', 'maxlength' => '250']) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('name'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class=" form-group <?php echo $errors->first('location') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label('location', trans('Location') . '<span class="requireRed">* </span>', ['class' => 'mws-form-label']),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::text('location', null, ['class' => 'form-control', 'maxlength' => '250']) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('location'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('lob') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label('status', trans('Status') . '<span class="requireRed"> * </span>', ['class' => 'mws-form-label']),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::select('status', config('constants.STATUS_LIST'), null, ['class' => 'form-control ']) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('status'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mws-button-row text-end">
                                <input type="submit" value="{{ trans('Save') }}" class="btn btn-danger">
                                <a href="{{ route($modelName . '.add') }}" class="btn btn-primary reset_form"><i
                                        class=\"icon-refresh\"></i> {{ trans('Clear') }}</a>
                                <a href="{{ route($modelName . '.index') }}" class="btn btn-info"><i
                                        class=\"icon-refresh\"></i> {{ trans('Cancel') }}</a>
                            </div>
                        </div>

                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            <div>
                <div>

                @stop
