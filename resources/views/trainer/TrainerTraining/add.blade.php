@extends('admin.layouts.default')
@section('content')
<!-- JS & CSS library of MultiSelect plugin -->


<script src="https://cdn.ckeditor.com/4.15.0/standard-all/ckeditor.js"></script>
<div class="content-wrapper">
    <div class="page-header">
        <h2 class="page-title">Add New {{ $sectionNameSingular }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <!-- <li class="breadcrumb-item"><a href="#">Forms</a></li> -->
                <!-- <li class="breadcrumb-item active" aria-current="page">Forget Password</li> -->

                <li class="breadcrumb-item"><a href="{{ route('dashboard')}}"><i
                            class=" fa fa-dashboard"></i>Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route($modelName.'.index')}}">{{ $sectionName }}</a></li>
                <li class="breadcrumb-item active">Add New {{ $sectionNameSingular }}</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['role' => 'form','route' => "$modelName.add",'class' => 'mws-form', 'files' => true,"autocomplete"=>"off"]) }}

                    <div class=" form-group <?php echo ($errors->first('category_id')?'has-error':''); ?>">
                        <div class="mws-form-row">
                            {!! Html::decode( Form::label('category', trans("Select Training Category").'<span class="requireRed">*
                            </span>', ['class' => 'mws-form-label'])) !!}
                            <div class="mws-form-item">
                                {{ Form::select('category_id',$trainingCategory,'',  ['class' => 'form-control','placeholder' => 'Please select training category']) }}
                                <div class="error-message help-inline">
                                    <?php echo $errors->first('category_id'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mws-panel-body no-padding tab-content">
                        <div class=" form-group <?php echo ($errors->first('title')?'has-error':''); ?>">
                            <div class="mws-form-row">
                                {!! Html::decode( Form::label('title', trans("Title").'<span class="requireRed">*
                                </span>', ['class' => 'mws-form-label'])) !!}
                                <div class="mws-form-item">
                                    {{ Form::text('title','',  ['class' => 'form-control']) }}
                                    <div class="error-message help-inline">
                                        <?php echo $errors->first('title'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="form-group <?php echo ($errors->first('test_id')?'has-error':''); ?>">
                            <div class="mws-form-row">
                                {!! Html::decode( Form::label('test_id', trans("Test").'<span class="requireRed"> *
                                </span>', ['class' => 'mws-form-label'])) !!}
                                <div class="mws-form-item">
                                    {{ Form::select('test_id',$test,'', ['class' => 'form-control ']) }}
                                    <div class="error-message help-inline">
                                        <?php echo $errors->first('test_id'); ?>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class=" form-group <?php echo ($errors->first('type')?'has-error':''); ?>">
                            <div class="mws-form-row">
                                {!! Html::decode( Form::label('type', trans("Training Type").'<span class="requireRed">*
                                </span>', ['class' => 'mws-form-label'])) !!}
                                <div class="mws-form-item">
                                    {{ Form::select('type',$TrainingType,'',  ['class' => 'form-control','placeholder' => 'Please select training type']) }}
                                    <div class="error-message help-inline">
                                        <?php echo $errors->first('type'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group <?php echo ($errors->first('start_date_time')?'has-error':''); ?>"
                            id="live_date">
                            <div class="mws-form-row">
                                {!! Html::decode( Form::label('start_date_time', trans("Start Date").'<span
                                    class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
                                <div class="mws-form-item">
                                    {{ Form::text('start_date_time','', ['class' => 'form-control small', 'id' => 'start_date_time' ]) }}
                                    <div class="error-message help-inline">
                                        <?php echo $errors->first('start_date_time'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group <?php echo ($errors->first('end_date_time')?'has-error':''); ?>"
                            id="live_date">
                            <div class="mws-form-row">
                                {!! Html::decode( Form::label('end_date_time', trans("End Date").'<span
                                    class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
                                <div class="mws-form-item">
                                    {{ Form::text('end_date_time','', ['class' => 'form-control small', 'id' => 'end_date_time' ]) }}
                                    <div class="error-message help-inline">
                                        <?php echo $errors->first('end_date_time'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="form-group <?php echo ($errors->first('minimum_marks')?'has-error':''); ?>">
                            <div class="mws-form-row">
                                {!! Html::decode( Form::label('minimum_marks', trans("Minimum Marks").'<span
                                    class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
                                <div class="mws-form-item">
                                    {{ Form::text('minimum_marks','', ['class' => 'form-control small','maxlength'=>"10",'id'=>'minimum_marks','oninput'=>"this.value=this.value.replace(/[^0-9]/g,'');"]) }}
                                    <div class="error-message help-inline">
                                        <?php echo $errors->first('minimum_marks'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group <?php echo ($errors->first('number_of_attempts')?'has-error':''); ?>">
                            <div class="mws-form-row">
                                {!! Html::decode( Form::label('number_of_attempts', trans("Number of Attempts").'<span
                                    class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
                                <div class="mws-form-item">
                                    {{ Form::text('number_of_attempts','', ['class' => 'form-control small','id'=>'number_of_attempts','maxlength'=>"10",'oninput'=>"this.value=this.value.replace(/[^0-9]/g,'');"]) }}
                                    <div class="error-message help-inline">
                                        <?php echo $errors->first('number_of_attempts'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group <?php echo ($errors->first('number_of_attempts')?'has-error':''); ?>">
                            <div class="mws-form-row">
                                {!! Html::decode( Form::label('skip', trans("Skip Video").'<span class="requireRed"> *
                                </span>', ['class' => 'mws-form-label'])) !!}
                                <div class="mws-form-item">
                                <input class="form-check-input" type="radio" name="skip" id="0" value="0">
                                    <label class="form-check-label" for="0">
                                        yes
                                    </label>
                                    <input class="form-check-input" type="radio" name="skip" id="1" value="1">
                                    <label class="form-check-label" for="1">
                                        no
                                    </label>
                                    <div class="error-message help-inline">
                                        <?php echo $errors->first('skip'); ?>
                                    </div>
                                </div>
                            </div>

                        </div> -->
                        <div class="form-group <?php echo ($errors->first('thumbnail')?'has-error':''); ?>">
                            <div class="mws-form-row">
                                {!! HTML::decode( Form::label('thumbnail', trans("Thumbnail").'<span class="requireRed">
                                    *
                                </span>', ['class' => 'mws-form-label'])) !!}
                                <div class="mws-form-item">
                                    {{ Form::file('thumbnail', ['class' => '']) }}
                                    <div class="error-message help-inline">
                                        <?php echo $errors->first('thumbnail'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group <?php echo $errors->first('description') ? 'has-error' : ''; ?>">
                            {!! Html::decode( Form::label('description',trans("Description").'<span class="requireRed">
                                * </span>', ['class' => 'mws-form-label'])) !!}
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
                        <div class="mws-button-row">
                            <input type="submit" value="{{ trans('Save') }}" class="btn btn-danger">
                            <a href="{{ route($modelName.'.add')}}" class="btn btn-primary reset_form"><i
                                    class=\"icon-refresh\"></i> {{ trans('Clear') }}</a>
                            <a href="{{ route($modelName.'.index') }}" class="btn btn-info"><i
                                    class=\"icon-refresh\"></i> {{ trans('Cancel')  }}</a>
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
                        useCurrent: false,
                        minDate: moment() // Set the minimum date to today's date
                    });

                    $("#start_date_time").on("dp.change", function(e) {
                        $('#end_date_time').data("DateTimePicker").minDate(e.date);
                    });
                    $("#end_date_time").on("dp.change", function(e) {
                        $('#start_date_time').data("DateTimePicker").maxDate(e.date);
                    });
                });



                $('#addMore').click(function() {
                    var count = $('#add_more_count').val();
                    var new_count = parseInt(count) + parseInt(1);
                    $.ajax({
                        url: '{{ URL("admin/trainings/add-more-document") }}',
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
                </script>
                @stop
