@extends('admin.layouts.default')
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h1 >Assign Test</h1>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-12 ">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="row">
                                <!-- Project Selection -->
                                <div class="mb-3 col-6">
                                    {!! Form::label('project', 'Select Product', ['class' => 'block font-bold mb-1']) !!}
                                    {!! Form::select('project', $projects, null, [
                                        'id' => 'projectSelect',
                                        'class' => 'form-control',
                                        'placeholder' => '-- Choose Project --',
                                    ]) !!}
                                </div>

                                <!-- Project-dependent fields -->
                                <div id="retailiq-section" class="mb-3 col-6" style="display: none;">
                                    {!! Form::label('client_id', 'Select Client', ['class' => 'block font-bold mb-1']) !!}
                                    {!! Form::select('client_id', [], null, [
                                        'id' => 'client_idSelect',
                                        'class' => 'form-control',
                                        'placeholder' => '-- Choose Client --',
                                    ]) !!}
                                </div>

                                <div id="method-section" class="mb-3 col-6" style="display: none;">
                                    {!! Form::label('method', 'Select Method', ['class' => 'block font-bold mb-1']) !!}
                                    {!! Form::select('method', $methods, null, [
                                        'id' => 'methodSelect',
                                        'class' => 'form-control',
                                        'placeholder' => '-- Choose Method --',
                                    ]) !!}
                                </div>

                                <!-- Excel Upload -->
                                <div id="excel-upload-section" class="box search-panel collapsed-box"
                                    style="display: none;">
                                    <div class="box-body mb-4">
                                        <form action="{{ route('import.tests', $test_id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="d-flex flex-wrap align-items-center">

                                                <a href="{{ asset('sample-files/import-test-participants-sample.xlsx') }}"
                                                    class="btn btn-primary"> Download sample file</a>

                                                <!-- File Input and Upload Button aligned to the end -->
                                                <div class="d-flex flex-wrap align-items-center gap-2 ms-auto">
                                                    <div class="col-md-8">
                                                        <input type="file" name="file" class="form-control" required>
                                                    </div>
                                                    <div>
                                                        <button class="btn btn-success" type="submit">Upload Users</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div id="user-selection-section" class="" style="display: none;">
                                    <form action="{{ route('assgin.test.participants', $test_id) }}" method="POST">
                                        @csrf
                                        {!! Form::hidden('test_id', $test_id) !!}
                                        {!! Form::label('empIds', 'Select Users', ['class' => 'block font-bold mb-1']) !!}
                                        {!! Form::select('empIds[]', $users, $existingUserIds, [
                                            'class' => 'form-control select2-form',
                                            'multiple' => 'multiple',
                                            'id' => 'select2-users',
                                        ]) !!}
                                        <button class="btn btn-primary mt-3" type="submit">Upload Users</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mt-2">
                            Upload test participants directly using emails </h4>
                        <hr>
                        {{ Form::open(['role' => 'form', 'route' => ['Test.import.tests.usersDirectly', $test_id], 'class' => 'forms-sample', 'files' => true, 'autocomplete' => 'off']) }}
                        <div class="form-group {{ $errors->has('trainees') ? 'has-error' : '' }}">
                            {!! Form::label('trainees', trans('Enter Users Emails (comma-separated)'), ['class' => 'mws-form-label']) !!}
                            <div class="mws-form-item">
                                {{ Form::textarea('trainees', null, ['class' => 'form-control', 'id' => 'trainees', 'placeholder' => 'Enter emails separated by commas', 'rows' => 3]) }}
                                <div class="error-message help-inline">
                                    {{ $errors->first('trainees') }}
                                </div>
                            </div>
                        </div>
                        <input type="submit" value="{{ trans('Save') }}" class="btn btn-danger">
                        <a href="javascript:void(0);" class="btn btn-primary reset_form" onclick="location.reload();">
                            <i class="icon-refresh"></i> {{ trans('Clear') }}
                        </a>
                        <a href="{{ route($modelName . '.index') }}" class="btn btn-info">
                            <i class="icon-refresh"></i> {{ trans('Cancel') }}
                        </a>
                        {{ Form::close() }}

                        <!-- Tagify -->
                        <link rel="stylesheet" href="{{ asset('front/cdn/tagify.css') }}">
                        <script src="{{ asset('front/cdn/tagify.js') }}"></script>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var input = document.getElementById('trainees');
                                var tagify = new Tagify(input, {
                                    delimiters: ",",
                                    pattern: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/,
                                });

                                input.form.addEventListener('submit', function() {
                                    var emails = tagify.value.map(function(tag) {
                                        return tag.value;
                                    }).join(',');
                                    input.value = emails;
                                });
                            });
                        </script>

                    </div>
                </div>
            </div>
        </div>


    </div>

    <script>
        $('#select2-users').select2({
            width: '100%',
        });

        $('#projectSelect').on('change', function() {
            const selectedProject = $(this).val();

            if (selectedProject === 'RetailIQ') {
                $('#retailiq-section').show();
                $('#method-section').hide();
                $('#excel-upload-section').hide();
                $('#user-selection-section').hide();
            } else if (selectedProject) {
                $('#retailiq-section').hide();
                $('#method-section').show();
                $('#excel-upload-section').hide();
                $('#user-selection-section').hide();
            } else {
                $('#retailiq-section, #method-section, #excel-upload-section, #user-selection-section')
                    .hide();
            }
        });

        // Show/hide method-related section
        $('#methodSelect').on('change', function() {
            const selectedMethod = $(this).val();

            if (selectedMethod === 'fromExcel') {
                $('#excel-upload-section').show();
                $('#user-selection-section').hide();
            } else if (selectedMethod === 'fromUser') {
                $('#excel-upload-section').hide();
                $('#user-selection-section').show();
            } else {
                $('#excel-upload-section, #user-selection-section').hide();
            }
        });
    </script>
    <div>
        <div>



        @stop
