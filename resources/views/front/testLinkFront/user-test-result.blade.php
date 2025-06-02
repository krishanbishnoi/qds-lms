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
                    <a href="{{ route('front.dashboard') }}"><img src="{{ asset('lms-img/qdegrees-logo.svg') }}"
                            alt="logo" width="130" height="33"></a>
                </div>
                <div class="courseName">
                    {{-- <p class="mb-0">{{ $testDetails->title }}</p> --}}
                </div>
                <div class="courseProgress">
                    <div class="progress-main">
                        <div class="progress-circle" data-progress="100"></div>
                    </div>
                    {{-- <div class="progressText">
                    <strong>Progress</strong>
                    <span>1<i>/4</i></span>
                </div> --}}
                    <button type="button" class="optionBtn d-lg-none"><img src="{{ asset('front/img/option.svg') }}"
                            alt="icon" width="23" height="23"></button>
                    <a href="{{ route('front.dashboard') }}"><button type="button" class="exitBtn"><img
                                src="{{ asset('front/img/exit.svg') }}" alt="icon" width="23"
                                height="23"></button></a>
                </div>
            </div>
        </div>

    </header>

    {{-- Test Result Model --}}
    <div class="modal d-block testresultModal" id="testresult" tabindex="-1" aria-hidden="true">
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
                        <div class="d-sm-flex justify-content-between">
                            <div class="fs-6 blue-text">Certificate</div>
                            <div class="text-center">
                                <div class="d-flex align-items-center justify-content-start gap-3 ">
                                    @if ($resultStatus == 'Passed')
                                        <button type="button" class="btn btn-secondary smallBtn  py-1 px-4"
                                            data-bs-toggle="modal" data-bs-target="#certificate-modal">View</button>
                                        {{-- <p class="blacktxt fs-5 mb-0 text-start"><b >Contect To Admin</b> --}}
                                    @else
                                        <p class="blacktxt fs-5 mb-0 text-start"><b>Not Generated</b>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- certificate for test model --}}

    <div class="modal fade" id="certificate-modal"data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered certificateMan">
            <div class="modal-content rounded-1">
                <div class="modal-body p-2 ">
                    <table
                        style="background-image: url('{{ asset('front/img/backgroundimage.png') }}'); background-repeat: no-repeat;width: 100%;background-position: left top;background-size: cover;padding: 0px 32px 32px 32px;">
                        <tr>
                            <td
                                style="padding-top: 60px;padding-left: 20px;font-size: 35px;font-weight:700;color: #ed1c24;">
                                <div style="font-family: 'Sans-Serif';text-transform:uppercase;">
                                    Certificate</div>
                            </td>
                            <td align="right" style="padding-top: 30px;padding-right: 25px;">
                                <img src="{{ asset('lms-img/qdegrees-logo.svg') }}" alt="logo" width="130"
                                    height="33">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"
                                style="font-size: 16px;font-weight: 700;text-transform: uppercase;padding-left: 6px;color: #323232;padding-top: 40px;">
                                of achievement test {{ $testDetails->title }}</td>
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
                                <b>{{ $testAttendee->email }}</b>
                                <p
                                    style="padding-top: 20px; font-family: sans-serif;color: #5c5a59;font-size: 12px;font-weight: 500;margin-top: 20px;width: 70%;margin: auto;padding-bottom: 40px;">
                                    This certificate above verifies that {{ $testAttendee->email }} successfully
                                    completed
                                    the
                                    course {{ $testDetails->title }} on {{ today()->format('d-M-Y') }} as taught by
                                    {{ $admin }}
                                    on
                                    LMS | QDegrees. The certificate indicates the entire course was completed as validated
                                    by the
                                    student. The course duration represents the total video hours of the course at time
                                    of most
                                    recentcompletion.</p>
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
                                    style="display: grid;text-align: center;font-family: sans-serif;font-size: 13px;font-weight: normal;color: #5c5a59;">Director<br>
                                    <b
                                        style="font-weight: 500;font-size: 16px;color: #474645;">{{ $admin }}</b></span>
                            </td>
                        </tr>
                    </table>
                    <div class="modal-footer border-0">
                        <a href="{{ route('front.dashboard') }}"><button type="button"
                                class="btn btn-secondary fs-7 text-black" style="background-color: #FFF2E5"
                                data-bs-dismiss="modal">Back
                                to
                                Home</button></a>
                        <a
                            href="{{ route('download.user.test.certificate.link', ['testId' => $testDetails->id, 'attendeeId' => $testAttendee->link_id]) }}">
                            <button type="button" class="btn btn-secondary fs-7"
                                style="background-color: #00407E">Download</button></a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- <script>
        // For closing page countdown timer
        let countdown = 5; // Set the countdown duration in seconds

        // Update the countdown timer every second
        let timerInterval = setInterval(function() {
            document.getElementById('countdown').innerText = countdown;

            if (countdown <= 0) {
                clearInterval(timerInterval); // Stop the timer
                redirectToTestDetails();
            }

            countdown--;
        }, 1000);

        function redirectToTestDetails() {
            window.location.href = '{{ route('userTestDetails.index.link.copied', ['test_id' => $testDetails->id]) }}';
        }
    </script> --}}
@stop
