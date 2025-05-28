@extends('trainer.layouts.default')
@section('content')
<!-- JS & CSS library of MultiSelect plugin -->
<script src="https://phpcoder.tech/multiselect/js/jquery.multiselect.js"></script>
<link rel="stylesheet" href="https://phpcoder.tech/multiselect/css/jquery.multiselect.css">


<script src="https://cdn.ckeditor.com/4.15.0/standard-all/ckeditor.js"></script>
<div class="content-wrapper">
    <div class="page-header">
        <h2 class="page-title">Add New {{ $sectionNameSingular }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard')}}"><i
                            class="fa fa-dashboard"></i>Dashboard</a></li>
                <li class="breadcrumb-item"> <a href='{{ route("TrainerTest.index")}}'>Tests</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route($modelName.'.index',$test_id)}}">{{ $sectionName }}</a></li>
                <li class="breadcrumb-item active">Add New {{ $sectionNameSingular }}</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['role' => 'form','url' =>  route("$modelName.add",$test_id),'class' => 'mws-form', 'files' => true,"autocomplete"=>"off"]) }}

                    <div class="mws-panel-body no-padding tab-content">


                        <div class="form-group <?php echo ($errors->first('question')?'has-error':''); ?>">
                            <div class="mws-form-row">
                                {!! Html::decode( Form::label('question', trans("Question").'<span class="requireRed"> *
                                </span>', ['class' => 'mws-form-label'])) !!}
                                <div class="mws-form-item">
                                    {{ Form::text('question','', ['class' => 'form-control small','id'=>'question']) }}
                                    <div class="error-message help-inline">
                                        <?php echo $errors->first('question'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group <?php echo ($errors->first('question_type')?'has-error':''); ?>">
                            <div class="mws-form-row">
                                {!! Html::decode( Form::label('question_type', trans("Question Type").'<span
                                    class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
                                <div class="mws-form-item">
                                    {{ Form::select('question_type',question_type,'', ['class' => 'form-control small','id'=>'question_type','placeholder'=>'Please Select Question Type'  ]) }}
                                    <div class="error-message help-inline">
                                        <?php echo $errors->first('question_type'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        $question_type =   !empty(Request::old('question_type'))? Request::old('question_type'):'';


                        ?>
                        <div id="row_dim" class="question_type">
                            <div class=" project_detailSection">
                                <div class="projectDetailsInnerSection ace_left_sec">
                                    <table>
                                        <tr>
                                            <td width="700px">
                                                <div
                                                    class="form-group <?php echo ($errors->first('option')) ? 'has-error' : ''; ?>">
                                                    {!! Html::decode( Form::label('option', trans("Options").'<span
                                                        class="requireRed"> </span>', ['class' => 'mws-form-label']))
                                                    !!}
                                                    <div class="mws-form-item">
                                                        {{ Form::text('data[1][option]','',['class'=>'form-control option']) }}
                                                        <div class="error-message help-inline">
                                                            <?php echo $errors->first('option'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td style="padding-top: 23px;" width="10px">
                                                <div class="form-group">
                                                    <input type="hidden" name="count" value="1" id="add_more_count">
                                                    <a href="javascript:void(0);" id="addMore"
                                                        class="btn btn-primary add_new_btn add_more_new_supp"
                                                        value="Add More">Add More</a>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="form-group <?php echo ($errors->first('marks')?'has-error':''); ?>">
                            <div class="mws-form-row">
                                {!! Html::decode( Form::label('marks', trans("Marks").'<span class="requireRed"> *
                                </span>', ['class' => 'mws-form-label'])) !!}
                                <div class="mws-form-item">
                                    {{ Form::text('marks','', ['class' => 'form-control small','id'=>'marks']) }}
                                    <div class="error-message help-inline">
                                        <?php echo $errors->first('marks'); ?>
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
                            <a href="{{ route($modelName.'.add',$test_id)}}" class="btn btn-primary reset_form"><i
                                    class=\"icon-refresh\"></i> {{ trans('Clear') }}</a>
                            <a href="{{ route($modelName.'.index',$test_id) }}" class="btn btn-info"><i
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
                $('#addMore').click(function() {
                    var count = $('#add_more_count').val();
                    var new_count = parseInt(count) + parseInt(1);
                    $.ajax({
                        url: '{{ URL("trainer/questions/add-more-option") }}',
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


                $(function() {
                    $('#row_dim').hide();
                    if ($('#question_type').val() == 'radio' || $('#question_type').val() == 'checkbox' || $(
                            '#question_type').val() == 'select') {
                        $('.question_type').show();
                    }

                });
                $('#question_type').change(function() {
                    if ($('#question_type').val() == 'radio' || $('#question_type').val() == 'checkbox' || $(
                            '#question_type').val() == 'select') {
                                $('.option').val("");
                        $('#row_dim').show();
                    } else {
                        $('.option').val("");
                        $('#row_dim').hide();
                    }
                });
                </script>
                @stop
