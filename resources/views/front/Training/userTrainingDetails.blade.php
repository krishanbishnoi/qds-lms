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
                            $progressPercentage =
                                $totalCoursesCount > 0 ? ($completedCoursesCount / $totalCoursesCount) * 100 : 0;
                            $roundedProgressPercentage = round($progressPercentage, 2);
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
                                ->where('status', 1)
                                ->where('document_id', $content['id'])
                                ->first();

                            // Check if the previous document has been completed
                            if ($key > 0) {
                                $previousDocumentCompleted = $documentCompletion && $documentCompletion->status === 1;
                            }
                        @endphp

                        <div class="tab-pane courseDocument{{ $content['id'] }}" id="courseViseView{{ $content['id'] }}">
                            @if ($content['type'] === 'video')
                                <video id="myVideo" class="videoContent" width="100%" height="500px" controls>
                                    <source
                                        src="{{ config('constants.TRAINING_DOCUMENT_URL') . '/' . $content['document'] }}"
                                        type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @elseif ($content['type'] === 'doc' && $content['document_type'] === 'pdf')
                                <iframe src="{{ asset('training_document/' . $content['document']) }}" width="100%"
                                    height="500px" style="border: none;"></iframe>
                            @elseif (($content['type'] === 'doc' && $content['document_type'] == 'ppt') || $content['document_type'] == 'pptx')
                                @if (config('constants.TRAINING_DOCUMENT_URL') . '/' . $content['document'] != '')
                                    <iframe
                                        src="https://view.officeapps.live.com/op/view.aspx?src={{ asset('training_document/' . $content['document']) }}"
                                        style="width: 100%; height: 500px;" frameborder="0"></iframe>
                                @endif
                            @elseif (($content['type'] === 'doc' && $content['document_type'] == 'xls') || $content['document_type'] == 'xlsx')
                                @if (config('constants.TRAINING_DOCUMENT_URL') . '/' . $content['document'] != '')
                                    <iframe
                                        src="https://view.officeapps.live.com/op/view.aspx?src={{ asset('training_document/' . $content['document']) }}"
                                        style="width: 100%; height: 500px;" frameborder="0"></iframe>
                                @endif
                            @elseif (($content['type'] === 'doc' && $content['document_type'] == 'doc') || $content['document_type'] == 'docx')
                                @if (config('constants.TRAINING_DOCUMENT_URL') . '/' . $content['document'] != '')
                                    <iframe
                                        src="https://view.officeapps.live.com/op/view.aspx?src={{ asset('training_document/' . $content['document']) }}"
                                        style="width: 100%; height: 500px;" frameborder="0"></iframe>
                                @endif
                            @elseif ($content['type'] === 'image')
                                @if (config('constants.TRAINING_DOCUMENT_URL') . '/' . $content['document'] != '')
                                    <img class="me-2"
                                        src="{{ config('constants.TRAINING_DOCUMENT_URL') . '/' . $content['document'] }}"
                                        style="width:500px; height:500px">
                                @endif
                            @endif
                        </div>
                    @endforeach
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
                    <p><span>Training Type : {{ $trainingDetails->type }}</span><span>Trainee :
                            {{ $totalTrainees }}</span></p>
                    <p><span>Total Content : {{ count($trainingCourses) }}</span><span>Total Time to finish :
                            {{ $hours . 'h ' . $minutes . 'm ' }}</span></p>
                </div>
                <hr>
                <div class="certificates">
                    <strong class="mb-2 d-block">Certificates</strong>
                    <p class="mb-2">Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>
                    <p class="mb-2">Complete the training and test to achieve this certificate</p>
                    <a href="javascript:void(0)" class="btn btn-light py-2 px-3 fs-7">Certificate</a>
                </div>
            </div>
        </div>

        <div class="courseContent courses-and-document-listing" id="courseContent">
            <div class="courselisting">
                <strong class="mb-3 d-block fs-5 fw-semibold">Course Content</strong>
                <div class="accordion" id="accordionExample">
                    @foreach ($trainingCourses as $index => $course)
                        @php
                            $isFirstCourse = $index === 0;
                            $isCompleted = checkIfCourseCompleted($course->id);
                            $isPreviousCompleted =
                                $index === 0 ? true : checkIfCourseCompleted($trainingCourses[$index - 1]->id);
                            $canAccess = $isFirstCourse || $isPreviousCompleted;
                        @endphp

                        <div class="accordion-item mb-3 border rounded shadow-sm">
                            <h2 class="accordion-header" id="heading{{ $index }}">
                                <button
                                    class="accordion-button collapsed bg-light {{ !$canAccess ? 'locked-course' : '' }}"
                                    type="button" data-bs-toggle="{{ $canAccess ? 'collapse' : '' }}"
                                    data-bs-target="{{ $canAccess ? '#collapse' . $index : '' }}"
                                    aria-expanded="{{ $isFirstCourse ? 'true' : 'false' }}"
                                    aria-controls="collapse{{ $index }}" data-course-id="{{ $course->id }}"
                                    onclick="{{ !$canAccess ? 'showLockedMessage(this); return false;' : '' }}">
                                    {{ $course['title'] }}
                                    @if ($isCompleted)
                                        <span class="ms-2 badge bg-success">Completed</span>
                                    @endif
                                </button>
                            </h2>

                            <div id="collapse{{ $index }}"
                                class="accordion-collapse collapse {{ $isFirstCourse ? 'show' : '' }}"
                                aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionExample">
                                <div class="accordion-body p-3">
                                    @if ($course->CourseContentAndDocument->isNotEmpty())
                                        @php
                                            $allContentCompleted = true;
                                            $hasTest = !empty($course->test_id);
                                        @endphp

                                        @foreach ($course->CourseContentAndDocument as $key => $content)
                                            @php
                                                $documentCompletionInside = \App\Models\TraineeAssignedTrainingDocument::where(
                                                    'user_id',
                                                    Auth::user()->id,
                                                )
                                                    ->where('document_id', $content['id'])
                                                    ->where('status', 1)
                                                    ->first();

                                                if (!$documentCompletionInside) {
                                                    $allContentCompleted = false;
                                                }
                                            @endphp

                                            <div class="mb-3">
                                                <a href="#courseViseView{{ $content['id'] }}"
                                                    class="courseGroup d-flex justify-content-between align-items-center border-bottom pb-3 text-decoration-none"
                                                    data-course-id="{{ $course->id }}"
                                                    data-content-id="{{ $content['id'] }}"
                                                    data-video-duration="{{ $content['length'] }}"
                                                    data-pdf-pages="{{ $content['length'] }}"
                                                    data-doc-type="{{ $content['type'] }}">
                                                    {{-- @dump($content['type'] ) --}}

                                                    <div class="d-flex align-items-center flex-grow-1"
                                                        style="min-width: 0;">
                                                        <div class="me-3">
                                                            @if ($content['type'] === 'video')
                                                                <img src="{{ asset('front/img/videoicon.svg') }}"
                                                                    width="24" height="24" alt="Video">
                                                            @elseif ($content['type'] === 'doc')
                                                                <img src="{{ asset('front/img/pdficon.svg') }}"
                                                                    width="24" height="24" alt="Document">
                                                            @elseif ($content['type'] === 'image')
                                                                <img src="{{ asset('front/img/image-icon.svg') }}"
                                                                    width="24" height="24" alt="Document">
                                                            @endif
                                                        </div>
                                                        <div class="flex-grow-1" style="min-width: 0;">
                                                            <p class="mb-1 fw-semibold text-dark text-truncate">
                                                                {{ $key + 1 }}. {{ $content['title'] }}
                                                            </p>
                                                            <div class="text-muted small">
                                                                @if ($content['type'] === 'video')
                                                                    Video • <small style="font-size: 11px"> Study required:
                                                                        {{ gmdate('i:s', $content['length']) }}</small>
                                                                @elseif ($content['type'] === 'doc')
                                                                    @php
                                                                        $seconds = $content['length'];
                                                                        if ($seconds < 60) {
                                                                            $readingTime =
                                                                                'Study required: ' . $seconds . ' sec';
                                                                        } else {
                                                                            $minutes = floor($seconds / 60);
                                                                            $remainingSeconds = $seconds % 60;
                                                                            $readingTime =
                                                                                'Study required: ' .
                                                                                $minutes .
                                                                                ':' .
                                                                                str_pad(
                                                                                    $remainingSeconds,
                                                                                    2,
                                                                                    '0',
                                                                                    STR_PAD_LEFT,
                                                                                ) .
                                                                                ' min';
                                                                        }
                                                                    @endphp
                                                                    Document • <small style="font-size: 11px">
                                                                        {{ $readingTime }}</small>
                                                                @elseif ($content['type'] === 'image')
                                                                    @php
                                                                        $seconds = $content['length'];
                                                                        if ($seconds < 60) {
                                                                            $viewingTime =
                                                                                'Study required: ' . $seconds . ' sec';
                                                                        } else {
                                                                            $minutes = floor($seconds / 60);
                                                                            $remainingSeconds = $seconds % 60;
                                                                            $viewingTime =
                                                                                'Study required: ' .
                                                                                $minutes .
                                                                                ':' .
                                                                                str_pad(
                                                                                    $remainingSeconds,
                                                                                    2,
                                                                                    '0',
                                                                                    STR_PAD_LEFT,
                                                                                ) .
                                                                                ' min';
                                                                        }
                                                                    @endphp
                                                                    Image • <small style="font-size: 11px">
                                                                        {{ $viewingTime }}</small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex flex-column align-items-end ms-3">
                                                        <div
                                                            class="small {{ $documentCompletionInside ? 'text-success' : 'text-warning' }}">
                                                            {{ $documentCompletionInside ? 'Completed' : 'Not Started' }}
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach

                                        @if ($hasTest)
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

                                            <div class="mt-4 pt-3 border-top">
                                                @if (!empty($testAlreadySubmited) && $testAlreadySubmited->status == 1)
                                                    <button class="btn btn-outline-secondary" data-bs-toggle="modal"
                                                        data-bs-target="#alreadySubmittedTest">
                                                        Begin Test
                                                    </button>
                                                @else
                                                    <a class="btn btn-primary w-100 {{ $allContentCompleted ? '' : 'disabled' }}"
                                                        href="{{ $allContentCompleted ? route('userTraining.test', ['training_id' => $training_id, 'course_id' => $course->id, 'test_id' => $course->test_id]) : 'javascript:void(0)' }}"
                                                        @if (!$allContentCompleted) data-original-href="{{ route('userTraining.test', ['training_id' => $training_id, 'course_id' => $course->id, 'test_id' => $course->test_id]) }}"
                                                        style="{{ !$allContentCompleted ? 'pointer-events: none; opacity: 0.6;' : '' }}" @endif>
                                                        <i class="bi bi-pencil-square me-2"></i>Begin Test
                                                        @if (!$allContentCompleted)
                                                            <small class="d-block mt-1">Complete all content first</small>
                                                        @endif
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                    @else
                                        <p class="text-muted mb-0">No content available.</p>
                                    @endif
                                </div>
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

        function showLockedMessage(button) {
            const $button = $(button);
            // Remove any existing message
            $button.find('.locked-message').remove();
            // Add new message
            $button.append(
                '<span class="ms-2 badge bg-warning text-dark locked-message">Complete previous course first</span>'
            );
            // Remove the message after 3 seconds
            setTimeout(() => {
                $button.find('.locked-message').remove();
            }, 3000);
            return false;
        }

        // This will handle the accordion behavior
        $(document).ready(function() {
            $('#accordionExample').on('show.bs.collapse', function(e) {
                const $header = $(e.target).prev('.accordion-header');
                const $button = $header.find('.accordion-button');

                // Skip check for first course
                if ($header.is('#heading0')) return true;

                const courseId = $button.data('course-id');
                const prevCourseId = $header.parent().prev('.accordion-item').find('.accordion-button')
                    .data('course-id');

                // Check if previous course is completed
                if (!checkIfCourseCompleted(prevCourseId)) {
                    showLockedMessage($button[0]);
                    e.preventDefault(); // Prevent accordion from opening
                    return false;
                }
            });
        });

        function checkAllContentCompleted(courseId) {
            var courseItems = $('a.courseGroup[data-course-id="' + courseId + '"]');
            var allCompleted = true;

            courseItems.each(function() {
                if (!$(this).find('.small').hasClass('text-success')) {
                    allCompleted = false;
                    return false;
                }
            });

            if (allCompleted) {
                const $button = $('.accordion-item').has('[data-course-id="' + courseId + '"]')
                    .find('.accordion-button');

                $button.find('.badge').remove();
                $button.append('<span class="ms-2 badge bg-success">Completed</span>');

                var testButton = $('.accordion-item').has('[data-course-id="' + courseId + '"]')
                    .find('.btn-primary');
                testButton.removeClass('disabled')
                    .css({
                        'pointer-events': 'auto',
                        'opacity': '1'
                    })
                    .attr('href', testButton.data('original-href'))
                    .find('small').remove();

                var nextAccordionItem = $('.accordion-item').has('[data-course-id="' + courseId + '"]').next(
                    '.accordion-item');
                if (nextAccordionItem.length) {
                    const nextButton = nextAccordionItem.find('.accordion-button');
                    nextButton.removeClass('locked-course')
                        .attr('data-bs-toggle', 'collapse')
                        .attr('data-bs-target', '#collapse' + nextAccordionItem.index())
                        .removeAttr('onclick');
                }
            }
        }

        // Helper function to check course completion (mimics PHP function)
        function checkIfCourseCompleted(courseId) {
            var allCompleted = true;
            $('a.courseGroup[data-course-id="' + courseId + '"]').each(function() {
                if (!$(this).find('.small').hasClass('text-success')) {
                    allCompleted = false;
                    return false;
                }
            });
            return allCompleted;
        }



        let globalTracker = {
            intervalId: null,
            viewedTime: 0,
            contentId: null,
            hasUpdated: false,
            type: null
        };

        $('a.courseGroup').click(function(event) {
            event.preventDefault();

            const $this = $(this);
            const contentId = $this.data('content-id');
            const courseId = $this.data('course-id');
            const contentLength = parseFloat($this.data('video-duration')); // used for both doc/video
            const contentType = $this.data('doc-type'); // 'doc' or 'video'
            const href = $this.attr('href');
            const $targetTab = $(href);

            // Reset UI
            $(".tab-content .tab-pane").hide();
            $("a.courseGroup").removeClass("active");
            $this.addClass("active");
            $(".thumb").hide();
            $targetTab.show();

            // Cleanup previous timer
            if (globalTracker.intervalId && globalTracker.contentId !== contentId) {
                clearInterval(globalTracker.intervalId);
                globalTracker.intervalId = null;
            }

            // Reset tracker
            globalTracker = {
                intervalId: null,
                viewedTime: 0,
                contentId,
                hasUpdated: false,
                type: contentType
            };

            // Load stored progress
            $.ajax({
                url: "{{ route('userTrainingDetails.document.duration') }}", 
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    content_id: contentId,
                    course_id: courseId
                },
                success: function(response) {
                    globalTracker.viewedTime = response.duration || 0;

                    if (contentType === 'doc' || contentType === 'image') {
                        handleDocumentTracking();
                    } else if (contentType === 'video') {
                        handleVideoTracking();
                    }
                }
            });

            function handleDocumentTracking() {
                function startTimer() {
                    if (!globalTracker.intervalId && !globalTracker.hasUpdated) {
                        globalTracker.intervalId = setInterval(() => {
                            globalTracker.viewedTime++;

                            if (globalTracker.viewedTime >= contentLength && !globalTracker.hasUpdated) {
                                clearInterval(globalTracker.intervalId);
                                globalTracker.hasUpdated = true;
                                updateProgress();
                            } else {
                                // console.log('time:', globalTracker.viewedTime);
                                updatePartialProgress(globalTracker.viewedTime);
                            }
                        }, 1000);
                    }
                }

                function stopTimer() {
                    clearInterval(globalTracker.intervalId);
                    globalTracker.intervalId = null;
                }

                function checkAndToggleTimer() {
                    const isTabVisible = document.visibilityState === 'visible';
                    const isDocTabActive = $targetTab.is(':visible');
                    if (isTabVisible && isDocTabActive && !globalTracker.hasUpdated) {
                        startTimer();
                    } else {
                        stopTimer();
                    }
                }

                checkAndToggleTimer();
                $(document).off('visibilitychange').on('visibilitychange', checkAndToggleTimer);
            }

            function handleVideoTracking() {
                const video = $targetTab.find('video')[0];

                if (!video) return;

                let lastReportedTime = globalTracker.viewedTime;

                video.currentTime = globalTracker.viewedTime; // Resume from last saved
                video.ontimeupdate = function() {
                    const newTime = Math.floor(video.currentTime);
                    if (newTime > globalTracker.viewedTime) {
                        globalTracker.viewedTime = newTime;

                        if (globalTracker.viewedTime >= contentLength && !globalTracker.hasUpdated) {
                            globalTracker.hasUpdated = true;
                            video.ontimeupdate = null;
                            updateProgress();
                        } else if (globalTracker.viewedTime !== lastReportedTime) {
                            lastReportedTime = globalTracker.viewedTime;
                            updatePartialProgress(globalTracker.viewedTime);
                        }
                    }
                };

                document.addEventListener('visibilitychange', function() {
                    if (document.visibilityState !== 'visible') {
                        video.pause();
                    }
                });
            }

            function updateProgress() {
                $.post("{{ route('userTrainingDetails.document.progress') }}", {
                    _token: '{{ csrf_token() }}',
                    content_id: contentId,
                    course_id: courseId,
                    content_length: contentLength,
                    content_type: contentType
                }, function() {
                    $('a.courseGroup[data-content-id="' + contentId + '"][data-course-id="' + courseId +
                            '"]')
                        .find('.small')
                        .removeClass('text-warning')
                        .addClass('text-success')
                        .text('Completed');
                    checkAllContentCompleted(courseId);
                });
            }

            function updatePartialProgress(duration) {
                $.post("{{ route('userTrainingDetails.document.partial') }}", {
                    _token: '{{ csrf_token() }}',
                    content_id: contentId,
                    course_id: courseId,
                    duration
                });
            }
        });
    </script>
    {{-- <script>
        jQuery("a.courseGroup").click(function(event) {
            event.preventDefault();
            jQuery(".tab-content .tab-pane").hide();
            jQuery('a.courseGroup').removeClass('active');
            jQuery(this).addClass('active');
            jQuery(".thumb").hide();
            var target = jQuery(this).attr('href');
            jQuery(target).show();
        });
    </script> --}}
    <script>
        $(document).ready(function() {
            $('a').on('click', function(e) {
                var href = $(this).attr('href');
                if (href && (href.endsWith('.pdf') || href.endsWith('.doc') || href.endsWith('.xlsx'))) {
                    e.preventDefault();
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
