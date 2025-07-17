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

                        {{-- <div id="projectDetailSection" style="display: none;">
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
                                                <div class="col-md-2">
                                                    <div
                                                        class="form-group {{ $errors->first('title') ? 'has-error' : '' }}">
                                                        {!! Html::decode(Form::label('title', 'Title <span class="requireRed">*</span>', ['class' => 'form-label'])) !!}
                                                        {{ Form::text("data[$i][title]", $document->title ?? '', ['class' => 'form-control']) }}
                                                        <div class="error-message help-inline">
                                                            {{ $errors->first('title') }}</div>
                                                    </div>
                                                </div>

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
                                            <div class="col-md-2">
                                                <div class="form-group {{ $errors->first('title') ? 'has-error' : '' }}">
                                                    {!! Html::decode(Form::label('title', 'Title <span class="requireRed">*</span>', ['class' => 'form-label'])) !!}
                                                    {{ Form::text("data[$i][title]", '', ['class' => 'form-control']) }}
                                                    <div class="error-message help-inline">{{ $errors->first('title') }}
                                                    </div>
                                                </div>
                                            </div>

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


                                            <div class="col-md-1 mt-4">
                                                <a href="javascript:void(0);" id="addMore"
                                                    class="btn btn-success btn-sm w-100">Add</a>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="count" value="1" id="add_more_count">
                                @endif
                            </div>

                        </div> --}}

                        <div id="projectDetailSection" style="display: none;">
                            {!! Html::decode(
                                Form::label('training_documents', trans('Briefings Documents') . '<span class="requireRed">*</span>', [
                                    'class' => 'mws-form-label',
                                ]),
                            ) !!}
                            <div class="project_detailSection">

                                <?php $i = 1; ?>
                                <div class="projectDetailsInnerSection_1 ace_left_sec mb-4 border p-3 rounded bg-light">
                                    <div class="row g-1 align-items-start">
                                        <!-- Type Selector -->
                                        {{-- <div class=""> --}}
                                        <div class=" col-md-6">
                                            {!! Html::decode(Form::label('type', 'Type <span class="requireRed">*</span>', ['class' => 'form-label'])) !!}
                                            <select name="data[1][type]" class="form-control type-selector"
                                                onchange="handleTypeChange(this)">
                                                <option value="document">Document</option>
                                                <option value="audio">Audio</option>
                                                <option value="both">Both</option>
                                            </select>
                                        </div>
                                        {{-- </div> --}}

                                        <!-- Document Fields (shown by default) -->
                                        <div class="document-fields col-md-12 row g-3 align-items-start">
                                            <!-- Title -->
                                            <div class=" col-md-4  {{ $errors->first('title') ? 'has-error' : '' }}">
                                                {!! Html::decode(
                                                    Form::label('title', 'Document Title <span class="requireRed">*</span>', ['class' => 'form-label']),
                                                ) !!}
                                                {{ Form::text('data[1][document_title]', '', ['class' => 'form-control']) }}
                                                <div class="error-message help-inline">
                                                    {{ $errors->first('title') }}</div>
                                            </div>

                                            <!-- Document Upload -->
                                            <div class="col-md-4  {{ $errors->first('document') ? 'has-error' : '' }}">
                                                {!! Html::decode(
                                                    Form::label('document', 'Document <span class="requireRed">*</span>', ['class' => 'form-label']),
                                                ) !!}
                                                {{ Form::file('data[1][document]', ['class' => 'form-control']) }}
                                                <div class="error-message help-inline">
                                                    {{ $errors->first('document') }}</div>
                                            </div>

                                            <!-- Reading Time -->
                                            <div
                                                class="col-md-4  {{ $errors->first('document_length') ? 'has-error' : '' }}">
                                                {!! Html::decode(
                                                    Form::label('document_length', 'Reading Time (Minutes)<span class="requireRed">*</span>', [
                                                        'class' => 'form-label',
                                                    ]),
                                                ) !!}
                                                {{ Form::text('data[1][document_length]', '', ['class' => 'form-control']) }}
                                                <div class="error-message help-inline">
                                                    {{ $errors->first('document_length') }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Audio Fields (hidden by default) -->
                                        <div class="audio-fields col-md-12 row g-3 align-items-start"
                                            style="display: none;">
                                            <!-- Audio Title -->
                                            <div class="col-md-4">
                                                <div class=" {{ $errors->first('audio_title') ? 'has-error' : '' }}">
                                                    {!! Html::decode(
                                                        Form::label('audio_title', 'Audio Title <span class="requireRed">*</span>', ['class' => 'form-label']),
                                                    ) !!}
                                                    {{ Form::text('data[1][audio_title]', '', ['class' => 'form-control']) }}
                                                    <div class="error-message help-inline">
                                                        {{ $errors->first('audio_title') }}</div>
                                                </div>
                                            </div>

                                            <!-- Audio Upload -->
                                            <div class="col-md-4">
                                                <div class="">
                                                    {{-- {!! Html::decode(
                                                            Form::label('audio_document', 'Audio Instruction <span class="requireRed">*</span>', ['class' => 'form-label']),
                                                        ) !!} --}}
                                                    <div class="attchadomn d-flex align-items-center mb-3 mt-3"
                                                        data-bs-toggle="modal" data-bs-target="#recordtask_1"
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

                                            <!-- Audio Reading Time -->
                                            <div class="col-md-4">
                                                <div class=" {{ $errors->first('audio_length') ? 'has-error' : '' }}">
                                                    {!! Html::decode(
                                                        Form::label('audio_length', 'Listening Time (Minutes)<span class="requireRed">*</span>', ['class' => 'form-label']),
                                                    ) !!}
                                                    {{ Form::text('data[1][audio_length]', '', ['class' => 'form-control']) }}
                                                    <div class="error-message help-inline">
                                                        {{ $errors->first('audio_length') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Add Button -->
                                        <div class="col-md-2 mt-4">
                                            <a href="javascript:void(0);" id="addMore"
                                                class="btn btn-success btn-sm w-100">Add More</a>
                                        </div>
                                    </div>

                                    <!-- Audio Modal -->
                                    <div class="modal fade" id="recordtask_1" aria-hidden="true"
                                        aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header grayclr">
                                                    <h1 class="modal-title fs-6 fw-semibold" id="exampleModalToggleLabel">
                                                        Record Audio
                                                    </h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body m-3">
                                                    <div class="audio-controls" id="audio-controls-1">
                                                        <div class="text-center">
                                                            <div class="position-relative d-inline-block">
                                                                <i class="fas fa-clock forange"
                                                                    style="font-size: 2rem;"></i>
                                                                <span id="recordingDot-1"
                                                                    class="position-absolute top-0 start-100 translate-middle badge bg-danger rounded-circle blink"
                                                                    style="width: 8px; height: 8px; display: none;"></span>
                                                            </div>
                                                            <div id="playDuration-1"
                                                                class="forange fw-semibold fs-4 mt-2">00:00</div>
                                                        </div>

                                                        <div
                                                            class="d-flex mt-2 align-items-center justify-content-center gap-3">
                                                            <div class="uploadAudio">
                                                                <input type="file" name="data[1][audio_document]"
                                                                    id="audioFile-1" accept="audio/*">
                                                                <label for="audioFile-1" class="m-0">
                                                                    <i class="fas fa-cloud-upload-alt fa-2x"></i>
                                                                </label>
                                                            </div>
                                                            <button type="button" id="recordButton-1"
                                                                class="btn btn-outline-danger rounded-circle p-3"
                                                                onclick="toggleRecording('1')">
                                                                <i class="fas fa-microphone"></i>
                                                            </button>
                                                            <button disabled id="playButton-1" type="button"
                                                                onclick="togglePlayback('1')"
                                                                class="btn btn-outline-primary rounded-circle p-3">
                                                                <i class="fas fa-play"></i>
                                                            </button>
                                                            <button type="button"
                                                                class="btn btn-outline-success rounded-circle p-3"
                                                                data-bs-dismiss="modal" aria-label="Close"
                                                                id="saveButton-1">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <audio id="audioPlayer-1"></audio>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="count" value="1" id="add_more_count">
                                {{-- @if (isset($breflingsDocument) && !$breflingsDocument->isEmpty())







                                    <?php
                                    $groupedDocuments = [];
                                    foreach ($breflingsDocument as $doc) {
                                        $groupIndex = $doc->group_index ?? count($groupedDocuments) + 1;
                                        $groupedDocuments[$groupIndex][] = $doc;
                                    }
                                    $i = 0;
                                    ?>

                                    @foreach ($groupedDocuments as $groupIndex => $documents)
                                        <?php
                                        $i++;
                                        $mainDoc = $documents[0];
                                        $audioDoc = isset($documents[1]) && $documents[1]->type == 'audio' ? $documents[1] : null;
                                        // Determine type based on doc_upload_type if available, otherwise fall back to previous logic
                                        $type = $mainDoc->doc_upload_type ?? (count($documents) > 1 ? 'both' : $mainDoc->type);
                                        ?>

                                        <div
                                            class="projectDetailsInnerSection_{{ $i }} ace_left_sec mb-4 border p-3 rounded bg-light">
                                            {{ Form::hidden('data[' . $i . '][entryID]', $mainDoc->id ?? null) }}
                                            @if ($audioDoc)
                                                {{ Form::hidden('data[' . $i . '][audio_entryID]', $audioDoc->id ?? null) }}
                                            @endif

                                            <div class="g-3 align-items-start">
                                                <!-- Type Selector -->
                                                <div class="col-md-6 form-group">
                                                    {!! Html::decode(Form::label('type', 'Type <span class="requireRed">*</span>', ['class' => 'form-label'])) !!}
                                                    <select name="data[{{ $i }}][type]"
                                                        class="form-control type-selector"
                                                        onchange="handleTypeChange(this)">
                                                        <option value="document"
                                                            {{ $type == 'document' ? 'selected' : '' }}>
                                                            Document</option>
                                                        <option value="audio" {{ $type == 'audio' ? 'selected' : '' }}>
                                                            Audio</option>
                                                        <option value="both" {{ $type == 'both' ? 'selected' : '' }}>
                                                            Both
                                                        </option>
                                                    </select>
                                                </div>

                                                <!-- Document Fields -->
                                                <div class="document-fields col-md-12 row g-3"
                                                    style="{{ $type == 'audio' ? 'display: none;' : '' }}">
                                                    <!-- Title -->
                                                    <div
                                                        class="col-md-3 form-group {{ $errors->first('title') ? 'has-error' : '' }}">
                                                        {!! Html::decode(
                                                            Form::label('title', 'Document Title <span class="requireRed">*</span>', ['class' => 'form-label']),
                                                        ) !!}
                                                        {{ Form::text("data[$i][document_title]", $mainDoc->title ?? '', ['class' => 'form-control']) }}
                                                        <div class="error-message help-inline">
                                                            {{ $errors->first('title') }}</div>
                                                    </div>

                                                    <!-- Document Upload -->
                                                    <div
                                                        class="col-md-3 form-group {{ $errors->first('document') ? 'has-error' : '' }}">
                                                        {!! Html::decode(
                                                            Form::label('document', 'Document <span class="requireRed">*</span>', ['class' => 'form-label']),
                                                        ) !!}
                                                        {{ Form::file("data[$i][document]", ['class' => 'form-control']) }}
                                                        @if ($mainDoc->document)
                                                            {{ Form::hidden("data[$i][existing_document]", $mainDoc->document) }}
                                                        @endif
                                                        <div class="error-message help-inline">
                                                            {{ $errors->first('document') }}</div>
                                                    </div>

                                                    <!-- Reading Time -->
                                                    <div
                                                        class="col-md-3 form-group {{ $errors->first('document_length') ? 'has-error' : '' }}">
                                                        {!! Html::decode(
                                                            Form::label('document_length', 'Reading Time (Minutes)<span class="requireRed">*</span>', [
                                                                'class' => 'form-label',
                                                            ]),
                                                        ) !!}
                                                        {{ Form::text("data[$i][document_length]", $mainDoc->length ? $mainDoc->length / 60 : '', ['class' => 'form-control']) }}
                                                        <div class="error-message help-inline">
                                                            {{ $errors->first('document_length') }}</div>
                                                    </div>

                                                    <!-- Preview -->


                                                    <!-- Audio Fields -->
                                                    <div class="audio-fields col-12 row g-3 align-items-start"
                                                        style="{{ $type == 'document' ? 'display: none;' : '' }}">
                                                        <!-- Audio Title -->
                                                        <div
                                                            class="col-md-4 form-group {{ $errors->first('audio_title') ? 'has-error' : '' }}">
                                                            {!! Html::decode(
                                                                Form::label('audio_title', 'Audio Title <span class="requireRed">*</span>', ['class' => 'form-label']),
                                                            ) !!}
                                                            {{ Form::text("data[$i][audio_title]", $audioDoc ? $audioDoc->title : '', ['class' => 'form-control']) }}
                                                            <div class="error-message help-inline">
                                                                {{ $errors->first('audio_title') }}</div>
                                                        </div>

                                                        <!-- Audio Upload -->
                                                        <div class="col-md-4 form-group">
                                                            {!! Html::decode(
                                                                Form::label('audio_document', 'Audio Instruction <span class="requireRed">*</span>', ['class' => 'form-label']),
                                                            ) !!}
                                                            @if ($audioDoc && $audioDoc->document)
                                                                <div class="mb-2">
                                                                    <audio controls class="w-100">
                                                                        <source
                                                                            src="{{ TRAINING_DOCUMENT_URL . $audioDoc->document }}"
                                                                            type="audio/{{ $audioDoc->document_type }}">
                                                                    </audio>
                                                                    {{ Form::hidden("data[$i][existing_audio_document]", $audioDoc->document) }}
                                                                    <div class="mt-2">
                                                                        <a href="javascript:void(0);"
                                                                            class="btn btn-sm btn-outline-danger"
                                                                            onclick="removeAudioFile(this, '{{ $i }}')">
                                                                            <i class="fas fa-trash"></i> Remove Audio
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="attchadomn d-flex align-items-center mb-3 mt-3"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#recordtask_{{ $i }}"
                                                                    role="button">
                                                                    <div class="recordimg pe-1 d-flex align-items-center">
                                                                        <i class="fas fa-microphone me-2"></i>
                                                                        <div class="attchtext">
                                                                            <h6 class="fw-normal forange">Attach audio
                                                                                instruction</h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <!-- Audio Reading Time -->
                                                        <div
                                                            class="col-md-4 form-group {{ $errors->first('audio_length') ? 'has-error' : '' }}">
                                                            {!! Html::decode(
                                                                Form::label('audio_length', 'Listening Time (Minutes)<span class="requireRed">*</span>', ['class' => 'form-label']),
                                                            ) !!}
                                                            {{ Form::text("data[$i][audio_length]", $audioDoc ? ($audioDoc->length ? $audioDoc->length / 60 : '') : '', ['class' => 'form-control']) }}
                                                            <div class="error-message help-inline">
                                                                {{ $errors->first('audio_length') }}</div>
                                                        </div>
                                                    </div>

                                                    <!-- Add/Remove Button -->
                                                    <div class="col-md-1 mt-4">
                                                        @if ($i == 1)
                                                            <a href="javascript:void(0);" id="addMore"
                                                                class="btn btn-success btn-sm w-100">Add More</a>
                                                        @else
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-danger btn-sm w-100"
                                                                onclick="removeTableEntry('{{ $i }}', [{{ $mainDoc->id ?? 'null' }}{{ $audioDoc ? ',' . $audioDoc->id : '' }}])">Remove</a>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Audio Modal -->
                                                <div class="modal fade" id="recordtask_{{ $i }}"
                                                    aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
                                                    tabindex="-1">
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
                                                                <div class="audio-controls"
                                                                    id="audio-controls-{{ $i }}">
                                                                    <div class="text-center">
                                                                        <div class="position-relative d-inline-block">
                                                                            <i class="fas fa-clock forange"
                                                                                style="font-size: 2rem;"></i>
                                                                            <span id="recordingDot-{{ $i }}"
                                                                                class="position-absolute top-0 start-100 translate-middle badge bg-danger rounded-circle blink"
                                                                                style="width: 8px; height: 8px; display: none;"></span>
                                                                        </div>
                                                                        <div id="playDuration-{{ $i }}"
                                                                            class="forange fw-semibold fs-4 mt-2">00:00
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="d-flex mt-2 align-items-center justify-content-center gap-3">
                                                                        <div class="uploadAudio">
                                                                            <input type="file"
                                                                                name="data[{{ $i }}][audio_document]"
                                                                                id="audioFile-{{ $i }}"
                                                                                accept="audio/*">
                                                                            <label for="audioFile-{{ $i }}"
                                                                                class="m-0">
                                                                                <i
                                                                                    class="fas fa-cloud-upload-alt fa-2x"></i>
                                                                            </label>
                                                                        </div>
                                                                        <button type="button"
                                                                            id="recordButton-{{ $i }}"
                                                                            class="btn btn-outline-danger rounded-circle p-3"
                                                                            onclick="toggleRecording('{{ $i }}')">
                                                                            <i class="fas fa-microphone"></i>
                                                                        </button>
                                                                        <button disabled
                                                                            id="playButton-{{ $i }}"
                                                                            type="button"
                                                                            onclick="togglePlayback('{{ $i }}')"
                                                                            class="btn btn-outline-primary rounded-circle p-3">
                                                                            <i class="fas fa-play"></i>
                                                                        </button>
                                                                        <button type="button"
                                                                            class="btn btn-outline-success rounded-circle p-3"
                                                                            data-bs-dismiss="modal" aria-label="Close"
                                                                            id="saveButton-{{ $i }}">
                                                                            <i class="fas fa-check"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <audio id="audioPlayer-{{ $i }}"></audio>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    @endforeach
                                    <input type="hidden" name="count" value="{{ $i }}"
                                        id="add_more_count">
                                @endif --}}

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


                        @if (isset($breflingsDocument) && !$breflingsDocument->isEmpty())
                            <div class="col-md-12 row mt-3">
                                @foreach ($breflingsDocument as $mainDoc)
                                    <div class="col-md-3 mt-3 position-relative document-preview-container">
                                        <!-- Delete button with better styling -->
                                        <form action="{{ route('training.document.delete', $mainDoc->id) }}"
                                            method="POST" class="position-absolute top-0 end-0 m-1 delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-danger btn-xs rounded-circle p-1 border-0 shadow-sm"
                                                onclick="return confirm('Are you sure you want to delete this document?')"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Delete document">
                                                <i class="fas fa-trash fa-sm"></i>
                                            </button>
                                        </form>

                                        @if ($mainDoc->document)
                                            <div class="document-preview-content border rounded p-2 bg-light">
                                                @if ($mainDoc->type == 'image')
                                                    <img src="{{ TRAINING_DOCUMENT_URL . $mainDoc->document }}"
                                                        class="img-fluid"
                                                        style="max-height: 180px; width: 100%; object-fit: contain;" />
                                                @elseif ($mainDoc->document_type == 'pdf')
                                                    <iframe src="{{ TRAINING_DOCUMENT_URL . $mainDoc->document }}"
                                                        class="w-100" style="height: 250px;"></iframe>
                                                @elseif (in_array($mainDoc->document_type, ['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx']))
                                                    <iframe
                                                        src="https://view.officeapps.live.com/op/embed.aspx?src={{ asset('training_document/' . $mainDoc->document) }}"
                                                        class="w-100" style="height: 250px;"></iframe>
                                                @elseif ($mainDoc->type == 'video')
                                                    <video controls class="w-100" style="max-height: 250px;">
                                                        <source src="{{ TRAINING_DOCUMENT_URL . $mainDoc->document }}"
                                                            type="video/{{ $mainDoc->document_type }}">
                                                    </video>
                                                @elseif ($mainDoc->type == 'audio')
                                                    <div class="audio-container text-center p-2">
                                                        <i class="fas fa-music fa-2x mb-2 text-muted"></i>
                                                        <audio controls class="w-100">
                                                            <source src="{{ TRAINING_DOCUMENT_URL . $mainDoc->document }}"
                                                                type="audio/{{ $mainDoc->document_type }}">
                                                            Your browser does not support the audio element.
                                                        </audio>
                                                    </div>
                                                @endif
                                                <div class="mt-2 text-center small text-muted">
                                                    {{ $mainDoc->title }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <style>
                                .delete-form {
                                    z-index: 10;
                                }

                                .btn-xs {
                                    padding: 0.15rem 0.25rem;
                                    font-size: 0.75rem;
                                    line-height: 1;
                                }

                                .document-preview-container {
                                    padding: 5px;
                                }

                                .document-preview-content {
                                    height: 100%;
                                    transition: all 0.3s ease;
                                }

                                .document-preview-content:hover {
                                    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
                                }

                                .audio-container {
                                    background-color: #f8f9fa;
                                    border-radius: 4px;
                                }
                            </style>

                            <script>
                                // Initialize tooltips
                                document.addEventListener('DOMContentLoaded', function() {
                                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                                    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                                        return new bootstrap.Tooltip(tooltipTriggerEl)
                                    })
                                });
                            </script>
                        @endif

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


    {{-- <script>
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
    </script> --}}


    <script>
        // Function to handle type selection change
        function handleTypeChange(selectElement) {
            const card = selectElement.closest('.ace_left_sec');
            const type = selectElement.value;
            const documentFields = card.querySelector('.document-fields');
            const audioFields = card.querySelector('.audio-fields');

            if (type === 'document') {
                documentFields.style.display = 'flex';
                audioFields.style.display = 'none';
            } else if (type === 'audio') {
                documentFields.style.display = 'none';
                audioFields.style.display = 'flex';
            } else if (type === 'both') {
                documentFields.style.display = 'flex';
                audioFields.style.display = 'flex';
            }
        }

        // Add more functionality
        $(document).on('click', '#addMore', function() {
            const count = parseInt($('#add_more_count').val()) + 1;
            $('#add_more_count').val(count);

            const newCard = $('.projectDetailsInnerSection_1').clone();
            newCard.removeClass('projectDetailsInnerSection_1').addClass(`projectDetailsInnerSection_${count}`);

            // Update all IDs and names in the cloned card
            newCard.find('[id]').each(function() {
                const oldId = $(this).attr('id');
                if (oldId) {
                    $(this).attr('id', oldId.replace('1', count));
                }
            });

            newCard.find('[name]').each(function() {
                const oldName = $(this).attr('name');
                if (oldName) {
                    $(this).attr('name', oldName.replace('[1]', `[${count}]`));
                }
            });

            newCard.find('[for]').each(function() {
                const oldFor = $(this).attr('for');
                if (oldFor) {
                    $(this).attr('for', oldFor.replace('1', count));
                }
            });

            // Update modal target
            newCard.find('[data-bs-target]').each(function() {
                const oldTarget = $(this).attr('data-bs-target');
                if (oldTarget) {
                    $(this).attr('data-bs-target', oldTarget.replace('1', count));
                }
            });

            // Reset values
            newCard.find('input[type="text"], input[type="file"]').val('');
            newCard.find('audio').attr('src', '');

            // Change button to Remove
            const addButton = newCard.find('#addMore');
            addButton.removeAttr('id').text('Remove').removeClass('btn-success').addClass('btn-danger')
                .attr('onclick', `removeTableEntry('${count}', null)`);

            // Append the new card
            $('.project_detailSection').append(newCard);
        });

        // Remove card functionality
        function removeTableEntry(index, id) {
            if (id) {
                // If this is an existing entry, add to a delete list
                if (!$('#deletedEntries').length) {
                    $('.project_detailSection').append(
                        '<input type="hidden" id="deletedEntries" name="deletedEntries" value="">');
                }
                const current = $('#deletedEntries').val();
                $('#deletedEntries').val(current ? `${current},${id}` : id);
            }

            $(`.projectDetailsInnerSection_${index}`).remove();

            // Update count
            const count = parseInt($('#add_more_count').val()) - 1;
            $('#add_more_count').val(count);
        }

        // Audio recording functions (updated to work with multiple cards)
        let recorders = {};
        let recordedChunks = {};
        let audioContexts = {};
        let playbackIntervals = {};
        let isRecording = {};
        let isPlaying = {};

        function toggleRecording(index) {
            const recordButton = document.getElementById(`recordButton-${index}`);
            const playButton = document.getElementById(`playButton-${index}`);
            const recordingDot = document.getElementById(`recordingDot-${index}`);
            const playDuration = document.getElementById(`playDuration-${index}`);

            if (isRecording[index]) {
                // Stop recording
                recorders[index].stop();
                recordButton.innerHTML = '<i class="fas fa-microphone"></i>';
                recordButton.classList.remove('btn-danger');
                recordButton.classList.add('btn-outline-danger');
                playButton.disabled = false;
                recordingDot.style.display = 'none';
                isRecording[index] = false;
            } else {
                // Start recording
                navigator.mediaDevices.getUserMedia({
                        audio: true
                    })
                    .then(function(stream) {
                        recordedChunks[index] = [];
                        recorders[index] = new MediaRecorder(stream);

                        recorders[index].ondataavailable = function(event) {
                            recordedChunks[index].push(event.data);
                        };

                        recorders[index].onstop = function() {
                            const audioBlob = new Blob(recordedChunks[index], {
                                type: 'audio/webm'
                            });
                            const audioUrl = URL.createObjectURL(audioBlob);
                            document.getElementById(`audioPlayer-${index}`).src = audioUrl;
                        };

                        recorders[index].start();
                        recordButton.innerHTML = '<i class="fas fa-stop"></i>';
                        recordButton.classList.remove('btn-outline-danger');
                        recordButton.classList.add('btn-danger');
                        playButton.disabled = true;
                        recordingDot.style.display = 'block';
                        isRecording[index] = true;

                        // Stop any current playback
                        stopPlayback(index);
                    })
                    .catch(function(err) {
                        console.error("Error accessing microphone:", err);
                    });
            }
        }

        function togglePlayback(index) {
            const playButton = document.getElementById(`playButton-${index}`);
            const audioPlayer = document.getElementById(`audioPlayer-${index}`);
            const playDuration = document.getElementById(`playDuration-${index}`);

            if (isPlaying[index]) {
                stopPlayback(index);
            } else {
                if (!recordedChunks[index] || recordedChunks[index].length === 0) {
                    console.error("No recording available.");
                    return;
                }

                // Start playback
                const blob = new Blob(recordedChunks[index], {
                    type: "audio/webm"
                });
                const audioURL = URL.createObjectURL(blob);
                audioPlayer.src = audioURL;
                audioPlayer.play();

                playButton.innerHTML = '<i class="fas fa-stop"></i>';
                playButton.classList.remove('btn-outline-primary');
                playButton.classList.add('btn-primary');
                isPlaying[index] = true;

                // Update timer
                playbackIntervals[index] = setInterval(function() {
                    const duration = audioPlayer.currentTime;
                    const minutes = Math.floor(duration / 60);
                    const seconds = Math.floor(duration % 60);
                    playDuration.textContent =
                        minutes.toString().padStart(2, '0') + ":" +
                        seconds.toString().padStart(2, '0');
                }, 100);

                audioPlayer.onended = function() {
                    stopPlayback(index);
                };
            }
        }

        function stopPlayback(index) {
            const playButton = document.getElementById(`playButton-${index}`);
            const audioPlayer = document.getElementById(`audioPlayer-${index}`);
            const playDuration = document.getElementById(`playDuration-${index}`);

            audioPlayer.pause();
            audioPlayer.currentTime = 0;
            playButton.innerHTML = '<i class="fas fa-play"></i>';
            playButton.classList.remove('btn-primary');
            playButton.classList.add('btn-outline-primary');
            playDuration.textContent = "00:00";
            clearInterval(playbackIntervals[index]);
            isPlaying[index] = false;
        }

        // Handle file upload for each card
        $(document).on('change', '[id^="audioFile-"]', function(e) {
            const index = this.id.split('-')[1];
            const file = e.target.files[0];
            if (!file) return;

            const audioPlayer = document.getElementById(`audioPlayer-${index}`);
            const playButton = document.getElementById(`playButton-${index}`);

            recordedChunks[index] = [file];
            audioPlayer.src = URL.createObjectURL(file);
            playButton.disabled = false;
        });

        // Initialize the toggle project section
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
    </script>
@stop
