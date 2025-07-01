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
                    $progressPercentage = ($completedCoursesCount / $totalCoursesCount) * 100;
                    $roundedProgressPercentage = round($progressPercentage, 2);
                    @endphp
                    <div class="progress-circle" data-progress="{{ $roundedProgressPercentage }}"><img
                            src="{{ asset('front/img/processing.svg') }}" width="20" height="20"></div>
                </div>
                <div class="progressText">
                    <strong>Progress</strong>
                    <span>{{ $completedCoursesCount }}<i>/{{ $totalCoursesCount }}</i></span>
                </div>
                <button type="button" class="optionBtn d-none"><img src="{{ asset('front/img/option.svg') }}"
                        alt="icon" width="23" height="23"></button>
                <a href="{{ route('front.dashboard') }}"><button type="button" class="exitBtn"><img
                            src="{{ asset('front/img/exit.svg') }}" alt="icon" width="23"
                            height="23"></button></a>
            </div>
        </div>
    </div>
</header>
<div class="d-flex flex-wrap paddingTop">
    <div class="abouttraining">
        <div class="imgwrapper thumb m-3">
            <figure><img src="{{ TRAINING_DOCUMENT_URL . $trainingDetails->thumbnail }}" alt="">
                <a href="" class="backBtn d-md-none">
                    <img src="{{ asset('front/img/back-button.png') }}" alt="" width="50">
                </a>
            </figure>
        </div>
        <div class="courseName trainingNameMobile d-lg-none pb-md-0 pb-0 w-100">
            <p class="mb-0">{{ $trainingDetails->title }}</p>
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
            </div>
            <div class="timeSection timeSec">
                <div class="timeDescription">
                    <i class="d-none d-md-block"><img src="../front/img/timer.svg" alt="img"></i>
                    <p><span class="d-none d-md-block">Time Left</span><span id="countdown" class="ml-5 d-none d-md-block"></span>Embrace the urgency: Time Remaining
                        for Training.
                        Every second counts as you embark on your training journey. The time left is a precious resource
                        that demands your focus, determination, and dedication. With each passing moment, opportunities for
                        growth and improvement await.</p>
                </div>
                <div class="timerGroup">
                </div>
            </div>
            <div class="about_content d-none d-md-block">
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

            <div class="traningTypes d-md-none">
                <span class="mb-3 d-block">Training Type <b>Skill Development Training</b></span>
                <div class="d-flex justify-content-between">
                    <span>Total Time To Finish <b>23474h 17m</b></span>
                    <span>Total Content <b>10</b></span>
                </div>

                <ul class="nav nav-tabs tabsMain mb-4" id="myTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#modulesTab" type="button">Modules</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#detailsTab" type="button">Detail</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="modulesTab">
                        <ul class="courselistUl">
                            <li>
                                <a href="">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-2">
                                            <strong>01</strong>
                                            <span>Module 1 <b>Introduction to Mystery Shopping</b></span>
                                        </div>
                                        <figure class="m-0">
                                            <img src="{{ asset('front/img/play-btn-icon.png') }}" alt="">
                                        </figure>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-2">
                                            <strong>02</strong>
                                            <span>Module 2 <b> Responsibilities of Mystery Shopper</b></span>
                                        </div>
                                        <figure class="m-0">
                                            <img src="{{ asset('front/img/play-btn-icon.png') }}" alt="">
                                        </figure>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-2">
                                            <strong>03</strong>
                                            <span>Module 3 <b>Market Research in Mystery Shopping</b></span>
                                        </div>
                                        <figure class="m-0">
                                            <img src="{{ asset('front/img/play-btn-icon.png') }}" alt="">
                                        </figure>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-2">
                                            <strong>04</strong>
                                            <span>Module 4 <b>Preparing Your Mystery Shopping Career</b></span>
                                        </div>
                                        <figure class="m-0">
                                            <img src="{{ asset('front/img/play-btn-icon.png') }}" alt="">
                                        </figure>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-2">
                                            <strong>05</strong>
                                            <span>Module 5 <b>Types of Assignments in Mystery Shopping</b></span>
                                        </div>
                                        <figure class="m-0">
                                            <img src="{{ asset('front/img/play-btn-icon.png') }}" alt="">
                                        </figure>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-2">
                                            <strong>06</strong>
                                            <span>Module 6 <b>Mystery Shopping Process</b></span>
                                        </div>
                                        <figure class="m-0">
                                            <img src="{{ asset('front/img/play-btn-icon.png') }}" alt="">
                                        </figure>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-2">
                                            <strong>07</strong>
                                            <span>Module 7 <b>Report Writing for Mystery Shoppers</b></span>
                                        </div>
                                        <figure class="m-0">
                                            <img src="{{ asset('front/img/play-btn-icon.png') }}" alt="">
                                        </figure>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-2">
                                            <strong>08</strong>
                                            <span>Module 8 <b>Enhancing Mystery Shopping Skills</b></span>
                                        </div>
                                        <figure class="m-0">
                                            <img src="{{ asset('front/img/play-btn-icon.png') }}" alt="">
                                        </figure>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-2">
                                            <strong>09</strong>
                                            <span>Module 9 <b>Business Side of Mystery Shopping</b></span>
                                        </div>
                                        <figure class="m-0">
                                            <img src="{{ asset('front/img/play-btn-icon.png') }}" alt="">
                                        </figure>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-2">
                                            <strong>10</strong>
                                            <span>Module 10 <b>Next steps in Mystery Shopping Career</b></span>
                                        </div>
                                        <figure class="m-0">
                                            <img src="{{ asset('front/img/play-btn-icon.png') }}" alt="">
                                        </figure>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="detailsTab">
                        <div class="detailMain">
                            <b>Key Performance Metrics:</b>
                            <ul class="detailUl ps-3 pb-3">
                                <li>Define Mystery Shopping and its evolution.</li>
                                <li>Explore Mystery Shopper roles and ethics.</li>
                                <li>Apply Market Research in Mystery Shopping.</li>
                                <li>Prepare for a Mystery Shopping Career responssibly.</li>
                            </ul>
                            <b>KeyCertificate</b>
                            <p style="margin-bottom: 70px;">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                        </div>
                    </div>
                </div>

            </div>
            <div class="footerBottom">
                <a href="" class="d-md-none submitBtn">Start</a>
            </div>
        </div>
        <div class="modulesTypes d-md-none">
            <a href="" class="moduleBck d-md-none mb-3 d-block">
                <img src="{{ asset('front/img/back-button.png') }}" alt="" width="50" class="me-2"> Module 1
            </a>
            <a href="#videoMdl" data-bs-toggle="modal">videoModal</a>
            <a href="#holdMdl" data-bs-toggle="modal">Hold On</a>
            <a href="#greatMdl" data-bs-toggle="modal">Great Job</a>
            <a href="#submissionMdl" data-bs-toggle="modal">submission</a>
            <a href="#congratsMdl" data-bs-toggle="modal">Congrates</a>
            <div class="chepterWise mb-3">
                <video controls>
                    <source src="{{ asset('front/img/video-one.mp4') }}" type="video/mp4">
                </video>
                <div class="d-flex justify-content-between align-items-center px-3 py-2 pb-3">
                    <b>Chapter- 1</b>
                    <div class="d-flex align-items-center gap-2 ">
                        <a href=""> <img src="{{ asset('front/img/prew-icon.svg') }}" alt="" width="35"></a>
                        <a href=""> <img src="{{ asset('front/img/next-icon.svg') }}" alt="" width="35"></a>
                    </div>
                </div>
            </div>
            <b class="mb-3 d-block fs-6 text-black">Mystery Shopper Masterclass</b>
            <p class="text-gray">Embrace the urgency: Time Remaining
                for Training.
                Every second counts as you embark on your training journey. The time left is a precious resource
                that demands your focus, determination, and dedication. With each passing moment, opportunities for
                growth and improvement await.</p>
            <b class="mb-3 d-block fs-6 text-black">Lessons</b>
            <ul class="courselistUl">
                <li>
                    <a href="">
                        <div class="d-flex justify-content-between align-items-center gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <strong>01</strong>
                                <div class="">
                                    <span class="mb-1 d-block"><b>Introduction to Mystery Shopping</b></span>
                                    <span class="text-gray d-flex align-items-center gap-2"> <img src="{{ asset('front/img/video-icon.svg') }}" alt="" class="me-1"> Video</span>
                                </div>
                            </div>
                            <figure class="m-0">
                                <img src="{{ asset('front/img/play-btn-icon.png') }}" alt="">
                            </figure>
                        </div>
                        <div class="barProgress">
                            <div class="progress mb-2" role="progressbar" aria-label="Basic example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar w-75"></div>
                            </div>
                            <strong class="text-blue mt-2">90% completed</strong>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="">
                        <div class="d-flex justify-content-between align-items-center gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <strong>02</strong>
                                <div class="">
                                    <span class="mb-1 d-block"><b>PPT- Introduction of Mystery...</b></span>
                                    <span class="text-gray d-flex align-items-center gap-2"> <img src="{{ asset('front/img/docs-icon.svg') }}" alt="" class="me-1"> DOC</span>
                                </div>
                            </div>
                            <figure class="m-0">
                                <img src="{{ asset('front/img/play-btn-icon.png') }}" alt="">
                            </figure>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="">
                        <div class="d-flex justify-content-between align-items-center gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <strong>03</strong>
                                <div class="">
                                    <span class="mb-1 d-block"><b>Introduction to Mystery Shopping</b></span>
                                    <span class="text-gray d-flex align-items-center gap-2"> <img src="{{ asset('front/img/docs-icon.svg') }}" alt="" class="me-1"> DOC</span>
                                </div>
                            </div>
                            <figure class="m-0">
                                <img src="{{ asset('front/img/play-btn-icon.png') }}" alt="">
                            </figure>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="">
                        <div class="d-flex justify-content-between align-items-center gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <strong>04</strong>
                                <div class="">
                                    <span class="mb-1 d-block"><b>IPPT- Introduction of Mystery...</b></span>
                                    <span class="text-gray d-flex align-items-center gap-2"> <img src="{{ asset('front/img/docs-icon.svg') }}" alt="" class="me-1"> DOC</span>
                                </div>
                            </div>
                            <figure class="m-0">
                                <img src="{{ asset('front/img/play-btn-icon.png') }}" alt="">
                            </figure>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="">
                        <div class="d-flex justify-content-between align-items-center gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <strong>05</strong>
                                <div class="">
                                    <span class="mb-1 d-block"><b>Introduction to Mystery Shopping</b></span>
                                    <span class="text-gray d-flex align-items-center gap-2"> <img src="{{ asset('front/img/video-icon.svg') }}" alt="" class="me-1"> Video</span>
                                </div>
                            </div>
                            <figure class="m-0">
                                <img src="{{ asset('front/img/play-btn-icon.png') }}" alt="">
                            </figure>
                        </div>
                    </a>
                </li>
            </ul>
        </div>

        <div class="textIntruction d-md-none pb-5">
            <a href="" class="moduleBck d-md-none mb-3 d-block">
                <img src="https://demolms.qdegrees.com/front/img/back-button.png" alt="" width="50" class="me-2">
            </a>
            <div class="headingInt">
                <span class="d-block">Test Instructions</span>
                <p>Please read the instructions carefully before starting the test:</p>
            </div>
            <ul class="textType">
                <li>
                    <b>1. Test Duration:</b>
                    <p>5 minutes</p>
                </li>
                <li>
                    <b>2. Total Questions:</b>
                    <p>5 </p>
                </li>
                <li>
                    <b>3. Passing Score:</b>
                    <p>50% </p>
                </li>
                <li>
                    <b>4. Question Types:</b>
                    <p>The test consists of MCQS (Multiple Choice Questions), SCQS (Single Correct Questions), and True/False type questions </p>
                </li>
                <li>
                    <b>5. All Questions Are Mandatory:</b>
                    <p>You must answer every question before submitting. Unanswered questions will prevent final submission.</p>
                </li>
                <li>
                    <b>6. No Negative Marking:</b>
                    <p>There is no penalty for incorrect answers, so attempt all questions confidently.</p>
                </li>
                <li>
                    <b>7. Stable Internet Required:</b>
                    <p>Any network disconnection may automatically submit your test and log the activity.</p>
                </li>
                <li>
                    <b>8. Stay on Test Page:</b>
                    <p>Switching to another tab or minimizing the browser will trigger warnings. Multiple violations will auto-submit your test.</p>
                </li>
                <li>
                    <b>9. Do Not Refresh:</b>
                    <p>Reloading, pressing F5, or clicking the back button will end your test immediately.</p>
                </li>
                <li>
                    <b>10. Webcam Must Stay On:</b>
                    <p>Your webcam must detect your face during the entire test.</p>
                </li>
                <li>
                    <b>11. Mic Access May Be Monitored:</b>
                    <p>Your microphone may be used to monitor ambient noise levels to detect suspicious behavior.</p>
                </li>
                <li>
                    <b>12. No Mobile Devices:</b>
                    <p>Using your phone, smartwatches, or other digital devices is strictly prohibited during the test.</p>
                </li>
                <li>
                    <b>13. No External Help:</b>
                    <p>This is an individual assessment. Collaboration or help from others will result in disqualification.</p>
                </li>
                <li>
                    <b>14. Al Surveillance Active:</b>
                    <p>Face, tab, and activity monitoring tools are in use to ensure test integrity. Every action is logged.</p>
                </li>
                <li>
                    <b>15. Copy/Paste Disabled:</b>
                    <p>Right-click, inspect element, or using keyboard shortcuts like Ctrl+C/Ctrl+V is disabled and logged.</p>
                </li>
                <li>
                    <b>16. System Focus Monitoring:</b>
                    <p>Unusual mouse movements or inactivity may be flagged as suspicious.</p>
                </li>
                <li>
                    <b>17. Time-Managed Questions:</b>
                    <p>Allocate your time wisely. Some questions may be time-bound within the test.</p>
                </li>
                <li>
                    <b>18. Zero Tolerance Policy:</b>
                    <p>Any attempt to bypass restrictions will result in immediate test submission and logging of the attempt.</p>
                </li>
                <li class="border-0">
                    <b>19. Result Review:</b>
                    <p>Results will be reviewed before finalization. Suspicious attempts may be invalidated.</p>
                </li>
                <ul class="tittleUl">
                    <li>
                        <b>Test Title:</b>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,</p>
                    </li>
                    <li>
                        <b>Test Description:</b>
                        <p>It is a long established fact that a reader will be distracted by the readable content of a page.</p>
                    </li>
                </ul>
                <div class="form-check my-4">
                    <input class="form-check-input" type="checkbox" value="" id="checkDefault">
                    <label class="form-check-label" for="checkDefault">
                        I've Read and Start test
                    </label>
                </div>
            </ul>
        </div>

        <div class="questionsScrn d-md-none pb-5 w-100">
            <a href="" class="moduleBck d-md-none mb-3 d-block">
                <img src="https://demolms.qdegrees.com/front/img/back-button.png" alt="" width="50" class="me-2">
            </a>
            <div class="headingInt">
                <span class="d-block fw-medium">Question 2/5</span>
                <div class="barProgress mx-0">
                    <div class="progress mb-2 bg-white" role="progressbar" aria-label="Basic example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar w-75"></div>
                    </div>
                </div>
            </div>
            <div class="questionInner">
                <b>Question: 01</b>
                <p class="mb-4">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                <ul class="qualityCheckList p-0">
                    <li><input type="radio" id="radio1" name="radioDefault"><label for="radio1">Simply Dummy Text here 1</label></li>
                    <li><input type="radio" id="radio2" name="radioDefault"><label for="radio2">Simply Dummy Text here 1</label></li>
                    <li><input type="radio" id="radio3" name="radioDefault"><label for="radio3">Simply Dummy Text here 1</label></li>
                    <li><input type="radio" id="radio4" name="radioDefault"><label for="radio4">Simply Dummy Text here 1</label></li>
                </ul>
                <button type="button" class="btn btn-primary w-100 mt-4">Submit & Next</button>
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
    <div class="modal fade" id="testresult" tabindex="-1" aria-hidden="true">
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


    <!--  -->
    <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Modal 1</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Show a second modal and hide this one with the button below.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal">Open second modal</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="videoMdl">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-header bg-transparent px-2 py-3">
                    <!-- <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Modal 2</h1> -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <video width="100%" height="220" style="object-fit: cover;" controls>
                        <source src="{{ asset('front/img/video-one.mp4') }}" type="video/mp4">
                    </video>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="holdMdl">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header">
                    <!-- <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Modal 2</h1> -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center mdlContent">
                    <div class="mb-4">
                        <lottie-player src="{{ asset('front/img/pending-new.json') }}" style="width: 100%;height:200px;margin: auto;" loop autoplay></lottie-player>
                    </div>
                    <span class="d-block mb-3">Hold on!</span>
                    <p class="px-3">Module 2 is locked until you complete the Module 1 test. Let’s finish that first!</p>
                    <button type="button" class="btn btn-primary quizBtn w-100">Start Quiz</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="greatMdl">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header">
                    <!-- <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Modal 2</h1> -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center mdlContent">
                    <div class="mb-4">
                        <lottie-player src="{{ asset('front/img/task-complete.json') }}" style="width: 100%;height:200px;margin: auto;" loop autoplay></lottie-player>
                    </div>
                    <span class="d-block mb-3">Great Job! Module 1 is done.</span>
                    <p class="px-3">Let’s see how much you remember— take a quick test to move ahead!</p>
                    <button type="button" class="btn btn-primary quizBtn w-100">Start Quiz</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="submissionMdl">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header border-0">
                    <!-- <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Modal 2</h1> -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center mdlContent">
                    <div class="mb-4">
                        <lottie-player src="{{ asset('front/img/online-exam.json') }}" style="width: 100%;height:200px;margin: auto;" loop autoplay></lottie-player>
                    </div>
                    <span class="d-block mb-3">Confirm Submission</span>
                    <p class="px-3">Are you sure you want to submit your test? Once submitted, you cannot change your answers.</p>
                    <div class="modalSpan text-start mb-4">
                        <strong class="mb-3">Question Answered: <b>2/2</b></strong>
                        <strong>Time Remaining : <b>9m 32s</b></strong>
                    </div>
                    <div class="d-flex align-items-center justify-content-center gap-2">
                        <button type="button" class="btn btn-secondary">Previous</button>
                        <button type="button" class="btn btn-primary quizBtn">Confirm Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="congratsMdl">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header border-0">
                    <!-- <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Modal 2</h1> -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body mdlContent">

                    <span class="d-block mb-3">Congrats!! you have scored <br> <strong> 80%</strong> marks</span>
                    <div class="scroed">
                        <b class="border-bottom">Scored <span><strong>100.00</strong> % out of 100%</span></b>
                        <b>Result <span><strong>Passed</strong></span></b>
                    </div>
                    <div class="d-flex align-items-center justify-content-center gap-4">
                        <button type="button" class="btn btn-secondary px-5">View</button>
                        <button type="button" class="btn btn-primary quizBtn">Next Course</button>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="overllayBg" id="overllayBg" style="display: none;"></div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

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