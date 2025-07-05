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
            <h2 class="page-title">Add New {{ $sectionNameSingular }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>Dashboard</a>
                    </li>
                    <li class="breadcrumb-item"> <a href='{{ route('Test.index') }}'>Tests</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Course.index', $training_id) }}">{{ $sectionName }}</a>
                    </li>
                    <li class="breadcrumb-item active">Add New {{ $sectionNameSingular }}</li>
                </ol>
            </nav>
        </div>
        {{-- <div class="row" id="step-indicator" style="display: none; margin-bottom: 20px;">
            <div class="col-12">
                <ul class="step-wizard list-unstyled d-flex justify-content-between">
                    <li class="step" id="step-1-tab">Step 1: Course Details</li>
                    <li class="step" id="step-2-tab">Step 2: Test Configuration</li>
                    <li class="step" id="step-3-tab">Step 3: Question Configuration</li>
                </ul>
            </div>
        </div> --}}

        @php
            $currentStep = request()->input('current_step', 1);
            $testId = request()->input('test_id');
        @endphp

        <div class="row" id="step-indicator" style="margin-bottom: 20px;">
            <div class="col-12">
                <ul class="step-wizard list-unstyled d-flex justify-content-between">
                    <li class="step {{ $currentStep == 1 ? 'active' : '' }}" id="step-1-tab">Step 1: Course Details</li>
                    <li class="step {{ $currentStep == 2 ? 'active' : '' }}" id="step-2-tab">Step 2: Test Configuration</li>
                    <li class="step {{ $currentStep == 3 ? 'active' : '' }}" id="step-3-tab">Step 3: Question Configuration
                    </li>
                </ul>
            </div>
        </div>
        <div class="step-container" id="step-1" style="display: {{ $currentStep == 1 ? 'block' : 'none' }};">
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
                                    {{-- Add Test Select Box --}}
                                    <div class="form-group">
                                        <div class="mws-form-row">
                                            {!! Html::decode(
                                                Form::label('add_test_option', 'Add Test <span class=""> *</span>', ['class' => 'mws-form-label']),
                                            ) !!}
                                            <div class="mws-form-item">
                                                {{ Form::select('add_test_option', ['' => 'Select', 'existing' => 'Select from existing tests', 'new' => 'Create new test', 'no_test' => 'Do not want test in this course'], null, ['class' => 'form-control', 'id' => 'add_test_option']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Test ID Select Box (initially hidden) --}}
                                <div class="col-md-6" id="test_id_container" style="display: none;">
                                    <div class="form-group {{ $errors->first('test_id') ? 'has-error' : '' }}">
                                        <div class="mws-form-row">
                                            {!! Html::decode(
                                                Form::label('test_id', trans('Select Test') . '<span class=""> *</span>', ['class' => 'mws-form-label']),
                                            ) !!}
                                            <div class="mws-form-item">
                                                {{ Form::select('test_id', ['' => "Don't want test in this course"] + $test, null, ['class' => 'form-control']) }}
                                                <div class="error-message help-inline">
                                                    {{ $errors->first('test_id') }}
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
                                                    value="0"
                                                    {{ isset($model) && $model->skip == 0 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="skip_no">yes</label>

                                                <input class="form-check-input" type="radio" name="skip" id="skip_yes"
                                                    value="1"
                                                    {{ isset($model) && $model->skip == 1 ? 'checked' : '' }}>
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
                                                                    Form::label('length', trans('Reading Time (In Minutes)') . '<span class="requireRed">*</span>', [
                                                                        'class' => 'mws-form-label',
                                                                    ]),
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
                                                                            style="width: 100%; height: 500px;"
                                                                            frameborder="0">
                                                                        </iframe>
                                                                    @endif
                                                                @elseif (($document->type == 'doc' && $document->document_type == 'doc') || $document->document_type == 'docx')
                                                                    @if (config('TRAINING_DOCUMENT_URL') . $document->document != '')
                                                                        <br />
                                                                        <iframe
                                                                            src="https://view.officeapps.live.com/op/embed.aspx?src={{ asset('training_document/' . $document->document) }}"
                                                                            style="width: 100%; height: 500px;"
                                                                            frameborder="0">
                                                                        </iframe>
                                                                    @endif
                                                                @elseif (($document->type == 'doc' && $document->document_type == 'ppt') || $document->document_type == 'pptx')
                                                                    @if (config('TRAINING_DOCUMENT_URL') . $document->document != '')
                                                                        <br />
                                                                        <iframe
                                                                            src="https://view.officeapps.live.com/op/embed.aspx?src={{ asset('training_document/' . $document->document) }}"
                                                                            style="width: 100%; height: 500px;"
                                                                            frameborder="0">
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
                                                                <input type="hidden"
                                                                    name="data[<?php echo $i; ?>][entryID]"
                                                                    id="entryID_<?php echo $i; ?>"
                                                                    value="{{ $document->id }}">
                                                                @if ($i == 1)
                                                                    <a href="javascript:void(0);" id="addMore"
                                                                        class="btn btn-primary add_new_btn"
                                                                        value="Add More">Add
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
                                                                Form::label('length', trans('Reading Time (In Minutes)') . '<span class="requireRed">*</span>', [
                                                                    'class' => 'mws-form-label',
                                                                ]),
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
                                                '<span class="requireRed">                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * </span>',
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
                                <button id="next-button" class="btn btn-danger next-button">Save & Next</button>
                                <button id="submit-button" class="btn btn-success submit-button" type="submit"
                                    style="display: none;">Submit</button>


                                <a href="{{ route('Course.add', $training_id) }}"
                                    class="btn btn-primary reset_form">{{ trans('Clear') }}</a>
                                <a href="{{ route('Course.index', $training_id) }}"
                                    class="btn btn-info">{{ trans('Cancel') }}</a>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="step-container" id="step-2" style="display: {{ $currentStep == 2 ? 'block' : 'none' }};">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        @if ($flag == 1)
                            {{ Form::model($model, ['url' => route('Course.add', $training_id), 'id' => 'edit-plan-form', 'class' => 'row g-3', 'files' => true]) }}
                            {{ Form::hidden('id', null) }}
                        @else
                            {{ Form::open(['url' => route('Course.add', $training_id), 'id' => 'add-plan-form', 'class' => 'row g-3', 'files' => true]) }}
                        @endif
                        <div class="mws-panel-body no-padding tab-content row">

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

                            <div class="mws-button-row">
                                {{-- <button type="button" class="btn btn-danger next-button">Save & Next</button>
                                <button type="submit" class="btn btn-success submit-button"
                                    style="display: none;">Submit</button> --}}
                                <button id="next-button" class="btn btn-danger next-button">Save & Next</button>
                                <button id="submit-button" class="btn btn-success submit-button" type="submit"
                                    style="display: none;">Submit</button>


                                <a href="{{ route('Course.add', $training_id) }}"
                                    class="btn btn-primary reset_form">{{ trans('Clear') }}</a>
                                <a href="{{ route('Course.index', $training_id) }}"
                                    class="btn btn-info">{{ trans('Cancel') }}</a>
                            </div>

                        </div>

                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="step-container" id="step-3" style="display: {{ $currentStep == 3 ? 'block' : 'none' }};">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        @if ($flag == 1)
                            {{ Form::model($model, ['url' => route('Course.add', $training_id), 'id' => 'edit-plan-form', 'class' => 'row g-3', 'files' => true, 'autocomplete' => 'off']) }}
                            {{ Form::hidden('id', $model->id ?? null) }}
                        @else
                            {{ Form::open(['url' => route('Course.add', $training_id), 'id' => 'add-plan-form', 'class' => 'row g-3', 'files' => true, 'autocomplete' => 'off']) }}
                        @endif

                        <div class="mws-panel-body no-padding tab-content row">
                            @php
                                $test_id = Session::get('current_test_id');
                            @endphp
                            <div class="form-group">
                                @livewire('test-question-form', ['testId' => $test_id, 'questionId' => $model->id ?? null], key('question_form'), ['wire:key' => 'question_form', 'wire:ref' => 'questionFormRef'])
                            </div>
                            <div class="col-md-12
                                mt-3">
                                <div class="form-group <?php echo $errors->first('body') ? 'has-error' : ''; ?>">
                                    {!! Html::decode(
                                        Form::label('description', 'Hint or Any Other Description' . '<span class="requireRed"> * </span>', [
                                            'class' => 'mws-form-label',
                                        ]),
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
                            <button id="save-question-button" class="btn btn-primary">Save Question</button>
                            <button id="final-submit-button" class="btn btn-success">
                                Finish & Return to Courses
                            </button>
                            <a href="{{ route('Course.add', $training_id) }}" class="btn btn-primary reset_form">
                                <i class="icon-refresh"></i> {{ trans('Clear') }}
                            </a>
                        </div>

                        {{ Form::close() }}
                    </div>
                </div>

            </div>

            <table class="table table-hover table table-bordered mt-2 ">
                <thead class="theadLight">
                    <tr>
                        <th>S.No.</th>
                        <th>Question</th>
                        <th>Marks</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $test_id = Session::get('current_test_id');

                        if ($test_id) {
                            $results = App\Models\Question::where('test_id', $test_id)->get();
                        } else {
                            $results = collect();
                        }
                    @endphp
                    @foreach ($results as $result)
                        <tr class="items-inner">
                            <td>{{ $loop->iteration . '.' }}</td>
                            <td>{{ $result->question }}</td>
                            <td>{{ $result->marks }}</td>
                            <td>
                                {{-- <a href='{{ route("$modelName.edit", [$test_id, $result->id]) }}' class="btn btn-primary"
                                    title="Edit"> <span class="fas fa-edit"></span></a> --}}
                                <a href='{{ route("$modelName.delete", "$result->id") }}' data-delete="delete"
                                    class="delete_any_item btn btn-danger" title="Delete">

                                    <span class="fas fa-trash-alt"></span></a>
                                {{-- <a href='{{ route("$modelName.view", [$test_id, $result->id]) }}' class="btn btn-success"
                                    title="View"> <span class="fas fa-eye"></span></a> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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


                    document.addEventListener('DOMContentLoaded', function() {
                        const testSelector = document.getElementById('add_test_option');
                        const testIdContainer = document.getElementById('test_id_container');

                        testSelector.addEventListener('change', function() {
                            if (this.value === 'existing') {
                                testIdContainer.style.display = 'block';
                            } else if (this.value === 'no_test') {
                                testIdContainer.style.display = 'none';
                                stepIndicator.style.display = 'none';

                            } else {
                                testIdContainer.style.display = 'none';
                            }
                        });
                    });
                </script>

                <script>
                    let currentStep = {{ $currentStep ?? 1 }};
                    document.addEventListener('DOMContentLoaded', function() {
                        let testId = null;
                        let description;

                        function updateStepUI(step) {
                            document.querySelectorAll('.step-container').forEach((el, index) => {
                                el.style.display = (index + 1 === step) ? 'block' : 'none';
                            });

                            document.querySelectorAll('.step').forEach((el, index) => {
                                el.classList.toggle('active', index + 1 === step);
                            });

                            // Update buttons
                            if (step === 3) {
                                document.querySelectorAll('.next-button').forEach(btn => {
                                    btn.style.display = 'none';
                                });
                                document.querySelectorAll('.submit-button').forEach(btn => {
                                    btn.style.display = 'inline-block';
                                });
                            } else {
                                document.querySelectorAll('.next-button').forEach(btn => {
                                    btn.style.display = 'inline-block';
                                });
                                document.querySelectorAll('.submit-button').forEach(btn => {
                                    btn.style.display = 'none';
                                });
                            }
                        }

                        const testOption = document.getElementById('add_test_option');
                        const testIdContainer = document.getElementById('test_id_container');
                        const stepIndicator = document.getElementById('step-indicator');

                        testOption.addEventListener('change', function() {
                            if (this.value === 'existing') {
                                testIdContainer.style.display = 'block';
                                stepIndicator.style.display = 'none';

                                // Show only submit button for existing test
                                document.querySelectorAll('.submit-button').forEach(btn => {
                                    btn.style.display = 'inline-block';
                                });
                                document.querySelectorAll('.next-button').forEach(btn => {
                                    btn.style.display = 'none';
                                });
                            } else if (this.value === 'no_test') {
                                testIdContainer.style.display = 'none';
                                stepIndicator.style.display = 'none';

                                // Show only submit button for existing test
                                document.querySelectorAll('.submit-button').forEach(btn => {
                                    btn.style.display = 'inline-block';
                                });
                                document.querySelectorAll('.next-button').forEach(btn => {
                                    btn.style.display = 'none';
                                });
                            } else if (this.value === 'new') {
                                testIdContainer.style.display = 'none';
                                stepIndicator.style.display = 'block';
                                // Show next button for new test flow
                                document.querySelectorAll('.submit-button').forEach(btn => {
                                    btn.style.display = 'none';
                                });
                                document.querySelectorAll('.next-button').forEach(btn => {
                                    btn.style.display = 'inline-block';
                                });
                                currentStep = 1;
                                refreshQuestionsTable();
                                updateStepUI(currentStep);
                            } else {
                                testIdContainer.style.display = 'none';
                                stepIndicator.style.display = 'none';
                                // Hide both buttons if nothing selected
                                document.querySelectorAll('.submit-button').forEach(btn => {
                                    btn.style.display = 'none';
                                });
                                document.querySelectorAll('.next-button').forEach(btn => {
                                    btn.style.display = 'none';
                                });
                            }
                        });


                        document.addEventListener('DOMContentLoaded', function() {
                            if (CKEDITOR.instances['description']) {
                                description = CKEDITOR.instances['description'];
                            } else {
                                CKEDITOR.replace('description');
                                CKEDITOR.on('instanceReady', function(ev) {
                                    description = ev.editor;
                                });
                            }
                        });

                        document.getElementById('save-question-button')?.addEventListener('click', function(e) {
                            e.preventDefault();

                            const currentForm = this.closest('form');
                            const formData = new FormData(currentForm);

                            // For CKEditor fields
                            const editor = CKEDITOR.instances['body'];
                            if (editor) {
                                formData.set('description', editor.getData());
                            }

                            formData.append('current_step', 3); // Explicitly set step 3

                            fetch(currentForm.action, {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Refresh the questions table
                                        refreshQuestionsTable();
                                        // Clear the form for next question
                                        currentForm.reset();
                                        if (editor) editor.setData('');

                                        document.getElementById('final-submit-button').style.display =
                                            'inline-block';

                                        const testIdInput = currentForm.querySelector('input[name="test_id"]');
                                        const testId = testIdInput ? testIdInput.value : '';

                                        // Reload the page with step 3 active and passing the test_id
                                        window.location.href = window.location.pathname +
                                            '?current_step=3&test_id=' + testId;

                                    } else {
                                        // Handle errors
                                        if (data.errors) {
                                            // Clear previous errors
                                            document.querySelectorAll('.text-danger').forEach(el => el.remove());

                                            // Show new errors
                                            for (const [field, errors] of Object.entries(data.errors)) {
                                                const input = currentForm.querySelector(`[name="${field}"]`);
                                                if (input) {
                                                    const errorDiv = document.createElement('div');
                                                    errorDiv.className = 'text-danger';
                                                    errorDiv.textContent = errors[0];
                                                    input.parentNode.appendChild(errorDiv);
                                                }
                                            }
                                        }
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('An error occurred while saving the question');
                                });
                        });

                        // Handle Final Submit button
                        document.getElementById('final-submit-button')?.addEventListener('click', function(e) {
                            e.preventDefault();
                            window.location.href = "{{ route('Course.index', $training_id) }}";
                        });

                        // Function to refresh questions table
                        function refreshQuestionsTable() {
                            fetch(`{{ route('Course.add', $training_id) }}?test_id=${testId}&refresh=1`, {
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                    }
                                })
                                .then(response => response.text())
                                .then(html => {
                                    // Extract just the table part from the response
                                    const parser = new DOMParser();
                                    const doc = parser.parseFromString(html, 'text/html');
                                    const newTable = doc.querySelector('.table.table-hover');

                                    if (newTable) {
                                        const currentTable = document.querySelector('.table.table-hover');
                                        currentTable.parentNode.replaceChild(newTable, currentTable);
                                    }
                                });
                        }

                        // Handle next button clicks with AJAX save
                        document.querySelectorAll('.next-button').forEach(button => {
                            button.addEventListener('click', function(e) {
                                e.preventDefault();

                                const currentForm = this.closest('form');
                                const formData = new FormData(currentForm);

                                formData.append('current_step', currentStep);

                                // For CKEditor fields

                                const editor = CKEDITOR.instances['description'];
                                if (editor) {
                                    formData.set('description', editor.getData());
                                }

                                fetch(currentForm.action, {
                                        method: 'POST',
                                        body: formData,
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'X-CSRF-TOKEN': document.querySelector(
                                                'meta[name="csrf-token"]').content,
                                            'Accept': 'application/json'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            // Store test ID if we're moving from step 2 to 3
                                            if (currentStep === 2 && data.test_id) {
                                                testId = data.test_id;
                                            }

                                            if (currentStep < 3) {
                                                currentStep++;
                                                updateStepUI(currentStep);

                                                // Update question form action with new test ID if needed
                                                if (currentStep === 3 && testId) {
                                                    const questionForms = document.querySelectorAll(
                                                        '#step-3 form');
                                                    questionForms.forEach(form => {
                                                        form.action = form.action.replace(
                                                            /test_id=\d+/, `test_id=${testId}`);
                                                    });
                                                }
                                            }
                                        } else {
                                            // Display validation errors
                                            if (data.errors) {
                                                for (const [field, errors] of Object.entries(data.errors)) {
                                                    const input = currentForm.querySelector(
                                                        `[name="${field}"]`);
                                                    if (input) {
                                                        const errorDiv = document.createElement('div');
                                                        errorDiv.className = 'text-danger';
                                                        errorDiv.textContent = errors[0];
                                                        input.parentNode.appendChild(errorDiv);
                                                    }
                                                }
                                            }
                                            alert('Please fix the errors before proceeding');
                                        }
                                    })
                                    .catch(error => {
                                        // console.error('Error:', error);
                                        console.log(error);

                                        alert('An error occurred while saving');
                                    });
                            });
                        });

                        updateStepUI(currentStep);
                    });
                </script>



            @stop
