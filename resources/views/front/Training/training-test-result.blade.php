@extends('front.layouts.trainee-default')
@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Great+Vibes&display=swap"
        rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Noto Sans' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
    <style>
        .certificateMan {
            --bs-modal-width: 720px;
        }

        .testresultModal .modal-content {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            border-radius: 10px;
        }
    </style>
    <header class="traineeHead">
        <div class="container-fluid">
            <div class="d-flex flex-wrap align-items-center">
                <div class="logoSec">
                    <a href="{{ route('front.dashboard') }}"><img src="{{ asset('lms-img/creditsaison-logo.svg') }}"
                            alt="logo" width="170" height="89"></a>
                </div>
                <div class="courseName">
                    {{-- <p class="mb-0">{{ $trainingData->title }}</p> --}}
                </div>
                <div class="courseProgress">
                    <div class="progress-main">
                        <div class="progress-circle" data-progress="100"></div>
                    </div>
                    {{-- <div class="progressText">
                    <strong>Progress</strong>
                    <span>1<i>/4</i></span>
                </div> --}}
                    {{-- <button type="button" class="optionBtn d-lg-none"><img src="{{ asset('front/img/option.svg') }}" alt="icon"
                            width="23" height="23"></button>
                    <a href="{{ route('front.dashboard') }}"><button type="button" class="exitBtn"><img
                                src="{{ asset('front/img/exit.svg') }}.svg" alt="icon" width="23" height="23"></button></a> --}}
                </div>
            </div>
        </div>
    </header>
    {{-- Test Result Model --}}
    <div class="modal d-block testresultModal z-1" id="testresult">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Test Results</h3>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body">
                    <div class="selectUser">
                        <div class="d-sm-flex justify-content-between">
                            <div class="fs-6 blue-text">Scored</div>
                            <div class="text-center">
                                @if ($resultStatus == 'Passed')
                                    <p class="greentxt fs-5 mb-0 text-start"><b
                                            id="percentage">{{ number_format($percentage, 2) }}</b><span
                                            class="lightGreyTxt ms-1">% out of 100%</span></p>
                                @else
                                    <p class="danger-text fs-5 mb-0 text-start"><b
                                            id="percentage">{{ number_format($percentage, 2) }}</b><span
                                            class="lightGreyTxt ms-1">% out of 100%</span></p>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="d-sm-flex justify-content-between">
                            <div class=" fs-6 blue-text">Result</div>
                            <div class="text-center">
                                @if ($resultStatus == 'Passed')
                                    <p class="greentxt fs-5 mb-0 text-start"><b id="result">{{ $resultStatus }}</b></p>
                                @else
                                    <p class="danger-text fs-5 mb-0 text-start"><b id="result">{{ $resultStatus }}</b>
                                    </p>
                                @endif
                            </div>
                        </div>
                        <hr>
                        {{-- @if ($OverAllStatus == 'Passed' && $isLastCourse)
                            <div class="d-sm-flex justify-content-between">
                                <div class=" fs-6 blue-text">Note:</div>
                                <div class="text-center"
                                    style="font-size: 15px;font-weight: 400;color: var(--lightText400);">
                                    This training requires VC (Virtual Classroom) or Classroom training.
                                    Please click the button below to <a href="{{ route('request.for.vc', $trainingId) }}"> Request Special
                                        Training</a> .<br>

                                </div>
                            </div>
                            <hr>
                        @endif --}}
                        <div class="d-sm-flex justify-content-between">
                            <div class="fs-6 blue-text">Certificate</div>
                            <div class="text-center">
                                <div class="d-flex align-items-center justify-content-start gap-3 ">
                                    {{-- @use('Jenssegers\Agent\Agent')
                                    @php
                                        $isMobile = new \Jenssegers\Agent\Agent()->isMobile();
                                    @endphp --}}
                                    @if ($OverAllStatus == 'Passed' && $isLastCourse)
                                        <!-- Last course, passed all tests -->
                                        <button type="button" class="btn btn-secondary smallBtn  py-1 px-4"
                                            data-bs-toggle="modal" data-bs-target="#certificate-modal">View</button>
                                        {{-- @if ($isMobile)
                                            <button type="button" class="btn btn-secondary smallBtn py-1 px-4"
                                                onclick="onSurveySubmit()">Finish Training</button>
                                        @else --}}
                                        <a href="{{ route('front.dashboard') }}"><button type="button"
                                                class="btn btn-secondary fs-7 text-black"
                                                style="background-color: #FFF2E5">Finish Training</button></a>
                                        {{-- @endif --}}
                                    @else
                                        <!-- Not last course or not passed -->
                                        <button type="button" class="btn btn-secondary smallBtn  py-1 px-4"
                                            data-bs-toggle="modal"
                                            data-bs-target="#certificate-not-generated-modal">View</button>
                                        <a href="{{ route('userTrainingDetails.index', ['id' => $trainingId]) . '?user_id=' . Auth::id() }}"
                                            class="btn btn-secondary smallBtn py-1 px-4">Next Course</a>
                                    @endif


                                    <script>
                                        // // Custome for log and alert
                                        function onSurveySubmit() {
                                            if (window.Android && Android.closeActivity) {
                                                Android.closeActivity(window.location.origin);
                                                console.log("thanks");

                                            } else {
                                                // alert("Thank you for finishing the training!");
                                                // Optionally, redirect or close tab
                                                console.log("Thank you for finishing the training!");
                                                // window.close();
                                            }

                                            return true;
                                        }
                                    </script>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- certificate model --}}
    <div class="modal fade" id="certificate-modal"data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered certificateMan">
            <div class="modal-content rounded-1">

                <div class="modal-body p-2 ">
                    @php
                        $templates = [
                            1 => 'admin.certificates.certificate-pdf',
                            2 => 'admin.certificates.certificate-pdf-2',
                            3 => 'admin.certificates.certificate-pdf-3',
                            4 => 'admin.certificates.certificate-pdf-4',
                            5 => 'admin.certificates.certificate-pdf-5',
                            6 => 'admin.certificates.certificate-pdf-6',
                        ];
                    @endphp

                    {{-- Render only active certificate --}}
                    @include($templates[$activeCertificate] ?? $templates[1], ['isFromIndex' => 0])
                    {{-- @use('Jenssegers\Agent\Agent')
                    @php
                        $isMobile = new \Jenssegers\Agent\Agent()->isMobile();
                    @endphp --}}
                    <div class="modal-footer border-0">

                        {{-- @if ($isMobile)
                            <button type="button" class="btn btn-secondary fs-7 text-black" data-bs-dismiss="modal"
                                aria-label="Close" style="background-color: #FFF2E5">Close</button>
                        @else --}}

                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                        <a href="{{ route('front.dashboard') }}"><button type="button"
                                class="btn btn-secondary fs-7 text-black" style="background-color: #FFF2E5"
                                data-bs-dismiss="modal">Back
                                to
                                Home</button></a>
                        {{-- @endif --}}
                        <a href="{{ route('download.user.training.certificate', $trainingData->id) }}">
                            <button type="button" class="btn btn-secondary fs-7"
                                style="background-color: #00407E">Download</button></a>
                    </div>
                </div>

            </div>
        </div>
    </div>
    {{-- certificate-not-generated-modal --}}
    <div class="modal fade" id="certificate-not-generated-modal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="testNamePlaceholder">Certificate Not Generated</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="selectUser">
                        @if ($totalAttendedTestCount < $totalTestCount)
                            <div class="d-sm-flex justify-content-between">
                                <div class="fs-6 blue-text text-center">You need to attend all {{ $totalTestCount }} tests
                                    to generate the certificate of this training.</div>
                            </div>
                        @elseif ($OverAllStatus == 'Failed')
                            <div class="d-sm-flex justify-content-between">
                                <div class="fs-6 blue-text text-center">Your over all result is failed. Certificate is not
                                    generated for failed result.</div>
                            </div>
                        @else
                            <div class="d-sm-flex justify-content-between">
                                <div class="fs-6 blue-text text-center">Thank you, your over all response has been saved
                                    for result contect to admin. </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
