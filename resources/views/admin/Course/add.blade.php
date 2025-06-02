@extends('admin.layouts.default')
@section('content')
    <!-- JS & CSS library of MultiSelect plugin -->
    {{-- <script src="https://phpcoder.tech/multiselect/js/jquery.multiselect.js"></script>
<link rel="stylesheet" href="https://phpcoder.tech/multiselect/css/jquery.multiselect.css"> --}}


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
            <h2 class="page-title">Add New {{ $sectionNameSingular }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>Dashboard</a>
                    </li>
                    <li class="breadcrumb-item"> <a href='{{ route('Test.index') }}'>Tests</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route($modelName . '.index', $training_id) }}">{{ $sectionName }}</a></li>
                    <li class="breadcrumb-item active">Add New {{ $sectionNameSingular }}</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        @if ($flag == 1)
                            {{ Form::model($model, ['url' => route('Course.add', $training_id), 'class' => 'mws-form', 'files' => true, 'autocomplete' => 'off']) }}
                            {{ Form::hidden('id', null) }}
                        @else
                            {{ Form::open(['role' => 'form', route('Course.add', $training_id), 'class' => 'mws-form', 'files' => true, 'autocomplete' => 'off']) }}
                        @endif
                        <input type="hidden" name="training_id" value="{{ $training_id }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mws-panel-body no-padding tab-content">
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
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('test_id') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label('test_id', trans('Select Test') . '<span class=""> *</span>', ['class' => 'mws-form-label']),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::select('test_id', ['' => 'Don\'t want test in this course'] + $test, null, ['class' => 'form-control']) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('test_id'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('skip') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label(
                                                'skip',
                                                trans('Skip Video') .
                                                    '<span class="requireRed"> *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </span>',
                                                ['class' => 'mws-form-label'],
                                            ),
                                        ) !!}
                                        <div class="mws-form-item">
                                            <input class="form-check-input" type="radio" name="skip" id="skip_no"
                                                value="0" {{ isset($model) && $model->skip == 0 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="skip_no">yes</label>

                                            <input class="form-check-input" type="radio" name="skip" id="skip_yes"
                                                value="1" {{ isset($model) && $model->skip == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="skip_yes">no</label>

                                            <div class="error-message help-inline">
                                                {{ $errors->first('skip') }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            {!! Html::decode(
                                Form::label(
                                    'training_documents',
                                    trans('Training Documents') .
                                        '<span
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            class="requireRed"> * </span>',
                                    ['class' => 'mws-form-label'],
                                ),
                            ) !!}
                            <div class="project_detailSection">
                                <?php $i = 0; ?>
                                @if (isset($documents) && !$documents->isEmpty())
                                    @foreach ($documents as $document)
                                        <?php $i++; ?>
                                        <div class="projectDetailsInnerSection_<?php echo $i; ?> ace_left_sec">
                                            <table>
                                                <tr>
                                                    <td width="700px">
                                                        <div class="form-group <?php echo $errors->first('title') ? 'has-error' : ''; ?>">
                                                            {!! Html::decode(
                                                                Form::label('title', trans('Title') . '<span class="requireRed">*</span>', ['class' => 'mws-form-label']),
                                                            ) !!}
                                                            <div class="mws-form-item">
                                                                {{ Form::text('data[' . $i . '][title]', isset($document->title) ? $document->title : '', ['class' => 'form-control']) }}
                                                                <div class="error-message help-inline">
                                                                    <?php echo $errors->first('title'); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td width="700px">
                                                        <div class="form-group <?php echo $errors->first('document') ? 'has-error' : ''; ?>">
                                                            {!! Html::decode(
                                                                Form::label('document', trans('Document') . '<span class="requireRed">*</span>', ['class' => 'mws-form-label']),
                                                            ) !!}
                                                            <div class="mws-form-item">
                                                                {{ Form::file('data[' . $i . '][document]', ['class' => 'form-control']) }}
                                                                @if ($document->document)
                                                                    {{ Form::hidden('data[' . $i . '][existing_document]', $document->document) }}
                                                                @endif
                                                                <div class="error-message help-inline">
                                                                    <?php echo $errors->first('document'); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td width="700px">
                                                        <div class="form-group <?php echo $errors->first('length') ? 'has-error' : ''; ?>">
                                                            {!! Html::decode(
                                                                Form::label('length', trans('Length') . '<span class="requireRed">*</span>', ['class' => 'mws-form-label']),
                                                            ) !!}
                                                            <div class="mws-form-item">
                                                                {{ Form::text('data[' . $i . '][length]', isset($document->length) ? $document->length : '', ['class' => 'form-control']) }}
                                                                <div class="error-message help-inline">
                                                                    <?php echo $errors->first('length'); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td width="700px">
                                                        <div>
                                                            @if ($document->type == 'image')
                                                                @if (config('TRAINING_DOCUMENT_URL') . $document->document != '')
                                                                    <br />
                                                                    <img height="100%" width="100%"
                                                                        src="{{ TRAINING_DOCUMENT_URL . $document->document }}" />
                                                                @endif
                                                            @elseif ($document->type == 'doc' && $document->document_type == 'pdf')
                                                                @if (config('TRAINING_DOCUMENT_URL') . $document->document != '')
                                                                    <br />
                                                                    <iframe
                                                                        src="{{ TRAINING_DOCUMENT_URL . $document->document }}"
                                                                        style="width: 100%; height: 500px;"></iframe>
                                                                @endif
                                                            @elseif (($document->type == 'doc' && $document->document_type == 'xls') || $document->document_type == 'xlsx')
                                                                @if (config('TRAINING_DOCUMENT_URL') . $document->document != '')
                                                                    <br />
                                                                    <iframe
                                                                        src="https://view.officeapps.live.com/op/embed.aspx?src={{ asset('training_document/' . $document->document) }}"
                                                                        style="width: 100%; height: 500px;" frameborder="0">
                                                                    </iframe>
                                                                @endif
                                                            @elseif (($document->type == 'doc' && $document->document_type == 'doc') || $document->document_type == 'docx')
                                                                @if (config('TRAINING_DOCUMENT_URL') . $document->document != '')
                                                                    <br />
                                                                    <iframe
                                                                        src="https://view.officeapps.live.com/op/embed.aspx?src={{ asset('training_document/' . $document->document) }}"
                                                                        style="width: 100%; height: 500px;" frameborder="0">
                                                                    </iframe>
                                                                @endif
                                                            @elseif (($document->type == 'doc' && $document->document_type == 'ppt') || $document->document_type == 'pptx')
                                                                @if (config('TRAINING_DOCUMENT_URL') . $document->document != '')
                                                                    <br />
                                                                    <!-- direct view from iframe -->
                                                                    <iframe
                                                                        src="https://view.officeapps.live.com/op/embed.aspx?src={{ asset('training_document/' . $document->document) }}"
                                                                        style="width: 100%; height: 500px;" frameborder="0">
                                                                    </iframe>
                                                                @endif
                                                            @elseif($document->type == 'video')
                                                                @if (config('TRAINING_DOCUMENT_URL') . $document->document != '')
                                                                    <br />
                                                                    <video height="100%" width="100%" controls>
                                                                        <source
                                                                            src="{{ TRAINING_DOCUMENT_URL . $document->document }}"
                                                                            type="video/mp4">
                                                                        Your browser does not support the video tag.
                                                                    </video>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td style="padding-top: 20px;" width="25px">
                                                        <div class="form-group">
                                                            <input type="hidden" name="data[<?php echo $i; ?>][entryID]"
                                                                id="entryID_<?php echo $i; ?>"
                                                                value="{{ $document->id }}">
                                                            @if ($i == 1)
                                                                <a href="javascript:void(0);" id="addMore"
                                                                    class="btn btn-primary add_new_btn" value="Add More">Add
                                                                    More</a>
                                                            @else
                                                                <a href="javascript:void(0);" class="btn btn-danger"
                                                                    onclick="removeTableEntry('<?php echo $i; ?>')"
                                                                    id="remove_<?php echo $i; ?>">Remove</a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    @endforeach
                                    <input type="hidden" name="count" value="<?php echo $i; ?>"
                                        id="add_more_count">
                                @else
                                    <div class="projectDetailsInnerSection_1 ace_left_sec">
                                        <table>
                                            <tr>
                                                <td width="700px">
                                                    <div class="form-group <?php echo $errors->first('title') ? 'has-error' : ''; ?>">
                                                        {!! Html::decode(
                                                            Form::label('title', trans('Title') . '<span class="requireRed">*</span>', ['class' => 'mws-form-label']),
                                                        ) !!}
                                                        <div class="mws-form-item">
                                                            {{ Form::text('data[1][title]', '', ['class' => 'form-control title']) }}
                                                            <div class="error-message help-inline">
                                                                <?php echo $errors->first('title'); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td width="700px">
                                                    <div class="form-group <?php echo $errors->first('document') ? 'has-error' : ''; ?>">
                                                        {!! Html::decode(
                                                            Form::label('document', trans('Document') . '<span class="requireRed">*</span>', ['class' => 'mws-form-label']),
                                                        ) !!}
                                                        <div class="mws-form-item">
                                                            {{ Form::file('data[1][document]', ['class' => 'form-control']) }}
                                                            <div class="error-message help-inline">
                                                                <?php echo $errors->first('document'); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td width="700px">
                                                    <div class="form-group <?php echo $errors->first('length') ? 'has-error' : ''; ?>">
                                                        {!! Html::decode(
                                                            Form::label('length', trans('Length') . '<span class="requireRed">*</span>', ['class' => 'mws-form-label']),
                                                        ) !!}
                                                        <div class="mws-form-item">
                                                            {{ Form::text('data[1][length]', '', ['class' => 'form-control']) }}
                                                            <div class="error-message help-inline">
                                                                <?php echo $errors->first('length'); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="padding-top: 23px;" width="10px">
                                                    <div class="form-group">
                                                        <a href="javascript:void(0);" id="addMore"
                                                            class="btn btn-primary add_new_btn add_more_new_supp"
                                                            value="Add More">Add More</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <input type="hidden" name="count" value="1" id="add_more_count">
                                @endif
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
                        <div class="mws-button-row">
                            <input type="submit" value="{{ trans('Save') }}" class="btn btn-danger">
                            <a href="{{ route('Course.add', $training_id) }}" class="btn btn-primary reset_form"><i
                                    class=\"icon-refresh\"></i>
                                {{ trans('Clear') }}</a>
                            <a href="{{ route('Course.index', $training_id) }}" class="btn btn-info"><i
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
                    $('#addMore').click(function() {
                        var count = $('#add_more_count').val();
                        var new_count = parseInt(count) + parseInt(1);
                        $.ajax({
                            url: '{{ URL('admin/courses/add-more-document') }}',
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




                    function removeTableEntry(id) {
                        bootbox.confirm("Are you sure want to remove this?",
                            function(result) {
                                if (result) {
                                    $('#loader_img').show();
                                    var entID = $('#entryID_' + id).val();
                                    $.ajax({
                                        url: '{{ URL('admin/courses/delete-more-document') }}',
                                        type: 'post',
                                        data: {
                                            'id': entID
                                        },
                                        data: {
                                            "_token": "{{ csrf_token() }}",
                                            'id': entID
                                        },
                                        async: false,
                                        success: function(r) {
                                            if (r == 1) {
                                                $('.projectDetailsInnerSection_' + id).remove();
                                            } else {
                                                alert('There is an error please try again.')
                                            }
                                            $('#loader_img').hide();
                                        }
                                    });
                                }
                            });
                    }
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

                            $('#row_dim').show();
                        } else {
                            $('.option').val("");
                            $('#row_dim').hide();
                        }
                    });
                </script>
            @stop
