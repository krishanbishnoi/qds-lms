@extends('admin.layouts.default')
@section('content')
    @php
        $flag = 0;
        $heading = 'Add';
        if (isset($model) && !empty($model)) {
            $flag = 1;
            $heading = 'Update';
        }
    @endphp
    <!-- JS & CSS library of MultiSelect plugin -->
    <script src="https://cdn.ckeditor.com/4.15.0/standard-all/ckeditor.js"></script>

    <div class="content-wrapper">
        @if ($errors->has('document_errors'))
            <div class="alert alert-danger">
                @foreach ($errors->get('document_errors') as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        <div class="page-header">
            <h2 class="page-title">{{ $heading }} New {{ $sectionNameSingular }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>Dashboard</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('Training.index') }}">{{ $sectionName }}</a></li>
                    <li class="breadcrumb-item active">Add New {{ $sectionNameSingular }}</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        @if ($flag == 1)
                            {{ Form::model($model, ['url' => route('Training.add'), 'class' => 'mws-form', 'files' => true, 'autocomplete' => 'off']) }}
                            {{ Form::hidden('id', null) }}
                        @else
                            {{ Form::open(['role' => 'form', 'route' => 'Training.add', 'class' => 'mws-form', 'files' => true, 'autocomplete' => 'off']) }}
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('category_id') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label('category', trans('Select Training Category') . '<span class="requireRed">*</span>', [
                                                'class' => 'mws-form-label',
                                            ]),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::select('category_id', $trainingCategory, null, ['class' => 'form-control', 'placeholder' => 'Please select training category']) }}
                                            @error('category_id')
                                                <div class="error-message help-inline">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('type') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label('type', trans('Training Type') . '<span class="requireRed">*</span>', ['class' => 'mws-form-label']),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::select('type', $TrainingType, null, ['class' => 'form-control', 'placeholder' => 'Please select training type', 'id' => 'training_type']) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('type'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('training_title') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label('training_title', trans('Title') . '<span class="requireRed">*</span>', ['class' => 'mws-form-label']),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::text('training_title', $model->title ?? null, ['class' => 'form-control']) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('training_title'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('thumbnail') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! HTML::decode(
                                            Form::label('thumbnail', trans('Thumbnail') . '<span class="requireRed">*</span>', ['class' => 'mws-form-label']),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::file('thumbnail', ['class' => 'form-control']) }}
                                            @if (isset($model))
                                                <br />
                                                <img height="100" width="100"
                                                    src="{{ TRAINING_DOCUMENT_URL . $model->thumbnail }}" />
                                            @endif
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('thumbnail'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('start_date_time') ? 'has-error' : ''; ?>" id="live_date">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label('start_date_time', trans('Start Date') . '<span class="requireRed">*</span>', [
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
                                            Form::label('end_date_time', trans('End Date') . '<span class="requireRed">*</span>', [
                                                'class' => 'mws-form-label',
                                            ]),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::text('end_date_time',null, ['class' => 'form-control small', 'id' => 'end_date_time']) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('end_date_time'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="projectDetailSection" style="display: none;">
                            {!! Html::decode(
                                Form::label('training_documents', trans('Briefings Documents') . '<span class="requireRed">*</span>', [
                                    'class' => 'mws-form-label',
                                ]),
                            ) !!}
                            <div class="project_detailSection">
                                <?php $i = 0; ?>
                                @if (isset($breflingsDocument) && !$breflingsDocument->isEmpty())
                                    @foreach ($breflingsDocument as $document)
                                        <?php $i++; ?>
                                        <div
                                            class="projectDetailsInnerSection_{{ $i }} ace_left_sec mb-4 border p-3 rounded bg-light">
                                            {{ Form::hidden('data[' . $i . '][entryID]', $document->id ?? null) }}
                                            <div class="row g-3 align-items-start">
                                                {{-- Title --}}
                                                <div class="col-md-2">
                                                    <div
                                                        class="form-group {{ $errors->first('title') ? 'has-error' : '' }}">
                                                        {!! Html::decode(Form::label('title', 'Title <span class="requireRed">*</span>', ['class' => 'form-label'])) !!}
                                                        {{ Form::text("data[$i][title]", $document->title ?? '', ['class' => 'form-control']) }}
                                                        <div class="error-message help-inline">
                                                            {{ $errors->first('title') }}</div>
                                                    </div>
                                                </div>

                                                {{-- Document Upload --}}
                                                <div class="col-md-2">
                                                    <div
                                                        class="form-group {{ $errors->first('document') ? 'has-error' : '' }}">
                                                        {!! Html::decode(
                                                            Form::label('document', 'Document <span class="requireRed">*</span>', ['class' => 'form-label']),
                                                        ) !!}
                                                        {{ Form::file("data[$i][document]", ['class' => 'form-control']) }}
                                                        @if ($document->document)
                                                            {{ Form::hidden("data[$i][existing_document]", $document->document) }}
                                                        @endif
                                                        <div class="error-message help-inline">
                                                            {{ $errors->first('document') }}</div>
                                                    </div>
                                                </div>
                                                {{-- Length --}}
                                                @php
                                                    $fieldName = "data.$i.length";
                                                    $lengthInMinutes = isset($document->length)
                                                        ? (int) ($document->length / 60)
                                                        : '';
                                                @endphp

                                                <div class="col-md-2">
                                                    <div
                                                        class="form-group {{ $errors->has($fieldName) ? 'has-error' : '' }}">
                                                        {!! Html::decode(
                                                            Form::label($fieldName, 'Reading Time (In Minutes)<span class="requireRed">*</span>', ['class' => 'form-label']),
                                                        ) !!}
                                                        {{ Form::text("data[$i][length]", $lengthInMinutes, ['class' => 'form-control']) }}
                                                        <div class="error-message help-inline">
                                                            {{ $errors->first($fieldName) }}
                                                        </div>
                                                    </div>
                                                </div>

                                                {{ Form::hidden('data[' . $i . '][entryID]', $document->id ?? null) }}
                                                {{-- Preview --}}
                                                <div class="col-md-3">
                                                    @if ($document->type == 'audio' && !empty($document->document))
                                                        <audio controls class="w-100 mt-2">
                                                            <source src="{{ TRAINING_DOCUMENT_URL . $document->document }}"
                                                                type="audio/{{ pathinfo($document->document, PATHINFO_EXTENSION) }}">
                                                        </audio>
                                                    @elseif ($document->type == 'image')
                                                        <img src="{{ TRAINING_DOCUMENT_URL . $document->document }}"
                                                            class="img-fluid mt-2" />
                                                    @elseif ($document->document_type == 'pdf')
                                                        <iframe src="{{ TRAINING_DOCUMENT_URL . $document->document }}"
                                                            class="w-100 mt-2" style="height: 150px;"></iframe>
                                                    @elseif (in_array($document->document_type, ['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx']))
                                                        <iframe
                                                            src="https://view.officeapps.live.com/op/embed.aspx?src={{ asset('training_document/' . $document->document) }}"
                                                            class="w-100 mt-2" style="height: 150px;"></iframe>
                                                    @elseif ($document->type == 'video')
                                                        <video controls class="w-100 mt-2">
                                                            <source src="{{ TRAINING_DOCUMENT_URL . $document->document }}"
                                                                type="video/mp4">
                                                        </video>
                                                    @endif
                                                </div>

                                                {{-- Add/Remove Button --}}
                                                <div class="col-md-1 mt-4">
                                                    @if ($i == 1)
                                                        <a href="javascript:void(0);" id="addMore"
                                                            class="btn btn-success btn-sm w-100">Add</a>
                                                    @else
                                                        <a href="javascript:void(0);" class="btn btn-danger btn-sm w-100"
                                                            onclick="removeTableEntry('{{ $i }}', {{ $document->id ?? 'null' }})">Remove</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <input type="hidden" name="count" value="{{ $i }}" id="add_more_count">
                                @else
                                    <?php $i = 1; ?>
                                    <div class="projectDetailsInnerSection_1 ace_left_sec mb-4 border p-3 rounded bg-light">
                                        <div class="row g-3 align-items-start">
                                            {{-- Title --}}
                                            <div class="col-md-2">
                                                <div class="form-group {{ $errors->first('title') ? 'has-error' : '' }}">
                                                    {!! Html::decode(Form::label('title', 'Title <span class="requireRed">*</span>', ['class' => 'form-label'])) !!}
                                                    {{ Form::text("data[$i][title]", '', ['class' => 'form-control']) }}
                                                    <div class="error-message help-inline">{{ $errors->first('title') }}
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Document Upload --}}
                                            <div class="col-md-2">
                                                <div
                                                    class="form-group {{ $errors->first('document') ? 'has-error' : '' }}">
                                                    {!! Html::decode(
                                                        Form::label('document', 'Document <span class="requireRed">*</span>', ['class' => 'form-label']),
                                                    ) !!}
                                                    {{ Form::file("data[$i][document]", ['class' => 'form-control']) }}
                                                    <div class="error-message help-inline">
                                                        {{ $errors->first('document') }}</div>
                                                </div>
                                            </div>

                                            {{-- Length --}}
                                            <div class="col-md-2">
                                                <div class="form-group {{ $errors->first('length') ? 'has-error' : '' }}">
                                                    {!! Html::decode(
                                                        Form::label('length', 'Reading Time (In Minutes)<span class="requireRed">*</span>', ['class' => 'form-label']),
                                                    ) !!}
                                                    {{ Form::text("data[$i][length]", '', ['class' => 'form-control']) }}
                                                    <div class="error-message help-inline">{{ $errors->first('length') }}
                                                    </div>
                                                </div>
                                            </div>


                                            {{-- Add Button --}}
                                            <div class="col-md-1 mt-4">
                                                <a href="javascript:void(0);" id="addMore"
                                                    class="btn btn-success btn-sm w-100">Add</a>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="count" value="1" id="add_more_count">
                                @endif
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group <?php echo $errors->first('description') ? 'has-error' : ''; ?>">
                                    {!! Html::decode(
                                        Form::label('description', trans('Description') . '<span class="requireRed">*</span>', [
                                            'class' => 'mws-form-label',
                                        ]),
                                    ) !!}
                                    <div class="mws-form-item">
                                        {{ Form::textarea('description', null, ['class' => 'form-control textarea_resize', 'id' => 'description', 'rows' => 3, 'cols' => 3]) }}
                                        <span class="error-message descriptionTypeError help-inline">
                                            <?php echo $errors->first('description') ? $errors->first('description') : ''; ?>
                                        </span>
                                    </div>
                                    <script>
                                        var description = CKEDITOR.replace('description', {
                                            extraAllowedContent: 'div',
                                            height: 300
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mws-button-row">
                                    <input type="submit" value="{{ trans('Save') }}" class="btn btn-danger">
                                    <a href="{{ route('Training.add') }}" class="btn btn-primary reset_form">
                                        {{ trans('Clear') }}</a>
                                    <a href="{{ route('Training.index') }}" class="btn btn-info">
                                        {{ trans('Cancel') }}</a>
                                </div>
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

        .document-row {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
        }

        .document-field {
            flex: 1;
            min-width: 300px;
            padding: 0 10px;
            margin-bottom: 15px;
        }

        .document-preview {
            flex: 1;
            min-width: 100%;
            padding: 0 10px;
            margin-bottom: 15px;
        }

        .mws-form-label {
            display: block;
            margin-bottom: 5px;
        }

        .mws-form-item {
            margin-bottom: 10px;
        }

        .error-message {
            color: red;
            font-size: 12px;
        }

        .uploadAudio input[type="file"] {
            display: none;
        }

        .uploadAudio label {
            cursor: pointer;
        }

        .forange {
            color: #004183;
        }

        .grayclr {
            background-color: #f8f9fa;
        }

        .btn-outline-danger {
            border-color: #dc3545;
            color: #dc3545;
        }

        .btn-outline-primary {
            border-color: #0d6efd;
            color: #0d6efd;
        }

        .btn-outline-success {
            border-color: #198754;
            color: #198754;
        }
    </style>

    <script type="text/javascript">
        // Add More Functionality
        $(document).ready(function() {
            // Add new row
            $('#addMore').click(function() {
                var count = parseInt($('#add_more_count').val());
                var newCount = count + 1;

                // Clone the first row
                var newRow = $('.projectDetailsInnerSection_1').clone();

                // Update IDs, names, and reset values
                newRow.attr('class', 'projectDetailsInnerSection_' + newCount +
                    ' ace_left_sec mb-4 border p-3 rounded bg-light');
                newRow.find('input, select, textarea').each(function() {
                    var name = $(this).attr('name');
                    if (name) {
                        name = name.replace('[1]', '[' + newCount + ']');
                        $(this).attr('name', name);
                        $(this).val(''); // Clear values
                    }

                    // Reset file inputs
                    if ($(this).attr('type') == 'file') {
                        $(this).val('');
                    }
                });

                // Update the Remove button
                newRow.find('#addMore').remove();
                newRow.find('.col-md-1.mt-4').html(
                    '<a href="javascript:void(0);" class="btn btn-danger btn-sm w-100" onclick="removeTableEntry(\'' +
                    newCount + '\')">Remove</a>'
                );

                // Reset preview section
                newRow.find('.col-md-3').html('');

                // Insert the new row
                newRow.insertAfter('.projectDetailsInnerSection_' + count);

                // Update the count
                $('#add_more_count').val(newCount);
            });
        });


        // Remove row function
        // Global variable to track deleted items
        let deletedItems = [];

        // Remove row function - now accepts entryId parameter
        function removeTableEntry(rowId, entryId = null) {
            // If this is an existing record (has an ID), add to deleted items
            if (entryId) {
                deletedItems.push(entryId);

                // Create hidden input for deleted items if it doesn't exist
                if (!$('#deletedItemsInput').length) {
                    $('.project_detailSection').append('<input type="hidden" id="deletedItemsInput" name="deleted_items">');
                }

                // Update the hidden input with comma-separated IDs
                $('#deletedItemsInput').val(deletedItems.join(','));
            }
            // Remove the row from the DOM
            $(`.projectDetailsInnerSection_${rowId}`).remove();

            // Update the count of remaining rows
            const count = $('[class^="projectDetailsInnerSection_"]').length;
            $('#add_more_count').val(count);
        }

        // Form submission handler to ensure deleted items are included
        $(document).ready(function() {
            $('form').on('submit', function() {
                // Ensure deleted items are included in the form data
                if (deletedItems.length > 0 && !$('#deletedItemsInput').length) {
                    $(this).append(
                        `<input type="hidden" name="deleted_items" value="${deletedItems.join(',')}">`);
                }
                return true;
            });
        });

        var isEditMode = {{ $flag == 1 ? 'true' : 'false' }};
        $(function() {

            const startDateTimeValue = $('#start_date_time').val();
            const endDateTimeValue = $('#end_date_time').val();

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
                defaultDate: startDateTimeValue || null,
                minDate: isEditMode ? false : moment() // ✅ Allow past dates in edit mode
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
                defaultDate: endDateTimeValue || null,
                minDate: isEditMode ? false : moment()
            });

            $("#start_date_time").on("dp.change", function(e) {
                $('#end_date_time').data("DateTimePicker").minDate(e.date);
            });

            $("#end_date_time").on("dp.change", function(e) {
                $('#start_date_time').data("DateTimePicker").maxDate(e.date);
            });
        });


        $(document).ready(function() {
            function toggleProjectDetailSection() {
                var trainingType = $('#training_type').val();
                if (trainingType == '6') {
                    $('#projectDetailSection').show();
                } else {
                    $('#projectDetailSection').hide();
                }
            }

            // On page load
            toggleProjectDetailSection();

            // On change
            $('#training_type').change(function() {
                toggleProjectDetailSection();
            });
        });
    </script>
@stop
