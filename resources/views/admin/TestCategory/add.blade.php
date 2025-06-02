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
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route($modelName . '.index') }}">{{ $sectionName }}</a>
                    </li>
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
                                <div class="form-group {{ $errors->first('name') ? 'has-error' : '' }}">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label('name', trans('Name') . ' <span class="requireRed">*</span>', ['class' => 'mws-form-label']),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::text('name', null, ['class' => 'form-control']) }}
                                            <div class="error-message help-inline">
                                                {{ $errors->first('name') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group {{ $errors->first('is_active') ? 'has-error' : '' }}">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label('is_active', trans('Status') . '<span class="requireRed"> * </span>', ['class' => 'mws-form-label']),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::select('is_active', config('constants.STATUS_LIST'), null, ['class' => 'form-control']) }}
                                            <div class="error-message help-inline">
                                                {{ $errors->first('is_active') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group {{ $errors->first('description') ? 'has-error' : '' }}">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label('description', trans('Description') . '<span class="requireRed">*</span>', [
                                                'class' => 'mws-form-label',
                                            ]),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::textarea('description', null, ['class' => 'form-control', 'id' => 'description', 'rows' => 3, 'cols' => 3]) }}
                                            <span class="error-message descriptionTypeError help-inline">
                                                {{ $errors->first('description') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    var description;
                                    CKEDITOR.replace('description', {
                                        allowedContent: 'div',
                                    });
                                </script>
                            </div>

                            <div class="col-md-12 text-end">
                                <input type="submit" value="{{ trans('Save') }}" class="btn btn-danger">
                                <a href="{{ route($modelName . '.add') }}" class="btn btn-primary reset_form">
                                    <i class="icon-refresh"></i> {{ trans('Clear') }}
                                </a>
                                <a href="{{ route($modelName . '.index') }}" class="btn btn-info">
                                    <i class="icon-refresh"></i> {{ trans('Cancel') }}
                                </a>
                            </div>

                        </div>

                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .datetimepicker {
            position: relative;
        }
    </style>

    <script type="text/javascript">
        $(function() {
            $('#start_date_time').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss',
                icons: {
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
                defaultDate: moment()
            });

            $('#end_date_time').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss',
                icons: {
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

            $("#start_date_time").on("dp.change", function(e) {
                $('#end_date_time').data("DateTimePicker").minDate(e.date);
            });

            $("#end_date_time").on("dp.change", function(e) {
                $('#start_date_time').data("DateTimePicker").maxDate(e.date);
            });
        });
    </script>
@stop
