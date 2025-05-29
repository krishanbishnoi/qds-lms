@extends('front.layouts.trainee-default')
@section('content')
    <?php
    // $startTime = strtotime($trainingDetails->start_date_time);
    $endTime = strtotime($trainingDetails->end_date_time); // get taining end dateTime
    $currentDateTime = date('Y-m-d H:i:s'); //get current end dateTime
    $currentTimestamp = strtotime($currentDateTime);
    $difference = $endTime - $currentTimestamp;
    $hours = floor($difference / (60 * 60));
    $minutes = floor(($difference - $hours * 60 * 60) / 60);

    $trainingId = request()->route('id');
    ?>
    <header class="traineeHead">
        <div class="container-fluid">
            <div class="d-flex flex-wrap align-items-center">
                <div class="logoSec">
                    <a href="{{ route('front.dashboard') }}"><img src="../lms-img/qdegrees-logo.svg" alt="logo" width="130" height="33"></a>
                </div>
                <div class="courseName">
                    <p class="mb-0">{{ $trainingDetails->title }}</p>
                </div>
                <div class="courseProgress">
                    <div class="progress-main">
                        <div class="progress-circle" data-progress="90"><img src="../front/img/processing.svg"
                                width="20" height="20"></div>
                    </div>
                    <div class="progressText">
                        <strong>Progress</strong>
                        <span>1<i>/4</i></span>
                    </div>
                    <button type="button" class="optionBtn d-lg-none"><img src="../front/img/option.svg" alt="icon"
                            width="23" height="23"></button>
                    <button type="button" class="exitBtn"><img src="../front/img/exit.svg" alt="icon" width="23"
                            height="23"></button>
                </div>
            </div>
        </div>
    </header>
    <div class="d-flex flex-wrap paddingTop">
        <div class="courseName trainingNameMobile d-lg-none w-100">
            <p class="mb-0">{{ $trainingDetails->title }}</p>
        </div>
        <div class="abouttraining">
            <div class="tab-content" id="nav-tabContent">
                {{-- @foreach ($trainingDocuments as $trainingDocument) --}}
                @foreach ($trainingDocuments as $index => $document)
                    <div class="tab-pane fade {{ $index === 0 ? ' show active' : '' }}" id="question{{ $index + 1 }}"
                        role="tabpanel">
                        <div class="imgwrapper">
                            @if ($document->type === 'video')
                                <iframe width="100%" height="500"
                                    src="{{ TRAINING_DOCUMENT_URL . $document->document }}" title="YouTube video player"
                                    frameborder="0" allowfullscreen></iframe>
                            @elseif($document->type === 'doc')
                                <iframe src="{{ TRAINING_DOCUMENT_URL . $document->document }}" alt="img"
                                    width="100%" height="500"></iframe>
                            @elseif($document->type === 'image')
                                <img src="{{ TRAINING_DOCUMENT_URL . $document->document }}" alt="img" width="100%">
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="timeSection">
                <div class="timeDescription">
                    <i><img src="../front/img/timer.svg" alt="img"></i>
                    <p><span>Time Left</span><span id="countdown" class="ml-5"></span>Embrace the urgency: Time Remaining
                        for Training.
                        Every second counts as you embark on your training journey. The time left is a precious resource
                        that demands your focus, determination, and dedication. With each passing moment, opportunities for
                        growth and improvement await.</p>

                </div>
                <div class="timerGroup">

                </div>
            </div>
            <div class="about_content">
                <h2>About this Training</h2>
                <p>{!! $trainingDetails->description !!}</p>
                <hr>
                <div class="otherDetail">
                    <strong>Other Details</strong>
                    <p><span>Training Type : {{ $trainingDetails->type }}</span><span>Trainee : {{ $totalTrainees }}</span>
                    </p>
                    <p><span>Total Content : {{ count($trainingDocuments) }}</span><span>Total Time to finish :
                            {{ $hours . 'h ' . $minutes . 'm ' }}</span></p>

                </div>
                <hr>
                <div class="certificates">
                    <strong class="mb-2 d-block">Certificates</strong>
                    <p class="mb-2">Lorem Ipsum is simply dummy text of the printing and typesetting industry
                    </p>
                    <p class="mb-2">Complete the training and test to achieve this certificate</p>
                    <a href="javascript:void(0)" class="btn btn-light py-2 px-3 fs-7">Certificate</a>
                </div>
            </div>
        </div>

        <div class="courseContent" id="courseContent">
            <div class="courselisting">
                <strong>Course Content</strong>
                <ul class="nav nav-tabs" id="nav-tab" role="tablist">
                    {{-- @foreach ($trainingDocuments as $trainingDocument) --}}
                    @foreach ($trainingDocuments as $index => $document)
                        <li>
                            <a href="#question{{ $index + 1 }}"
                                class=" courseGroup {{ $index === 0 ? ' active' : '' }}" data-bs-toggle="tab"
                                aria-controls="nav-question{{ $index + 1 }}">
                                <div class="courseStatus">
                                    <img src="../front/img/processing.svg" width="20" height="20">
                                </div>
                                <p>{{ $index + 1 }}. {{ $document->title }}</p>
                                @if ($document->type === 'video')
                                    <span><img class="me-2" src="../front/img/videoicon.svg" width="18"
                                            height="18">Video</span>
                                @elseif($document->type === 'pdf')
                                    <span><img class="me-2" src="../front/img/pdficon.svg" width="18"
                                            height="18">PDF</span> height="500"></iframe>
                                @elseif($document->type === 'image')
                                    <span><img class="me-2" src="../front/img/photoicon.svg" width="16"
                                            height="16">Image</span>
                                @endif

                            </a>
                        </li>
                    @endforeach

                </ul>
                <a class="btn btn-secondary py-2" href="{{ route('userTraining.test', $trainingId) }}">Begin Test</a>
            </div>
        </div>
    </div>

    <div class="overllayBg" id="overllayBg" style="display: none;">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
        <script src="../js/owl.carousel.min.js"></script>
        <script src="../js/custom.js"></script>
    </div>

    <script>
        $(document).ready(function() {
            $(document).on("contextmenu", function() {
                return false;
            });

            $(document).keydown(function(e) {
                if (e.which === 123) { // F12 key
                    return false;
                }
            });

            $(document).on("keydown", function(e) {
                if (e.ctrlKey && (e.which === 85 || e.which === 83)) { // Ctrl+U or Ctrl+S
                    return false;
                }
            });
        });


        var hours = <?php echo $hours; ?>;
        var minutes = <?php echo $minutes; ?>;
        var seconds = (hours * 60 * 60) + (minutes * 60);

        function countdown() {
            var countdownElement = document.getElementById('countdown');

            if (seconds > 0) {
                seconds--;
            } else {
                clearInterval(timer);
            }

            var paddedSeconds = (seconds % 60).toString().padStart(2, '0');
            var paddedMinutes = Math.floor((seconds / 60) % 60).toString().padStart(2, '0');
            var paddedHours = Math.floor(seconds / 3600).toString().padStart(2, '0');

            countdownElement.innerHTML = paddedHours + ' hours ' + paddedMinutes + ' minutes ' + paddedSeconds + ' seconds';
        }

        var timer = setInterval(countdown, 1000); // Call the countdown function every second (1000 milliseconds)
    </script>

@stop
