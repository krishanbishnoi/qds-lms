@extends('admin.layouts.default')
@section('content')
    <!-- JS & CSS library of MultiSelect plugin -->
    <script src="https://phpcoder.tech/multiselect/js/jquery.multiselect.js"></script>
    <link rel="stylesheet" href="https://phpcoder.tech/multiselect/css/jquery.multiselect.css">


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
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>Dashboard</a>
                    </li>
                    <li class="breadcrumb-item"> <a href='{{ route('Test.index') }}'>Tests</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route($modelName . '.index', $test_id) }}">{{ $sectionName }}</a>
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
                            {{ Form::model($model, ['url' => route("$modelName.save", $test_id), 'id' => 'edit-plan-form', 'class' => 'row g-3', 'files' => true, 'autocomplete' => 'off']) }}
                            {{ Form::hidden('id', $model->id ?? null) }}
                        @else
                            {{ Form::open(['url' => route("$modelName.save", $test_id), 'id' => 'add-plan-form', 'class' => 'row g-3', 'files' => true, 'autocomplete' => 'off']) }}
                        @endif

                        <div class="mws-panel-body no-padding tab-content row">
                            @livewire('test-question-form', ['testId' => $test_id, 'questionId' => $model->id ?? null])

                            <div class="col-md-12 mt-3">
                                <div class="form-group <?php echo $errors->first('body') ? 'has-error' : ''; ?>">
                                    {!! Html::decode(
                                        Form::label('description', 'Hint or Any Other Description' . '<span class="requireRed"> * </span>', ['class' => 'mws-form-label']),
                                    ) !!}
                                    <div class="mws-form-item">
                                        {{ Form::textarea('description', $model->description ?? null, ['class' => 'form-control', 'id' => 'body']) }}
                                        <span class="error-message help-inline">
                                            <?php echo $errors->first('body'); ?>
                                        </span>
                                    </div>
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <script>
                                        var body = CKEDITOR.replace('body', {
                                            extraAllowedContent: 'div',
                                            height: 300
                                        });
                                        body.on('instanceReady', function() {
                                            this.dataProcessor.writer.selfClosingEnd = '>';

                                            var dtd = CKEDITOR.dtd;
                                            for (var e in CKEDITOR.tools.extend({}, dtd.$nonBodyContent, dtd.$block, dtd.$listItem, dtd
                                                    .$tableContent)) {
                                                this.dataProcessor.writer.setRules(e, {
                                                    indent: true,
                                                    breakBeforeOpen: true,
                                                    breakAfterOpen: true,
                                                    breakBeforeClose: true,
                                                    breakAfterClose: true,
                                                    filebrowserUploadUrl: '<?php echo URL::to('/admin/base/uploder'); ?>',
                                                    filebrowserImageWindowWidth: '640',
                                                    filebrowserImageWindowHeight: '480',
                                                });
                                            }
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>

                        <div class="mws-button-row text-end mt-3">
                            <input type="submit" value="{{ trans('Save') }}" class="btn btn-danger">
                            <a href="{{ route($modelName . '.add', $test_id) }}" class="btn btn-primary reset_form"><i
                                    class="icon-refresh"></i> {{ trans('Clear') }}</a>
                            <a href="{{ route($modelName . '.index', $test_id) }}" class="btn btn-info"><i
                                    class="icon-refresh"></i> {{ trans('Cancel') }}</a>
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
                    <script>
                        $(document).ready(function() {
                            toggleOptionFields();
                            $('#question_type').change(function() {
                                toggleOptionFields();
                            });

                            function toggleOptionFields() {
                                var selectedQuestionType = $('#question_type').val();
                                if (selectedQuestionType === "FreeText") {
                                    // Hide the option fields
                                    $('.project_detailSection').hide();
                                } else {
                                    $('.project_detailSection').show();
                                }
                            }
                        });
                    </script>
                    <script type="text/javascript">
                        $('#addMore').click(function() {
                            var count = $('#add_more_count').val();
                            var new_count = parseInt(count) + parseInt(1);
                            $.ajax({
                                url: '{{ URL('admin/questions/add-more-option') }}',
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


                        // $(function() {
                        //     $('#row_dim').hide();
                        //     if ($('#question_type').val() == 'radio' || $('#question_type').val() == 'checkbox' || $(
                        //             '#question_type').val() == 'select') {
                        //         $('.question_type').show();
                        //     }

                        // });
                        // $('#question_type').change(function() {
                        //     if ($('#question_type').val() == 'radio' || $('#question_type').val() == 'checkbox' || $(
                        //             '#question_type').val() == 'select') {
                        //                 $('.option').val("");
                        //         $('#row_dim').show();
                        //     } else {
                        //         $('.option').val("");
                        //         $('#row_dim').hide();
                        //     }
                        // });
                    </script>
                @stop
