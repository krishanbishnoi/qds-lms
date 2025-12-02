@extends('admin.layouts.default')
@section('content')

    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                    <i class="mdi mdi-bell-ring"></i>
                </span>
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
                                                        <button type="button" class="btn btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#certificate-modal">View</button>
                                                        <a href="{{ route('change.status', $certificate->id) }}"
                                                            class="btn btn-sm {{ $certificate->is_active == 1 ? 'btn-info' : 'btn-danger'  }}" title="Choose Certificate">
                                                            {{ $certificate->is_active == 1 ? 'Selected' : 'Choose Certificate'}}
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
        <div class="modal-dialog modal-dialog-centered certificateMan">
            <div class="modal-content rounded-1">
                <div class="modal-header border-0 p-2">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-2 ">
                    <div class="table-responsive">
                        <table
                            style="background-image: url('{{ asset('front/img/backgroundimage.png') }}'); background-repeat: no-repeat;width: 100%;background-position: left top;background-size: cover;padding: 0px 32px 32px 32px;">
                            <tr>
                                <td
                                    style="padding-top: 60px;padding-left: 20px;font-size: 35px;font-weight:700;color: #ed1c24;">
                                    <div style="font-family: 'Sans-Serif';text-transform:uppercase;">
                                        Certificate</div>
                                </td>
                                <td align="right" style="padding-top: 30px;padding-right: 25px;">
                                    <img src="{{ asset('lms-img/creditsaison-logo.svg') }}" alt="logo" width="170"
                                        height="89">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"
                                    style="font-size: 16px;font-weight: 700;text-transform: uppercase;padding-left: 6px;color: #323232;padding-top: 40px;">
                                    of achievement test COC</td>
                            </tr>
                            <tr align="center">
                                <td colspan="2" width="100%"
                                    style="text-transform: uppercase;font-size: 16px;font-weight: 700;color: #2c2c2c;font-family: sans-serif;font-size: 12px;    font-weight: 500;padding-top: 30px;">
                                    proudly presented to :
                                </td>

                            </tr>
                            <tr style="text-align: center;">
                                <td colspan="2"
                                    style="font-weight: 800;font-family: 'Sans-Serif';font-size: 35px;color: #ed1c24;padding-top: 20px;">
                                    <b>{{ Auth::user()->fullname }}</b>
                                    <p
                                        style="padding-top: 20px; font-family: sans-serif;color: #5c5a59;font-size: 12px;font-weight: 500;margin-top: 20px;width: 70%;margin: auto;padding-bottom: 40px;">
                                        This certificate acknowledges that <strong>{{ Auth::user()->fullname }}</strong>
                                        has
                                        successfully completed
                                        the digital training program
                                        <strong>Soft Skills</strong> on
                                        <strong>{{ today()->format('d-M-Y') }}</strong>,
                                        delivered via the LMS
                                        platform at QDegrees.
                                        <br><br>
                                        It is awarded in recognition of the learner’s active participation and completion of
                                        the
                                        required
                                        training content.
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td width="50%" style="padding-bottom: 80px;">
                                    <span
                                        style="display: grid;text-align: center;font-family: sans-serif;font-size: 13px;font-weight: normal;color: #5c5a59;">Date<br>
                                        <b
                                            style="font-weight: 500;font-size: 16px;color: #474645;">{{ today()->format('d-M-Y') }}</b></span>
                                </td>
                                <td width="50%" style="padding-bottom: 80px;">
                                    <span
                                        style="display: grid;text-align: center;font-family: sans-serif;font-size: 13px;font-weight: normal;color: #5c5a59;">Sr.
                                        Manager<br>Training & Development<br>
                                        <b style="font-weight: 500;font-size: 16px;color: #474645;">Qdegrees</b></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    {{-- <div class="modal-footer border-0">
                        @use('Jenssegers\Agent\Agent')

                        @if (new Agent()->isMobile())
                            <button type="button" class="btn btn-secondary fs-7 text-black" data-bs-dismiss="modal"
                                aria-label="Close" style="background-color: #FFF2E5">Close</button>
                        @else
                            <a href="{{ route('front.dashboard') }}"><button type="button"
                                    class="btn btn-secondary fs-7 text-black" style="background-color: #FFF2E5"
                                    data-bs-dismiss="modal">Back
                                    to
                                    Home</button></a>
                        @endif
                        <a href="{{ route('download.user.training.certificate', $trainingData->id) }}">
                            <button type="button" class="btn btn-secondary fs-7"
                                style="background-color: #00407E">Download</button></a>
                    </div> --}}
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

@endsection
