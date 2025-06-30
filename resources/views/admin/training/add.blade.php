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
                                            {{ Form::text('end_date_time', null, ['class' => 'form-control small', 'id' => 'end_date_time']) }}
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


                                                {{-- Audio Attach --}}
                                                <div class="col-md-2">
                                                    <div
                                                        class="form-group {{ $errors->first('document') ? 'has-error' : '' }}">
                                                        {!! Html::decode(
                                                            Form::label('audio_document', 'Audio Instruction <span class="requireRed">*</span>', ['class' => 'form-label']),
                                                        ) !!}
                                                        <div class="attchadomn d-flex align-items-center mb-3 mt-3"
                                                            data-bs-toggle="modal" data-bs-target="#recordtask"
                                                            role="button">
                                                            <div class="recordimg pe-1 d-flex align-items-center">
                                                                <i class="fas fa-microphone me-2"></i>
                                                                <div class="attchtext">
                                                                    <h6 class="fw-normal forange">Attach audio instruction
                                                                    </h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" id="recordtask" aria-hidden="true"
                                                    aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header grayclr">
                                                                <h1 class="modal-title fs-6 fw-semibold"
                                                                    id="exampleModalToggleLabel">
                                                                    Record Audio
                                                                </h1>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body m-3">
                                                                <div class="audio-controls" id="audio-controls">
                                                                    <div class="text-center">
                                                                        <div class="position-relative d-inline-block">
                                                                            <i class="fas fa-clock forange"
                                                                                style="font-size: 2rem;"></i>
                                                                            <span id="recordingDot"
                                                                                class="position-absolute top-0 start-100 translate-middle badge bg-danger rounded-circle blink"
                                                                                style="width: 8px; height: 8px; display: none;"></span>
                                                                        </div>
                                                                        <div id="playDuration"
                                                                            class="forange fw-semibold fs-4 mt-2">00:00
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="d-flex mt-2 align-items-center justify-content-center gap-3">

                                                                        <button type="button" id="recordButton"
                                                                            class="btn btn-outline-danger rounded-circle p-3"
                                                                            onclick="toggleRecording()">
                                                                            <i class="fas fa-microphone"></i>
                                                                        </button>
                                                                        <button disabled id="playButton" type="button"
                                                                            onclick="togglePlayback()"
                                                                            class="btn btn-outline-primary rounded-circle p-3">
                                                                            <i class="fas fa-play"></i>
                                                                        </button>
                                                                        <button type="button"
                                                                            class="btn btn-outline-success rounded-circle p-3"
                                                                            data-bs-dismiss="modal" aria-label="Close"
                                                                            id="saveButton">
                                                                            <i class="fas fa-check"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <audio id="audioPlayer"></audio>
                                                            </div>
                                                        </div>
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
                                                            <source
                                                                src="{{ TRAINING_DOCUMENT_URL . $document->document }}"
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
                                                            <source
                                                                src="{{ TRAINING_DOCUMENT_URL . $document->document }}"
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
                                    <input type="hidden" name="count" value="{{ $i }}"
                                        id="add_more_count">
                                @else
                                    <?php $i = 1; ?>
                                    <div
                                        class="projectDetailsInnerSection_1 ace_left_sec mb-4 border p-3 rounded bg-light">
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

                                            {{-- Audio Attach --}}
                                            <div class="col-md-2">
                                                {!! Html::decode(
                                                    Form::label('audio_document', 'Audio Instruction <span class="requireRed">*</span>', ['class' => 'form-label']),
                                                ) !!}
                                                <div class="attchadomn d-flex align-items-center mb-3 mt-3"
                                                    data-bs-toggle="modal" data-bs-target="#recordtask" role="button">
                                                    <div class="recordimg pe-1 d-flex align-items-center">
                                                        <i class="fas fa-microphone me-2"></i>
                                                        <div class="attchtext">
                                                            <h6 class="fw-normal forange">Attach audio instruction</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{ Form::hidden('audio_instruction', '') }}
                                            </div>
                                            <div class="modal fade" id="recordtask" aria-hidden="true"
                                                aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header grayclr">
                                                            <h1 class="modal-title fs-6 fw-semibold"
                                                                id="exampleModalToggleLabel">
                                                                Record Audio
                                                            </h1>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body m-3">
                                                            <div class="audio-controls" id="audio-controls">
                                                                <div class="text-center">
                                                                    <div class="position-relative d-inline-block">
                                                                        <i class="fas fa-clock forange"
                                                                            style="font-size: 2rem;"></i>
                                                                        <span id="recordingDot"
                                                                            class="position-absolute top-0 start-100 translate-middle badge bg-danger rounded-circle blink"
                                                                            style="width: 8px; height: 8px; display: none;"></span>
                                                                    </div>
                                                                    <div id="playDuration"
                                                                        class="forange fw-semibold fs-4 mt-2">00:00
                                                                    </div>
                                                                </div>

                                                                <div
                                                                    class="d-flex mt-2 align-items-center justify-content-center gap-3">
                                                                    <div class="uploadAudio">
                                                                        <input type="file" name="audio_instruction"
                                                                            id="audioFile" accept="audio/*">
                                                                        <label for="audioFile" class="m-0">
                                                                            <i class="fas fa-cloud-upload-alt fa-2x"></i>
                                                                        </label>
                                                                    </div>
                                                                    <button type="button" id="recordButton"
                                                                        class="btn btn-outline-danger rounded-circle p-3"
                                                                        onclick="toggleRecording()">
                                                                        <i class="fas fa-microphone"></i>
                                                                    </button>
                                                                    <button disabled id="playButton" type="button"
                                                                        onclick="togglePlayback()"
                                                                        class="btn btn-outline-primary rounded-circle p-3">
                                                                        <i class="fas fa-play"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn btn-outline-success rounded-circle p-3"
                                                                        data-bs-dismiss="modal" aria-label="Close"
                                                                        id="saveButton">
                                                                        <i class="fas fa-check"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <audio id="audioPlayer"></audio>
                                                        </div>
                                                    </div>
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
    </style>
    <script>
        let recorder;
        let recordedChunks = [];
        let audioContext = new(window.AudioContext || window.webkitAudioContext)();
        let playbackInterval;
        let stopMessageTimeout;
        let recordedAudioData;
        let isRecording = false;
        let isPlaying = false;

        function toggleRecording() {
            const recordButton = document.getElementById("recordButton");
            const playButton = document.getElementById("playButton");
            const statusMessage = document.getElementById("statusMessage");
            const playDuration = document.getElementById("playDuration");

            if (isRecording) {
                // Stop recording
                recorder.stop();
                recordButton.innerHTML = '<i class="fas fa-microphone"></i>';
                recordButton.classList.remove('btn-danger');
                recordButton.classList.add('btn-outline-danger');
                playButton.disabled = false;
                statusMessage.textContent = "Recording stopped";
                playDuration.style.display = "block";
                isRecording = false;

                // Hide the status message after 2 seconds
                setTimeout(() => {
                    statusMessage.textContent = "";
                }, 2000);
            } else {
                // Start recording
                navigator.mediaDevices.getUserMedia({
                        audio: true
                    })
                    .then(function(stream) {
                        recordedChunks = [];
                        recorder = new MediaRecorder(stream);

                        recorder.ondataavailable = function(event) {
                            recordedChunks.push(event.data);
                            recordedAudioData = event.data;
                        };

                        recorder.onstop = function() {
                            const audioBlob = new Blob(recordedChunks, {
                                type: 'audio/webm'
                            });
                            const audioUrl = URL.createObjectURL(audioBlob);
                            document.getElementById("audioPlayer").src = audioUrl;
                        };

                        recorder.start();
                        recordButton.innerHTML = '<i class="fas fa-stop"></i>';
                        recordButton.classList.remove('btn-outline-danger');
                        recordButton.classList.add('btn-danger');
                        playButton.disabled = true;
                        statusMessage.textContent = "Recording...";
                        playDuration.style.display = "none";
                        isRecording = true;

                        // Stop any current playback
                        stopPlayback();
                    })
                    .catch(function(err) {
                        console.error("Error accessing microphone:", err);
                        statusMessage.textContent = "Microphone access denied";
                    });
            }
        }

        function togglePlayback() {
            const playButton = document.getElementById("playButton");
            const audioPlayer = document.getElementById("audioPlayer");
            const playDuration = document.getElementById("playDuration");

            if (isPlaying) {
                stopPlayback();
            } else {
                if (recordedChunks.length === 0) {
                    console.error("No recording available.");
                    return;
                }

                // Start playback
                const blob = new Blob(recordedChunks, {
                    type: "audio/webm"
                });
                const audioURL = URL.createObjectURL(blob);
                audioPlayer.src = audioURL;
                audioPlayer.play();

                playButton.innerHTML = '<i class="fas fa-stop"></i>';
                playButton.classList.remove('btn-outline-primary');
                playButton.classList.add('btn-primary');
                isPlaying = true;

                // Update timer
                playbackInterval = setInterval(function() {
                    const duration = audioPlayer.currentTime;
                    const minutes = Math.floor(duration / 60);
                    const seconds = Math.floor(duration % 60);
                    playDuration.textContent =
                        minutes.toString().padStart(2, '0') + ":" +
                        seconds.toString().padStart(2, '0');
                }, 100);

                audioPlayer.onended = function() {
                    stopPlayback();
                };
            }
        }

        function stopPlayback() {
            const playButton = document.getElementById("playButton");
            const audioPlayer = document.getElementById("audioPlayer");
            const playDuration = document.getElementById("playDuration");

            audioPlayer.pause();
            audioPlayer.currentTime = 0;
            playButton.innerHTML = '<i class="fas fa-play"></i>';
            playButton.classList.remove('btn-primary');
            playButton.classList.add('btn-outline-primary');
            playDuration.textContent = "00:00";
            clearInterval(playbackInterval);
            isPlaying = false;
        }

        // Handle modal close
        document.getElementById("recordtask").addEventListener("hidden.bs.modal", function() {
            if (isRecording) {
                recorder.stop();
                isRecording = false;
            }
            stopPlayback();
        });

        // Handle file upload
        document.getElementById("audioFile").addEventListener("change", function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const audioPlayer = document.getElementById("audioPlayer");
            const playButton = document.getElementById("playButton");
            const recordButton = document.getElementById("recordButton");

            recordedChunks = [file];
            audioPlayer.src = URL.createObjectURL(file);
            playButton.disabled = false;
            recordButton.disabled = false;
        });
    </script>

    <style>
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
                minDate: moment()
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
                minDate: moment()
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
