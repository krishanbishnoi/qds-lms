@extends('trainer.layouts.default')
@section('content')
<script src="https://phpcoder.tech/multiselect/js/jquery.multiselect.js"></script>
<link rel="stylesheet" href="https://phpcoder.tech/multiselect/css/jquery.multiselect.css">
<div class="content-wrapper">
    <div class="page-header">
        <h2 class="page-title">Edit {{ $sectionNameSingular }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard')}}"><i
                            class="fa fa-dashboard"></i>Dashboard</a></li>

                <li class="breadcrumb-item"><a href='{{ route("$modelName.index")}}'>{{ $sectionName }}</a></li>
                <li class="breadcrumb-item active">Add New {{ $sectionNameSingular }}</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['role' => 'form','url' =>  route("$modelName.edit",$model->id),'class' => 'mws-form', 'files' => true,"autocomplete"=>"off"]) }}
                    <div class="mws-panel-body no-padding tab-content">
                        <div class=" form-group <?php echo ($errors->first('category_id')?'has-error':''); ?>">
                            <div class="mws-form-row">
                                {!! Html::decode( Form::label('category', trans("Select Test Category").'<span class="requireRed">*
                                </span>', ['class' => 'mws-form-label'])) !!}
                                <div class="mws-form-item">
                                    {{ Form::select('category_id',$TestCategory,$model->category_id,  ['class' => 'form-control','placeholder' => 'Please select test category']) }}
                                    <div class="error-message help-inline">
                                        <?php echo $errors->first('category_id'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" form-group <?php echo ($errors->first('title')?'has-error':''); ?>">
                            <div class="mws-form-row">
                                {!! Html::decode( Form::label('title', trans("Title").'<span class="requireRed">*
                                </span>', ['class' => 'mws-form-label'])) !!}
                                <div class="mws-form-item">
                                    {{ Form::text('title',$model->title,  ['class' => 'form-control']) }}
                                    <div class="error-message help-inline">
                                        <?php echo $errors->first('title'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" form-group <?php echo ($errors->first('type')?'has-error':''); ?>">
                            <div class="mws-form-row">
                                {!! Html::decode( Form::label('type', trans("Test Type").'<span class="requireRed">*
                                </span>', ['class' => 'mws-form-label'])) !!}
                                <div class="mws-form-item">
                                    {{ Form::select('type',test_type,$model->type,  ['class' => 'form-control','placeholder' => 'Please select test type']) }}
                                    <div class="error-message help-inline">
                                        <?php echo $errors->first('type'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group <?php echo ($errors->first('lob')?'has-error':''); ?>">
                        <div class="mws-form-row">
                            {!! Html::decode( Form::label('lob', trans("LOB").'<span class="requireRed"> * </span>',
                            ['class' => 'mws-form-label'])) !!}
                            <div class="mws-form-item">
                                {{ Form::select('lob',$lob,$model->lob, ['class' => 'form-control ','placeholder' => 'Please Select LOB']) }}
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
                                {{ Form::select('region',$region,$model->region, ['class' => 'form-control ','placeholder' => 'Please Select Region']) }}
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
                                {{ Form::select('circle',$circle,$model->circle, ['class' => 'form-control ','placeholder' => 'Please Select Circle']) }}
                                <div class="error-message help-inline">
                                    <?php echo $errors->first('circle'); ?>
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
                                    {{ Form::text('start_date_time',$model->start_date_time, ['class' => 'form-control small', 'id' => 'start_date_time' ]) }}
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
                                    {{ Form::text('end_date_time',$model->end_date_time, ['class' => 'form-control small', 'id' => 'end_date_time' ]) }}
                                    <div class="error-message help-inline">
                                        <?php echo $errors->first('end_date_time'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group <?php echo ($errors->first('minimum_marks')?'has-error':''); ?>">
                            <div class="mws-form-row">
                                {!! Html::decode( Form::label('minimum_marks', trans("Minimum Passing Percentage").'<span
                                    class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
                                <div class="mws-form-item">
                                    {{ Form::text('minimum_marks',$model->minimum_marks, ['class' => 'form-control small','maxlength'=>"10",'id'=>'minimum_marks','oninput'=>"this.value=this.value.replace(/[^0-9]/g,'');"]) }}
                                    <div class="error-message help-inline">
                                        <?php echo $errors->first('minimum_marks'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group <?php echo ($errors->first('number_of_questions')?'has-error':''); ?>">
                            <div class="mws-form-row">
                                {!! Html::decode( Form::label('number_of_questions', trans("Number of Question").'<span
                                    class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
                                <div class="mws-form-item">
                                    {{ Form::text('number_of_questions',$model->number_of_questions, ['class' => 'form-control small','id'=>'number_of_questions','maxlength'=>"10",'oninput'=>"this.value=this.value.replace(/[^0-9]/g,'');"]) }}
                                    <div class="error-message help-inline">
                                        <?php echo $errors->first('number_of_questions'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group <?php echo ($errors->first('number_of_attempts')?'has-error':''); ?>">
                            <div class="mws-form-row">
                                {!! Html::decode( Form::label('number_of_attempts', trans("Number of Attempts").'<span
                                    class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
                                <div class="mws-form-item">
                                    {{ Form::text('number_of_attempts',$model->number_of_attempts, ['class' => 'form-control small','id'=>'number_of_attempts','maxlength'=>"10",'oninput'=>"this.value=this.value.replace(/[^0-9]/g,'');"]) }}
                                    <div class="error-message help-inline">
                                        <?php echo $errors->first('number_of_attempts'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group <?php echo ($errors->first('time_of_test')?'has-error':''); ?>">
                            <div class="mws-form-row">
                                {!! Html::decode( Form::label('time_of_test', trans("Time Of Test(mins)").'<span
                                    class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
                                <div class="mws-form-item">
                                    {{ Form::text('time_of_test',$model->time_of_test, ['class' => 'form-control small','id'=>'time_of_test','maxlength'=>"10",'oninput'=>"this.value=this.value.replace(/[^0-9]/g,'');"]) }}
                                    <div class="error-message help-inline">
                                        <?php echo $errors->first('time_of_test'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group <?php echo ($errors->first('thumbnail')?'has-error':''); ?>">
                            <div class="mws-form-row">
                                {!! HTML::decode( Form::label('thumbnail', trans("Thumbnail").'<span class="requireRed"> *
                                </span>', ['class' => 'mws-form-label'])) !!}
                                <div class="mws-form-item">
                                    {{ Form::file('thumbnail', ['class' => '']) }}
                                    <div class="error-message help-inline">
                                        <?php echo $errors->first('thumbnail'); ?>
                                    </div>

                                </div>
                                <div class='lightbox'>
									@if(TRAINING_DOCUMENT_URL.$model->thumbnail != "")
										<br />
										<img height="100" width="100" src="{{ TRAINING_DOCUMENT_URL.$model->thumbnail }}" />
									@endif
									</div>
                            </div>
                        </div>
                        <div class="form-group <?php echo $errors->first('description') ? 'has-error' : ''; ?>">
                            {!! Html::decode( Form::label('description',trans("Description").'<span class="requireRed">
                                * </span>', ['class' => 'mws-form-label'])) !!}
                            <div class="mws-form-item">
                                {{ Form::textarea("description",$model->description, ['class' => 'form-control textarea_resize','id' => 'description' ,"rows"=>3,"cols"=>3]) }}
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
                            <a href='{{ route("$modelName.edit",[$model->id])}}' class="btn btn-primary reset_form"><i
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


                // 	$('#datetimepicker4').datetimepicker({
                // 	onClose: function (dateText, inst) {
                // 		alert("date chosen");
                // 	}
                // });


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

                   $("#start_date_time").on("dp.change", function (e) {
                       $('#end_date_time').data("DateTimePicker").minDate(e.date);
                   });
                   $("#end_date_time").on("dp.change", function (e) {
                       $('#start_date_time').data("DateTimePicker").maxDate(e.date);
                   });
                });





                </script>
                @stop

