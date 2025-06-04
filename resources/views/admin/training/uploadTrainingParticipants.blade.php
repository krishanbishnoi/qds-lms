@extends('admin.layouts.default')
@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <div class="content-wrapper">
        <div class="page-header">
            <h1>
                Assign Training
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i>
                            Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::to('admin/trainings') }}"><i class="fa fa-dashboard"></i>
                            Trainings</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Assign</li>

                </ol>
            </nav>
        </div>
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
                                        <form action="{{ route('import.training-participants', $training_id) }}"
                                            method="POST" enctype="multipart/form-data">
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
                                    <form action="{{ route('Training.assgin-training-participants', $training_id) }}"
                                        method="POST">
                                        @csrf
                                        {!! Form::hidden('training_id', $training_id) !!}
                                        {!! Form::label('empIds', 'Select Users', ['class' => 'block font-bold mb-1']) !!}
                                        {!! Form::select('empIds[]', $users, $existingUserIds, [
                                            'class' => 'form-control select2-form',
                                            'multiple' => 'multiple',
                                            'id' => 'select2-users',
                                        ]) !!}
                                        <button class="btn btn-primary mt-3" type="submit">Upload Users</button>
                                    </form>
                                </div>

                                <!-- retailiq Project fields -->

                                <div class="mb-3 col-6" id="retailiq-section" style="display: none;">


                                    <form action="{{ route('retail.assign-training', $training_id) }}" method="POST"
                                        class="mt-0">
                                        @csrf
                                        {!! Form::hidden('training_id', $training_id) !!}

                                        {!! Form::label('client_id', 'Select Client', ['class' => 'block font-bold mb-1']) !!}
                                        <select name="client_id" id="client_idSelect" class="form-control">
                                            <option value="">-- Choose Client --</option>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client['id'] }}">{{ $client['company_name'] }}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div id="campaign-section" class="mb-3 col-6" style="display: none;">
                                    {!! Form::label('campaign_id', 'Select Campaign', ['class' => 'block font-bold mb-1']) !!}
                                    {!! Form::select('campaign_id', [], null, [
                                        'id' => 'campaignSelect',
                                        'class' => 'form-control',
                                        'placeholder' => '-- Choose Campaign --',
                                    ]) !!}
                                </div>

                                <div class="mb-3 col-6" id="store-section" style="display: none;">
                                    {!! Form::label('store_code', 'Select Store', ['class' => 'block font-bold mb-1']) !!}
                                    {!! Form::select('store_code', [], null, [
                                        'id' => 'storeSelect',
                                        'class' => 'form-control',
                                        'placeholder' => '-- Choose Store --',
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

        $('#client_idSelect').on('change', function() {
            const clientId = $(this).val();
            const $campaignSelect = $('#campaignSelect');

            if (clientId) {
                $('#campaign-section').show();
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
                                $campaignSelect.append(new Option(camp.name, camp.id));
                            });
                        } else {
                            $campaignSelect.empty().append('<option>No campaigns found</option>');
                        }
                    },
                    error: function() {
                        $campaignSelect.empty().append('<option>Error loading campaigns</option>');
                    }
                });
            } else {
                $('#campaign-section').hide();
                $campaignSelect.empty();
            }
        });
        $('#campaignSelect').on('change', function() {
            const campaignId = $(this).val();
            const $storeSelect = $('#storeSelect');

            if (campaignId) {
                $('#store-section').show();
                $storeSelect.empty().append('<option>Loading...</option>');

                $.ajax({
                    url: '{{ route('fetch.retail.campaigns.store') }}',
                    method: 'POST',
                    data: {
                        campaign_id: campaignId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success && response.store_codes.length > 0) {
                            $('#storeSelect').empty().append(
                                '<option value="">-- Choose Store --</option>');

                            response.store_codes.forEach(function(store) {
                                $('#storeSelect').append(
                                    $('<option>', {
                                        value: store.store_code,
                                        text: store.store_code
                                    })
                                );
                            });

                            $('#store-section').show();
                        } else {
                            $('#storeSelect').empty().append('<option>No stores found</option>');
                        }
                    },
                    error: function() {
                        $storeSelect.empty().append('<option>Error loading stores</option>');
                    }
                });
            } else {
                $('#store-section').hide();
                $storeSelect.empty();
            }
        });
    </script>


    <script>
        function downloadFile() {
            var fileUrl = '{{ asset('sample-files/test-participants-sample.xlsx ') }}';
            console.log(fileUrl);
            var link = document.createElement('a');
            link.href = fileUrl;
            link.download = 'training-participants-sample.xlsx';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
@endsection
