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
        {{--  <livewire:upload-training-participant />  --}}

        <div class="row">
            <div class="col-lg-12 ">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="p-4 row">
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
                                                    <div class="col-md-5">
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
