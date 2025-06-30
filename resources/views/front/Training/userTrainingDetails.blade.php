@extends('front.layouts.trainee-default')
@section('content')
    @php
        // $startTime = strtotime($trainingDetails->start_date_time);
        $endTime = strtotime($trainingDetails->end_date_time); // get taining end dateTime
        $currentDateTime = date('Y-m-d H:i:s'); //get current end dateTime
        $currentTimestamp = strtotime($currentDateTime);
        $difference = $endTime - $currentTimestamp;
        $hours = floor($difference / (60 * 60));
        $minutes = floor(($difference - $hours * 60 * 60) / 60);
        $trainingId = request()->route('id');
    @endphp
    <header class="traineeHead">
        <div class="container-fluid">
            <div class="d-flex flex-wrap align-items-center">
                <div class="logoSec">
                    <a href="{{ route('front.dashboard') }}"><img src="{{ asset('lms-img/qdegrees-logo.svg') }}" alt="logo"
                            width="130" height="33"></a>
                </div>
                <div class="courseName">
                    <p class="mb-0">{{ $trainingDetails->title }}</p>
                </div>
                <div class="courseProgress">
                    <div class="progress-main">
                        @php
                            if ($totalCoursesCount > 0) {
                                $progressPercentage = ($completedCoursesCount / $totalCoursesCount) * 100;
                                $roundedProgressPercentage = round($progressPercentage, 2);
                            } else {
                                $progressPercentage = 0;
                                $roundedProgressPercentage = 0;
                            }
                        @endphp

                        <div class="progress-circle" data-progress="{{ $roundedProgressPercentage }}"><img
                                src="{{ asset('front/img/processing.svg') }}" width="20" height="20"></div>
                    </div>
                    <div class="progressText">
                        <strong>Progress</strong>
                        <span>{{ $completedCoursesCount }}<i>/{{ $totalCoursesCount }}</i></span>
                    </div>
                    <button type="button" class="optionBtn d-lg-none"><img src="{{ asset('front/img/option.svg') }}"
                            alt="icon" width="23" height="23"></button>
                    <a href="{{ route('front.dashboard') }}"><button type="button" class="exitBtn"><img
                                src="{{ asset('front/img/exit.svg') }}" alt="icon" width="23"
                                height="23"></button></a>
                </div>
            </div>
        </div>
    </header>
    <div class="d-flex flex-wrap paddingTop">
        <div class="courseName trainingNameMobile d-lg-none w-100">
            <p class="mb-0">{{ $trainingDetails->title }}</p>
        </div>
        <div class="abouttraining">
            <div class="imgwrapper thumb">
                <figure><img src="{{ TRAINING_DOCUMENT_URL . $trainingDetails->thumbnail }}" alt=""></figure>
            </div>
            <div class="tab-content imgwrapper documents-view" id="nav-tabContent1">
                @php
                    $previousDocumentCompleted = true; // Variable to track if the previous document has been completed
                @endphp
                @foreach ($trainingCourses as $course)
                    @foreach ($course->CourseContentAndDocument as $key => $content)
                        @php
                            $documentCompletion = \App\Models\TraineeAssignedTrainingDocument::where(
                                'user_id',
                                Auth::user()->id,
                            )
                                ->where('course_id', $course->id)
                                ->where('document_id', $content['id'])
                                ->first();

                            // Check if the previous document has been completed
                            if ($key > 0) {
                                $previousDocumentCompleted = $documentCompletion && $documentCompletion->status === 1;
                            }
                        @endphp

                        {{-- @if ($previousDocumentCompleted) --}}
                        <!-- Display the document -->
                        <div class="tab-pane courseDocument{{ $content['id'] }}" id="courseViseView{{ $content['id'] }}">
                            @if ($content['type'] === 'video')
                                <!-- Video Content -->
                                <video id="myVideo" class="videoContent" width="100%" height="500px" controls>
                                    <source src="{{ TRAINING_DOCUMENT_URL . $content['document'] }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @elseif ($content['type'] === 'doc' && $content['document_type'] === 'pdf')
                                <div class="tab-pane" id="courseViseView{{ $content['id'] }}">
                                    <iframe src="{{ config('TRAINING_DOCUMENT_URL') . $content['document'] }}"
                                        width="100%" height="500px"></iframe>
                                @elseif (($content['type'] === 'doc' && $content['document_type'] == 'ppt') || $content['document_type'] == 'pptx')
                                    @if (config('TRAINING_DOCUMENT_URL') . $content['document'] != '')
                                        <iframe
                                            src="https://view.officeapps.live.com/op/view.aspx?src={{ asset('training_document/' . $content['document']) }}"
                                            style="width: 100%; height: 500px;" frameborder="0">
                                        </iframe>
                                    @endif
                                @elseif (($content['type'] === 'doc' && $content['document_type'] == 'xls') || $content['document_type'] == 'xlsx')
                                    @if (config('TRAINING_DOCUMENT_URL') . $content['document'] != '')
                                        <iframe
                                            src="https://view.officeapps.live.com/op/view.aspx?src={{ asset('training_document/' . $content['document']) }}"
                                            style="width: 100%; height: 500px;" frameborder="0">
                                        </iframe>
                                    @endif
                                @elseif (($content['type'] === 'doc' && $content['document_type'] == 'doc') || $content['document_type'] == 'docx')
                                    @if (config('TRAINING_DOCUMENT_URL') . $content['document'] != '')
                                        <iframe
                                            src="https://view.officeapps.live.com/op/view.aspx?src={{ asset('training_document/' . $content['document']) }}"
                                            style="width: 100%; height: 500px;" frameborder="0">
                                        </iframe>
                                    @endif
                                @elseif ($content['type'] === 'image')
                                    @if (config('TRAINING_DOCUMENT_URL') . $content['document'] != '')
                                        <img class="me-2"
                                            src="{{ config('TRAINING_DOCUMENT_URL') . $content['document'] }}"
                                            style="width:500px; height:500px">
                                    @endif
                            @endif
                        </div>
                        {{-- @else
                            <!-- Display an alert or any other action indicating that the previous document needs to be completed -->
                            <div class="alert alert-warning" role="alert">
                                Please read the previous document before accessing this one.
                            </div>
                        @endif --}}
                    @endforeach
                @endforeach

                {{-- @foreach ($trainingCourses as $course)
                    @foreach ($course->CourseContentAndDocument as $content)
                        @php
                            $documentCompletion = \App\Models\TraineeAssignedTrainingDocument::where('user_id', Auth::user()->id)
                                ->where('course_id', $course->id)
                                ->where('document_id', $content['id'])
                                ->first();
                        @endphp
                        @if ($content['type'] === 'video')
                            <!-- Video Content -->
                            <div class="tab-pane courseDocument{{ $content['id'] }}"
                                id="courseViseView{{ $content['id'] }}">
                                <video id="myVideo" class="videoContent" width="100%" height="500px" controls>
                                    <source src="{{ TRAINING_DOCUMENT_URL . $content['document'] }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        @elseif ($content['type'] === 'doc')
                            <!-- Doc Content -->
                            <div class="tab-pane courseDocument{{ $content['id'] }}"
                                id="courseViseView{{ $content['id'] }}" role="tabpanel">
                                <div class="imgwrapper">
                                    <iframe id="myIframe" src="{{ TRAINING_DOCUMENT_URL . $content['document'] }}"
                                        width="100%" style="height:calc(100vh - 100px)" type="application/pdf"
                                        frameborder="0" scrolling="auto"></iframe>
                                    <!-- Container for Doc.js rendering -->
                                    <div id="pdf-container{{ $content['id'] }}"></div>
                                    <!-- Placeholder for the checkbox -->
                                    <div style="background: #feffff; margin-top: -5px; padding: 8px; text-align: center;">
                                        <span>
                                            <input type="checkbox" id="readCheck" data-content-id="{{ $content['id'] }}"
                                                data-content-length="{{ $content['length'] }}"
                                                data-course-id="{{ $course->id }}"
                                                {{ $documentCompletion && ($documentCompletion->status === 1 || $documentCompletion->status === null) ? 'checked' : '' }}>
                                            <label for="readCheck" style="color: white;">I acknowledge that I have read the
                                                full document.</label>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endforeach --}}
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
                    <p><span>Training Type : {{ $trainingDetails->type }}</span><span>Trainee :
                            {{ $totalTrainees }}</span>
                    </p>
                    <p><span>Total Content : {{ count($trainingCourses) }}</span><span>Total Time to finish :
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
        <div class="courseContent courses-and-document-listing" id="courseContent">
            <div class="courselisting">
                <strong>Course Content</strong>
                <div class="accordion" id="accordionExample">
                    @foreach ($trainingCourses as $index => $course)
                        <div class="accordion-item">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $index + 1 }}" aria-expanded="false"
                                aria-controls="collapse{{ $index + 1 }}">
                                {{ $course['title'] }}
                            </button>
                            <div id="collapse{{ $index + 1 }}" class="accordion-collapse collapse"
                                data-bs-parent="#accordionExample">
                                <ul class="nav nav-tabs" id="nav-tab" role="tablist">
                                    @foreach ($course->CourseContentAndDocument as $key => $content)
                                        @php
                                            $documentCompletion = \App\Models\TrainingDocumentCompletion::where(
                                                'user_id',
                                                Auth::user()->id,
                                            )
                                                ->where('document_id', $content['id'])
                                                ->first();
                                        @endphp
                                        <li>
                                            <a href="#courseViseView{{ $content['id'] }}" class="courseGroup"
                                                data-course-id="{{ $course->id }}"
                                                data-content-id="{{ $content['id'] }}"
                                                data-video-duration="{{ $content['length'] }}"
                                                data-pdf-pages="{{ $content['length'] }}">
                                                <div class="courseStatus">
                                                    <img src="{{ asset('front/img/processing.svg') }}" width="20"
                                                        height="20">
                                                </div>
                                                <p>{{ $content['id'] }}. {{ $content['title'] }}</p>
                                                <span>
                                                    @if ($content['type'] === 'video')
                                                        <img class="me-2" src="{{ asset('front/img/videoicon.svg') }}"
                                                            width="18" height="18">Video
                                                    @elseif($content['type'] === 'doc')
                                                        <img class="me-2" src="{{ asset('front/img/pdficon.svg') }}"
                                                            width="18" height="18">Doc
                                                    @endif
                                                </span>
                                            </a>
                                            @if (!empty($course->test_id))
                                                @php
                                                    $testAlreadySubmited = \App\Models\TrainingTestParticipants::where(
                                                        'training_id',
                                                        $training_id,
                                                    )
                                                        ->where('course_id', $course->id)
                                                        ->where('test_id', $course->test_id)
                                                        ->where('trainee_id', Auth::user()->id)
                                                        ->first();
                                                @endphp

                                                @if (!empty($testAlreadySubmited) && $testAlreadySubmited->status == 1)
                                                    <a class="btn btn-secondary py-2" href="javascript:void()"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#alreadySubmittedTest">Begin Test</a>
                                                @else
                                                    <a class="btn btn-secondary py-2"
                                                        href="{{ route('userTraining.test', ['training_id' => $training_id, 'course_id' => $course->id, 'test_id' => $course->test_id]) }}">Begin
                                                        Test</a>
                                                @endif
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade " id="testresult" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Test Results</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="selectUser">
                        <div class="d-sm-flex justify-content-between">
                            <div class="fs-6 blue-text">Scored</div>
                            <div class="text-center">
                                <p class="greentxt fs-5 mb-0 text-start"><b>95%</b><span class="lightGreyTxt ms-1">out
                                        of 100%</span></p>
                            </div>
                        </div>
                        <hr>
                        <div class="d-sm-flex justify-content-between">
                            <div class=" fs-6 blue-text">Result</div>
                            <div class="text-center">
                                <p class="greentxt fs-5 mb-0 text-start"><b>Passed</b></p>
                            </div>
                        </div>
                        <hr>
                        <div class="d-sm-flex justify-content-between">
                            <div class="fs-6 blue-text">Certificate</div>
                            <div class="text-center">
                                <div class="d-flex align-items-center justify-content-start gap-3 ">
                                    <button type="button" class="btn btn-secondary smallBtn  py-1 px-4"
                                        data-bs-toggle="modal" data-bs-target="#certificate-modal">View</button>
                                    <a href=""><img src="{{ asset('front/img/download-btn.svg') }}"
                                            alt="" width="28"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="certificate-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg model-size">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Certificate</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="innerBox">
                        <div class="preview">
                            <div class="d-md-flex justify-content-between">
                                <img src="{{ asset('front/img/logo.svg') }}" alt="logo" width="90">
                                <p class="w-25 fs-12 lightGreyTxt">Lorem ipsum dolor sit amet consectetur adipisicing
                                    elit. Repellat,
                                    fuga?</p>
                            </div>
                            <p class="fs-12 text-dark fw-medium mb-1">CERTIFICATION OF COMPLETION</p>
                            <h1 class="fs-3 text-dark">Lorem Ipsum is simply dummy text of the printing and typesetting
                                industry.</h1>
                            <p class="fs-12 text-dark fw-medium">INSTRUCTOR : Mark Mathew</p>
                            <h2 class="text-dark fs-5 fw-bold mt-md-5 mt-3">Mark Mathews</h2>
                            <p class="text-dark mb-0">Date: <span class="fw-bold"> Mar 3, 2022</span></p>
                            <p class="text-dark">Length:<span class="fw-bold"> 3 Days</span></p>
                        </div>
                    </div>
                    <p class="text-dark fs-12 pt-2">This certificate above verifies that <span
                            class="blue-text fw-bold">Vaibhav Saini</span> successfully completed the course<span
                            class="blue-text fw-bold"> User
                            Experience Design Essentials - Adobe XD UI UX Design</span> on 03/03/2022 as taught by <span
                            class="blue-text fw-bold"> Mark Mathew </span>on
                        Udemy. The certificate indicates the entire course was completed as validated by the student.
                        The course duration represents the total video hours of the course at time of most recent
                        completion.</p>
                </div>
                <div class="modal-footer border-0">
                    <a href=""><button type="button" class="btn btn-secondary fs-7" data-bs-dismiss="modal">Back
                            to
                            Home</button></a>
                    <button type="button" class="btn btn-secondary fs-7">Download</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade " id="alreadySubmittedTest" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="testNamePlaceholder">Test</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="selectUser">
                        <div class="d-sm-flex justify-content-between">
                            <div class="fs-6 blue-text text-center">This Course Test Response Already Submitted.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="overllayBg" id="overllayBg" style="display: none;"></div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script>
        //For pdf compalated or not
        $(document).ready(function() {
            // Use event delegation to handle dynamically added checkboxes
            $(document).on('change', '#readCheck', function() {
                if ($(this).is(':checked')) {
                    var contentId = $(this).data('content-id');
                    var contentLength = $(this).data('content-length');
                    var courseId = $(this).data('course-id');
                    // Perform your AJAX request here
                    $.ajax({
                        url: "{{ route('userTrainingDetails.document.progress') }}",
                        method: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'content_id': contentId,
                            'course_id': courseId,
                            'content_length': contentLength,
                        },
                        success: function(response) {
                            console.log('Data saved successfully');
                            // location.reload(true);
                        },
                        error: function(error) {
                            console.error('Error saving data:', error);
                        }
                    });
                }
            });
        });
        //For Video compalated or not
        $('a.courseGroup').click(function(event) {
            event.preventDefault();
            var video = document.getElementById('myVideo');
            var contentId = $(this).data('content-id');
            var courseId = $(this).data('course-id');
            var contentLeanth = $(this).data('video-duration');
            video.addEventListener('timeupdate', function() {
                if (video.currentTime >= contentLeanth) {
                    $.ajax({
                        url: "{{ route('userTrainingDetails.document.progress') }}",
                        method: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'content_id': contentId,
                            'course_id': courseId,
                            'content_length': contentLeanth,
                        },
                        success: function(response) {
                            console.log(response);
                            // location.reload(true);
                        },
                        error: function(error) {
                            console.error(error);
                        }
                    });
                }
            });
        });
    </script>
    <script>
        jQuery("a.courseGroup").click(function(event) {
            event.preventDefault();
            jQuery(".tab-content .tab-pane").hide();
            jQuery('a.courseGroup').removeClass('active');
            jQuery(this).addClass('active');
            jQuery(".thumb").hide();
            var target = jQuery(this).attr('href');
            jQuery(target).show();
        });
    </script>
    <script>
        // $(document).ready(function() {
        //     $(document).on("contextmenu", function() {
        //         return false;
        //     });
        //     $(document).keydown(function(e) {
        //         if (e.which === 123) { // F12 key
        //             return false;
        //         }
        //     });
        //     $(document).on("keydown", function(e) {
        //         if (e.ctrlKey && (e.which === 85 || e.which === 83)) { // Ctrl+U or Ctrl+S
        //             return false;
        //         }
        //     });
        // });
        $(document).ready(function() {
            $('a').on('click', function(e) {
                var href = $(this).attr('href');
                if (href && (href.endsWith('.pdf') || href.endsWith('.doc') || href.endsWith('.xlsx'))) {
                    e.preventDefault();
                    // Optionally, you can display a message or perform some other action to indicate that the file is not downloadable
                    alert('File downloading is disabled.');
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
