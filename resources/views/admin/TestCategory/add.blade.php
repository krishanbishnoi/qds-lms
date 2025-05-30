@extends('admin.layouts.default')
@section('content')
    <!-- JS & CSS library of MultiSelect plugin -->
    <!-- <script src="https://phpcoder.tech/multiselect/js/jquery.multiselect.js"></script>
    <link rel="stylesheet" href="https://phpcoder.tech/multiselect/css/jquery.multiselect.css"> -->

    <script src="https://cdn.ckeditor.com/4.15.0/standard-all/ckeditor.js"></script>
    <div class="content-wrapper">
        <div class="page-header">
            <h2 class="page-title">Add New {{ $sectionNameSingular }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <!-- <li class="breadcrumb-item"><a href="#">Forms</a></li> -->
                    <!-- <li class="breadcrumb-item active" aria-current="page">Forget Password</li> -->

                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
                                class=" fa fa-dashboard"></i>Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route($modelName . '.index') }}">{{ $sectionName }}</a></li>
                    <li class="breadcrumb-item active">Add New {{ $sectionNameSingular }}</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['role' => 'form', 'route' => "$modelName.add", 'class' => 'mws-form', 'files' => true, 'autocomplete' => 'off']) }}

                        <div class="mws-panel-body no-padding tab-content">
                            <div class=" form-group <?php echo $errors->first('name') ? 'has-error' : ''; ?>">
                                <div class="mws-form-row">
                                    {!! Html::decode(
                                        Form::label(
                                            'name',
                                            trans('Name') .
                                                '<span class="requireRed">*
                                                                    </span>',
                                            ['class' => 'mws-form-label'],
                                        ),
                                    ) !!}
                                    <div class="mws-form-item">
                                        {{ Form::text('name', '', ['class' => 'form-control']) }}
                                        <div class="error-message help-inline">
                                            <?php echo $errors->first('name'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group <?php echo $errors->first('description') ? 'has-error' : ''; ?>">
                                {!! Html::decode(
                                    Form::label(
                                        'description',
                                        trans('Description') .
                                            '<span class="requireRed">
                                                                * </span>',
                                        ['class' => 'mws-form-label'],
                                    ),
                                ) !!}
                                <div class="mws-form-item">
                                    {{ Form::textarea('description', '', ['class' => 'form-control textarea_resize', 'id' => 'description', 'rows' => 3, 'cols' => 3]) }}
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
                            <div class="mws-button-row">
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
                                minDate: moment() // Set the minimum date to today's date
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
