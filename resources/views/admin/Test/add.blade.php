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
                            {{ Form::model($model, ['url' => route("$modelName.save"), 'id' => 'edit-plan-form', 'class' => 'row g-3', 'files' => true]) }}
                            {{ Form::hidden('id', null) }}
                        @else
                            {{ Form::open(['url' => route("$modelName.save"), 'id' => 'add-plan-form', 'class' => 'row g-3', 'files' => true]) }}
                        @endif
                        <div class="mws-panel-body no-padding tab-content row">
                            <div class="col-md-6">
                                <div class=" form-group <?php echo $errors->first('category_id') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label(
                                                'category',
                                                trans('Select Test Category') .
                                                    '<span class="requireRed">*
                                                                                                                                                                                                                                                                                                                                                    </span>',
                                                ['class' => 'mws-form-label'],
                                            ),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::select('category_id', $TestCategory, null, ['class' => 'form-control', 'placeholder' => 'Please select test category']) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('category_id'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class=" form-group <?php echo $errors->first('title') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label(
                                                'title',
                                                trans('Title') .
                                                    '<span class="requireRed">*
                                                                                                                                                                                                                                                                                                                                                    </span>',
                                                ['class' => 'mws-form-label'],
                                            ),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::text('title', null, ['class' => 'form-control']) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('title'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class=" form-group <?php echo $errors->first('type') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label(
                                                'type',
                                                trans('Test Type') .
                                                    '<span class="requireRed">*
                                                                                                                                                                                                                                                                                                                                                    </span>',
                                                ['class' => 'mws-form-label'],
                                            ),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::select('type', test_type,null , ['class' => 'form-control', 'placeholder' => 'Please select test type']) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('type'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- @if (Auth::user()->user_role_id == MANAGER_ROLE_ID)
                        <div class="form-group <?php echo $errors->first('training_trainer') ? 'has-error' : ''; ?>">
                            <div class="mws-form-row ">
                                {!! Html::decode( Form::label('training_trainer', trans("Assign Trainer").'<span
                                    class="requireRed"></span>',['class' => 'mws-form-label'])) !!}
                                <div class="mws-form-item">
                                    {{ Form::select("training_trainer[]",$trainers,null, ['class' => 'form-control','id'=>'training_trainer','multiple']) }}
                                    <div class="error-message help-inline">
                                        <?php echo $errors->first('training_trainer'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="form-group <?php echo $errors->first('training_manager') ? 'has-error' : ''; ?>">
                            <div class="mws-form-row ">
                                {!! Html::decode( Form::label('training_manager', trans("Assign Training Manager").'<span
                                    class="requireRed"></span>',['class' => 'mws-form-label'])) !!}
                                <div class="mws-form-item">
                                    {{ Form::select("training_manager[]",$training_manager,null, ['class' => 'form-control','id'=>'training_manager','multiple']) }}
                                    <div class="error-message help-inline">
                                        <?php echo $errors->first('training_manager'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
			            @endIf --}}

                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('lob') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label('lob', trans('LOB') . '<span class="requireRed"> * </span>', ['class' => 'mws-form-label']),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::select('lob', $lob, null, ['class' => 'form-control ', 'placeholder' => 'Please Select LOB']) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('lob'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('region') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label(
                                                'region',
                                                trans('Region') .
                                                    '<span class="requireRed"> *
                                                                                                                                                                                                                                                                                                                                                </span>',
                                                ['class' => 'mws-form-label'],
                                            ),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::select('region', $region, null, ['class' => 'form-control ', 'placeholder' => 'Please Select Region']) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('region'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('circle') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label(
                                                'circle',
                                                trans('Circle') .
                                                    '<span class="requireRed"> *
                                                                                                                                                                                                                                                                                                                                                </span>',
                                                ['class' => 'mws-form-label'],
                                            ),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::select('circle', $circle, null, ['class' => 'form-control ', 'placeholder' => 'Please Select Circle']) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('circle'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('start_date_time') ? 'has-error' : ''; ?>" id="live_date">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label('start_date_time', trans('Start Date') . '<span class="requireRed"> * </span>', [
                                                'class' => 'mws-form-label',
                                            ]),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::text('start_date_time', null, ['class' => 'form-control small', 'id' => 'start_date_time']) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('start_date_time'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('end_date_time') ? 'has-error' : ''; ?>" id="live_date">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label('end_date_time', trans('End Date') . '<span class="requireRed"> * </span>', [
                                                'class' => 'mws-form-label',
                                            ]),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::text('end_date_time', null, ['class' => 'form-control small', 'id' => 'end_date_time']) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('end_date_time'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('minimum_marks') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label(
                                                'minimum_marks',
                                                trans('Minimum Passing Percentage') .
                                                    '<span
                                                                                                                                                                                                                                                                                                                                                    class="requireRed"> * </span>',
                                                ['class' => 'mws-form-label'],
                                            ),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::text('minimum_marks', null, ['class' => 'form-control small', 'maxlength' => '10', 'id' => 'minimum_marks', 'oninput' => "this.value=this.value.replace(/[^0-9]/g,'');"]) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('minimum_marks'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('number_of_questions') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label(
                                                'number_of_questions',
                                                trans('Number of Question') .
                                                    '<span
                                                                                                                                                                                                                                                                                                                                                    class="requireRed"> * </span>',
                                                ['class' => 'mws-form-label'],
                                            ),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::text('number_of_questions', null, ['class' => 'form-control small', 'id' => 'number_of_questions', 'maxlength' => '10', 'oninput' => "this.value=this.value.replace(/[^0-9]/g,'');"]) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('number_of_questions'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('number_of_attempts') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label(
                                                'number_of_attempts',
                                                trans('Number of Attempts') .
                                                    '<span
                                                                                                                                                                                                                                                                                                                                                    class="requireRed"> * </span>',
                                                ['class' => 'mws-form-label'],
                                            ),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::text('number_of_attempts', null, ['class' => 'form-control small', 'id' => 'number_of_attempts', 'maxlength' => '10', 'oninput' => "this.value=this.value.replace(/[^0-9]/g,'');"]) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('number_of_attempts'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('time_of_test') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label(
                                                'time_of_test',
                                                trans('Time Of Test(mins)') .
                                                    '<span
                                                                                                                                                                                                                                                                                                                                                    class="requireRed"> * </span>',
                                                ['class' => 'mws-form-label'],
                                            ),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::text('time_of_test', null, ['class' => 'form-control small', 'id' => 'time_of_test', 'maxlength' => '10', 'oninput' => "this.value=this.value.replace(/[^0-9]/g,'');"]) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('time_of_test'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('publish_result') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label(
                                                'publish_result',
                                                trans('Publish Results') .
                                                    '<span class="requireRed"> *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </span>',
                                                ['class' => 'mws-form-label'],
                                            ),
                                        ) !!}
                                        <div class="mws-form-item">
                                            <input class="form-check-input" type="radio" name="publish_result"
                                                id="0" value="0" checked>
                                            <label class="form-check-label" for="0">yes</label>
                                            <input class="form-check-input" type="radio" name="publish_result"
                                                id="1" value="1">
                                            <label class="form-check-label" for="1">no</label>
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('publish_result'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('thumbnail') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! HTML::decode(
                                            Form::label(
                                                'thumbnail',
                                                trans('Thumbnail') .
                                                    '<span class="requireRed"> *
                                                                                                                                                                                                                                                                                                                                                </span>',
                                                ['class' => 'mws-form-label'],
                                            ),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::file('thumbnail', ['class' => null, 'accept' => 'image/*']) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('thumbnail'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
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
                                        {{ Form::textarea('description', null, ['class' => 'form-control textarea_resize', 'id' => 'description', 'rows' => 3, 'cols' => 3]) }}
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
                                minDate: moment() // today's date
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
