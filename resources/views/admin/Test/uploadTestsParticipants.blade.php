@extends('admin.layouts.default')
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h2 class="page-title">Upload Test participants</h2>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mt-2">
                            Upload test participants directly using emails </h4>
                        <hr>
                        {{ Form::open(['role' => 'form', 'route' => ['import.tests.usersDirectly', $test_id], 'class' => 'forms-sample', 'files' => true, 'autocomplete' => 'off']) }}
                        <div class="form-group {{ $errors->has('trainees') ? 'has-error' : '' }}">
                            {!! Form::label('trainees', trans('Enter Users Emails (comma-separated)'), ['class' => 'mws-form-label']) !!}
                            <div class="mws-form-item">
                                {{-- {{ Form::text('trainees', $existingEmails, ['class' => 'form-control', 'id' => 'trainees', 'placeholder' => 'Enter emails separated by commas']) }} --}}
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
                                    pattern: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/, // email validation
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
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="box search-panel collapsed-box">
                            <h4 class="mt-2">
                                Upload test participants from excel
                            </h4>
                            <hr>
                            <div class="box-body mb-4 mt-2">
                                <form action="{{ route('import.tests', $test_id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="col-md-2 col-sm-2">
                                        <div class="form-group ">
                                            <input type="file" name="file" required>
                                        </div>
                                    </div>
                                    <div class="d-md-flex justify-content-between align-items-center gap-5"
                                        style="display: block !important">
                                        <button class="btn btn-primary" type="submit">Upload Users</button>
                                        <a href="{{ asset('sample-files/import-test-participants-sample.xlsx') }}"
                                            class="btn btn-primary" style="margin-left:100px"> Download sample file</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
    <div>
        <div>
        @stop
