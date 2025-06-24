@extends('admin.layouts.default')
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h1>Assign Test</h1>
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
                                        <form action="{{ route('import.training-participants', $test_id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="d-flex flex-wrap align-items-center">
                                                <!-- Download Sample File Button -->
                                                <a href="{{ asset('sample-files/import-training-participants-sample.xlsx') }}"
                                                    class="btn btn-primary">
                                                    Download Sample File
                                                </a>
                                                <!-- File Input and Upload Button aligned to the end -->
                                                <div class="d-flex flex-wrap align-items-center gap-2 ms-auto">
                                                    <div class="col-md-8">
                                                        <input type="file" name="file" class="form-control" required>
                                                    </div>
                                                    <div>
                                                        <button class="btn btn-success" type="submit">Upload
                                                            Users</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- Placeholder for 'fromUser' future UI -->
                                <div id="user-selection-section" class="" style="display: none;">
                                    <form action="{{ route('assgin.test.participants', $test_id) }}" method="POST">
                                        @csrf
                                        {!! Form::hidden('test_id', $test_id) !!}
                                        {!! Form::label('empIds', 'Select Users', ['class' => 'block font-bold mb-1']) !!}
                                        {!! Form::select('empIds[]', $users, $existingUserIds, [
                                            'class' => 'form-control select2-users',
                                            'multiple' => 'multiple',
                                        ]) !!}
                                        <button class="btn btn-primary mt-3" type="submit">Upload Users</button>
                                    </form>
                                </div>

                                <!-- retailiq Project fields -->

                                <div class="mb-3 col-6" id="retailiq-section" style="display: none;">
                                    <form action="{{ route('retail.assign-test', $test_id) }}" method="POST"
                                        class="mt-0">
                                        @csrf

                                        {!! Form::hidden('test_id', $test_id) !!}

                                        {!! Form::label('client_id', 'Select Client', ['class' => 'block font-bold mb-1 required']) !!}
                                        {!! Form::select('client_id', $clients, null, [
                                            'id' => 'clientSelect',
                                            'class' => 'form-control',
                                            'placeholder' => '-- Choose Client --',
                                        ]) !!}
                                </div>
                                <div class="mb-3 col-6" id="store-section" style="display: none;">
                                    {!! Form::label('assginTo', 'Assgin To', ['class' => 'block font-bold mb-1 required']) !!}
                                    {!! Form::select('assginTo', $assginTo, null, [
                                        'class' => 'form-control',
                                        'placeholder' => '-- Choose --',
                                    ]) !!}
                                </div>
                                <div class="mb-3 col-6" id="store-section" style="display: none;">
                                    {!! Form::label('validity', 'Training Validity', ['class' => 'block font-bold mb-1 required']) !!}
                                    {!! Form::date('validity', null, [
                                        'class' => 'form-control',
                                        'id' => 'validityy',
                                        'min' => \Carbon\Carbon::tomorrow()->format('Y-m-d'), // Disables past and today's dates
                                    ]) !!}
                                </div>
                                <div id="campaign-section" class="mb-3 col-6" style="display: none;">
                                    {!! Form::label('campaign_id', 'Select Campaign', ['class' => 'block font-bold mb-1']) !!}
                                    {!! Form::select('campaign_id[]', [], $campaignData ?? null, [
                                        'id' => 'campaignSelect',
                                        'class' => 'form-control campaign-users',
                                        'multiple' => true,
                                        'data-placeholder' => '-- Choose Campaign --',
                                    ]) !!}

                                </div>


                                <div class="mb-3 col-6" id="store-section" style="display: none;">
                                    {!! Form::label('store_code', 'Select Store', ['class' => 'block font-bold mb-1']) !!}

                                    <div class="d-flex justify-content-between mb-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                            id="selectAllStores">Select All Stores</button>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            id="clearAllStores">Clear All</button>
                                    </div>

                                    {!! Form::select('store_code[]', [], null, [
                                        'id' => 'storeSelect',
                                        'class' => 'form-control select2-form',
                                        'multiple' => 'multiple',
                                    ]) !!}

                                    <div class="col-12 text-end">
                                        <button class="btn btn-primary mt-3" type="submit">Attach Training</button>
                                    </div>
                                </div>
                                </form>
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
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            display: inline-flex;
            align-items: center;
            padding: 2px 5px;
            margin: 3px 5px 3px 0;
            border: 1px solid #aaa;
            border-radius: 4px;
            background-color: #f4f4f4;
            font-size: 14px;
            line-height: 1.2;
            position: relative;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            margin-right: 5px;
            position: relative;
            top: auto;
            left: auto;
            line-height: 1;
            font-size: 12px;
        }
    </style>
    <script>
        $('.select2-users').select2({
            width: '100%',
        });

        $('#projectSelect').on('change', function() {
            const selectedProject = $(this).val();
            $('#clientSelect').val(null).trigger('change');
            $('#campaignSelect').val(null).trigger('change');
            $('#storeSelect').val(null).trigger('change');

            if (selectedProject === 'RetailIQ') {
                $('#retailiq-section').show();
                $('#method-section, #excel-upload-section, #user-selection-section, #campaign-section, #store-section')
                    .hide();
            } else if (selectedProject) {
                $('#retailiq-section').hide();
                $('#method-section').show();
                $('#excel-upload-section, #user-selection-section, #campaign-section, #store-section').hide();
            } else {
                $('#retailiq-section, #method-section, #excel-upload-section, #user-selection-section, #campaign-section, #store-section')
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


        $('#storeSelect').select2({
            width: '100%',
        });
        $('.campaign-users').select2({
            width: '100%',
        });
        $(document).ready(function() {
            const $storeSelect = $('#storeSelect');

            // Select All button
            $('#selectAllStores').on('click', function() {
                let allValues = [];
                $storeSelect.find('option').each(function() {
                    if ($(this).val()) {
                        allValues.push($(this).val());
                    }
                });
                $storeSelect.val(allValues).trigger('change');
            });

            // Clear All button
            $('#clearAllStores').on('click', function() {
                $storeSelect.val(null).trigger('change');
            });

            $('#clientSelect').on('change', function() {
                const clientId = $(this).val();
                const $campaignSelect = $('#campaignSelect'); // Use consistent ID

                if (clientId) {
                    $('#campaign-section, #store-section').show();
                    $campaignSelect.empty().append('<option>Loading...</option>');

                    $.ajax({
                        url: '{{ route('fetch.retail.campaigns') }}',
                        method: 'POST',
                        data: {
                            client_id: clientId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status === 1) {
                                $campaignSelect.empty().append(
                                    '<option value="">-- Choose Campaign --</option>');
                                response.campaigns.forEach(function(camp) {
                                    $campaignSelect.append(new Option(camp.name, camp
                                        .id));
                                });

                            } else {
                                $campaignSelect.empty().append(
                                    '<option>No campaigns found</option>');
                            }
                        },
                        error: function() {
                            $campaignSelect.empty().append(
                                '<option>Error loading campaigns</option>');
                        }
                    });
                } else {
                    $('#campaign-section, #store-section').hide();
                    $campaignSelect.empty();
                }
            });


        });
        $('#campaignSelect').on('change', function() {
            const selectedCampaignIds = $(this).val();
            const $storeSelect = $('#storeSelect');

            if (selectedCampaignIds && selectedCampaignIds.length > 0) {
                $('#store-section').show();

                $.ajax({
                    url: '{{ route('fetch.retail.campaigns.store') }}',
                    method: 'POST',
                    data: {
                        campaign_ids: selectedCampaignIds,
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        // Don't clear existing if already data exists in storeSelect
                        if ($storeSelect.find('option').length === 0) {
                            $storeSelect.append('<option>Loading...</option>');
                        }
                    },
                    success: function(response) {
                        if (response.status === 1 && response.stores.length > 0) {
                            $storeSelect.empty(); // Only clear when valid data is incoming

                            response.stores.forEach(function(store) {
                                $storeSelect.append(new Option(store.code, store.code));
                            });

                            $storeSelect.select2({
                                width: '100%'
                            }); // Reinit only if data updated
                        } else {
                            // Keep previous store list, just optionally show a toast or alert
                            console.warn('No new stores found for selected campaigns.');
                        }
                    },
                    error: function() {
                        console.error('Failed to load stores.');
                    }
                });
            } else {
                $('#store-section').hide();
                $storeSelect.empty();
            }
        });
        $(document).ready(function() {
            // Set minimum date to tomorrow (disables past dates and today)
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const minDate = tomorrow.toISOString().split('T')[0];
            $('#validityy').attr('min', minDate);

            // Clear any existing value
            $('#validityy').val('');

            // Validate on change
            $('#validityy').on('change', function() {
                const selectedDate = new Date($(this).val());
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                if (!$(this).val()) {
                    $('#validity-error').hide();
                    return;
                }

                // Check if date is in past or today
                if (selectedDate <= today) {
                    // Use SweetAlert if available, otherwise fall back to regular alert
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Date',
                            text: 'Past dates and today\'s date are not allowed. Please choose a future date.',
                        });
                    } else {
                        alert(
                            'Error: Past dates and today\'s date are not allowed. Please choose a future date.'
                        );
                    }

                    $('#validity-error').show();
                    $(this).val('');
                } else {
                    $('#validity-error').hide();
                }
            });
        });
    </script>
@stop
