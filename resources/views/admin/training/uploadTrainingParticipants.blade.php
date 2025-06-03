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
                        <div class="p-4 row">
                            <!-- Project Selection -->
                            <div class="col-md-6 mb-3">
                                {!! Form::label('project', 'Select Project', ['class' => 'block font-bold mb-1']) !!}
                                {!! Form::select('project', $projects, null, [
                                    'id' => 'projectSelect',
                                    'class' => 'form-control',
                                    'placeholder' => '-- Choose Project --',
                                ]) !!}
                            </div>

                            <!-- Project-dependent fields -->
                            <div id="retailiq-section" class="col-md-6 mb-3" style="display: none;">
                                <label for="auction_id" class="block font-bold mb-1">Auction ID</label>
                                <input type="text" name="auction_id" id="auction_id" class="form-control"
                                    placeholder="Enter Auction ID">
                            </div>

                            <div id="method-section" class="col-md-6 mb-3" style="display: none;">
                                {!! Form::label('method', 'Select Method', ['class' => 'block font-bold mb-1']) !!}
                                {!! Form::select('method', $methods, null, [
                                    'id' => 'methodSelect',
                                    'class' => 'form-control',
                                    'placeholder' => '-- Choose Method --',
                                ]) !!}
                            </div>

                            <!-- Excel Upload -->
                            <div id="excel-upload-section" class="box search-panel collapsed-box" style="display: none;">
                                <div class="box-body mb-4">
                                    <form action="{{ route('import.training-participants', $training_id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="col-md-6 col-sm-2">
                                            <div class="form-group">
                                                <input type="file" name="file" class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="d-md-flex justify-content-between align-items-center gap-5"
                                            style="display: block !important">
                                            <button class="btn btn-primary" type="submit">Upload Users</button>
                                            <a href="{{ asset('sample-files/import-training-participants-sample.xlsx') }}"
                                                class="btn btn-primary" style="margin-left:100px">Download sample
                                                file</a>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Placeholder for 'fromUser' future UI -->
                            <div id="user-selection-section" class="col-md-6 mt-4" style="display: none;">
                                {!! Form::label('user_ids', 'Select Users', ['class' => 'block font-bold mb-1']) !!}
                                {!! Form::select('user_ids[]', $users, null, [
                                    'class' => 'form-control select2-form',
                                    'multiple' => 'multiple',
                                    'id' => 'select2-users',
                                ]) !!}
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
