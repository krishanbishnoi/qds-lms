@extends('admin.layouts.default')
@section('content')

    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">

                Certificate Setup
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Certificate</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h4 class="card-title">All Certificates</h4>
                            </div>
                            {{-- <div class="col-md-6 text-end">
                            <a href="{{ route('Reminders.create') }}" class="btn btn-gradient-primary">
                                <i class="mdi mdi-plus"></i> Add New Certificate
                            </a>
                        </div> --}}
                        </div>

                        @if ($certificates->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($certificates as $certificate)
                                            <tr>
                                                <td>
                                                    <strong>{{ $certificate->name }}</strong>
                                                </td>
                                                <td>
                                                    <div class="" role="group">
                                                        <button type="button" class="btn btn-sm viewBtn"
                                                            data-id="{{ $certificate->id }}" data-bs-toggle="modal"
                                                            data-bs-target="#certificate-modal">
                                                            View
                                                        </button>
                                                        <a href="{{ route('change.status', $certificate->id) }}"
                                                            class="btn btn-sm {{ $certificate->is_active == 1 ? 'btn-info' : 'btn-danger' }}"
                                                            title="Choose Certificate">
                                                            {{ $certificate->is_active == 1 ? 'Selected' : 'Choose Certificate' }}
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center">
                                {{ $certificates->links('pagination::bootstrap-4') }}
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="mdi mdi-information"></i> No reminders found. <a
                                    href="{{ route('Reminders.create') }}">Create one now</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="certificate-modal"data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog certificateMan" style="width: 1250px; height:1250px;">
            <div class="modal-content rounded-1">
                <div class="modal-header border-0 p-2">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-2" id="certificateArea">
                    {{-- @dd($certificate->id) --}}
                    <div id="cert_1" class="certTemplate" style="display:none;">
                        @include('admin.certificates.certificate-pdf',['isFromIndex' => '1'])
                    </div>

                    <div id="cert_2" class="certTemplate" style="display:none;">
                        @include('admin.certificates.certificate-pdf-2',['isFromIndex' => '1'])
                    </div>

                    <div id="cert_3" class="certTemplate" style="display:none;">
                        @include('admin.certificates.certificate-pdf-3',['isFromIndex' => '1'])
                    </div>

                    <div id="cert_4" class="certTemplate" style="display:none;">
                        @include('admin.certificates.certificate-pdf-4',['isFromIndex' => '1'])
                    </div>
                    <div id="cert_5" class="certTemplate" style="display:none;">
                        @include('admin.certificates.certificate-pdf-5',['isFromIndex' => '1'])
                    </div>
                    <div id="cert_6" class="certTemplate" style="display:none;">
                        @include('admin.certificates.certificate-pdf-6',['isFromIndex' => '1'])
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).on('click', '.preview-btn', function() {
            let viewName = $(this).data('view');
            let url = "{{ route('certificate.preview', '') }}/" + viewName;
            $('#certificate-frame').attr('src', url);
        });
    </script>
    <script>
        document.querySelectorAll('.viewBtn').forEach(btn => {
            btn.addEventListener('click', function() {
                let id = this.dataset.id;
                console.log(id)
                document.querySelectorAll('.certTemplate').forEach(t => t.style.display = 'none');
                document.getElementById('cert_' + id).style.display = 'block';
            });
        });
    </script>

@endsection
