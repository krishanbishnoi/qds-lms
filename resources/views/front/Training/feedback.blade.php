@extends('front.layouts.trainee-default')
@section('content')


    <body>
        <header class="traineeHead">
            <div class="container-fluid">
                <div class="d-flex flex-wrap align-items-center">
                    <div class="logoSec">
                        <a href="{{ route('front.dashboard') }}"><img src="{{ asset('lms-img/qdegrees-logo.svg') }}"
                                alt="logo" width="130" height="33"></a>
                    </div>
                    <div class="courseName">
                        <p class="mb-0">Training Title</p>
                    </div>
                    <div id="countdown-timer" class="counttimerGroup" style="color: #000">
                        <div class="timerImg"><svg xmlns="http://www.w3.org/2000/svg" width="23.51" height="27.845"
                                viewBox="0 0 23.51 27.845">
                                <g id="_70fade71b5a52d187d0046af3cc3b5d2" data-name="70fade71b5a52d187d0046af3cc3b5d2"
                                    transform="translate(-6.24 -1.46)">
                                    <path id="Path_18436" data-name="Path 18436"
                                        d="M27.056,6.5V4.118h1.368a1.329,1.329,0,0,0,0-2.658H7.567a1.329,1.329,0,0,0,0,2.658H8.911V6.5a13.4,13.4,0,0,0,2.548,7.481,11.611,11.611,0,0,0,1.176,1.4,12.031,12.031,0,0,0-1.176,1.409,13.4,13.4,0,0,0-2.548,7.481v2.375H7.567a1.329,1.329,0,0,0,0,2.658H28.4a1.329,1.329,0,0,0,0-2.658H27.033V24.272a13.4,13.4,0,0,0-2.548-7.481,12.031,12.031,0,0,0-1.153-1.409,11.611,11.611,0,0,0,1.176-1.4A13.4,13.4,0,0,0,27.056,6.5ZM21.6,16.75a11.183,11.183,0,0,1,3.278,7.522v2.375h-13.8V24.272a11.183,11.183,0,0,1,3.25-7.495,1.9,1.9,0,0,0,0-2.735,11.16,11.16,0,0,1-3.26-7.49V4.118h13.8V6.5a11.16,11.16,0,0,1-3.25,7.49A1.9,1.9,0,0,0,21.6,16.75Z"
                                        transform="translate(0 0)" fill="currentColor" />
                                    <path id="Path_18437" data-name="Path 18437"
                                        d="M30.964,33.266H20v-.255c0-3.032,2.439-6.97,5.471-6.97s5.471,3.939,5.471,6.97ZM21.468,15.3h8.005v.155c0,1.846-1.792,4.267-4,4.267s-4-2.4-4-4.244Z"
                                        transform="translate(-7.487 -7.531)" fill="currentColor" />
                                    <circle id="Ellipse_589" data-name="Ellipse 589" cx="0.821" cy="0.821" r="0.821"
                                        transform="translate(17.163 13.017)" fill="currentColor" />
                                    <circle id="Ellipse_590" data-name="Ellipse 590" cx="1.076" cy="1.076" r="1.076"
                                        transform="translate(16.908 15.506)" fill="currentColor" />
                                </g>
                            </svg>
                        </div>
                        <span id="countdown"></span>
                    </div>
                    <div class="courseProgress">
                        {{-- <div class="progress-main">
                            <div class="progress-circle" data-progress="90"><img
                                    src="{{ asset('front/img/processing.svg') }}" width="20" height="20"></div>
                        </div> --}}
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
        <div class="d-flex flex-wrap paddingTop">
            <div class="courseName trainingNameMobile d-lg-none w-100">
                <p class="mb-0">TRAINIGNTITLE</p>
            </div>
            <div class="abouttraining">
                <div class="testGroup">
                    <form action="{{ route('store.feedback') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="testid d-flex justify-content-between">
                            <h3>Feedback</h3>

                            {{-- <span class="questionNum">1<i>/3</i></span> --}}
                            <input type="hidden" name="activity_type_id" value="1">
                            <input type="hidden" name="type" value="2">

                        </div>
                        <div class="testQuestion">
                            <p class="lightGreyTxt" style="font-size: 15px; max-width: 871px;">We are
                                committed to continuous improvement and ensuring the best possible experience for our users.
                                Your feedback is invaluable to us as it helps us understand how we can better serve you and
                                enhance our learning programs. We appreciate you taking the<br> time to share your
                                thoughts with us.</p>

                            <div class="ansCheck pb-3" style="max-width: 862px;">
                                <textarea class="form-control free-text-input" name="feedback" placeholder="Enter Feedback here" required></textarea>
                                {{-- <div class="wordcounter w-100">0/150</div> --}}
                            </div>
                        </div>
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <div class="d-flex mt-2">
                            <button type="submit"
                                class="btn btn-secondary smallBtn  py-2 px-4 view-result-button">Submit</button>
                        </div>
                    </form>
                    <div class="completeQStauts d-flex ">
                        <div class="progress-main ms-auto">
                            {{-- <div class="progress-circle" data-progress="20"></div> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="courseContent" id="courseContent">
                <div class="courselisting">
                    <strong>Test instruction </strong>
                    <ul class="nav nav-tabs" id="nav-tab" role="tablist">
                        <li>
                            <a href="#question1" class="courseGroup active bg-transparent p-2" data-bs-toggle="tab"
                                aria-controls="nav-question1">
                                <p>DESCRIPTION</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="overllayBg" id="overllayBg" style="display: none;"></div>

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
                                    <p class="greentxt fs-5 mb-0 text-start"><b id="percentage"></b><span
                                            class="lightGreyTxt ms-1">out
                                            of 100%</span></p>
                                </div>
                            </div>
                            <hr>
                            <div class="d-sm-flex justify-content-between">
                                <div class=" fs-6 blue-text">Result</div>
                                <div class="text-center">
                                    <p class="greentxt fs-5 mb-0 text-start"><b id="result"></b></p>
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
        {{-- certificate for test model --}}
        <div class="modal fade" id="certificate-modal" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                                    <p class="w-25 fs-12 lightGreyTxt">Lorem ipsum dolor sit amet consectetur
                                        adipisicing
                                        elit. Repellat,
                                        fuga?</p>
                                </div>
                                <p class="fs-12 text-dark fw-medium mb-1">CERTIFICATION OF COMPLETION</p>
                                <h1 class="fs-3 text-dark">Lorem Ipsum is simply dummy text of the printing and
                                    typesetting
                                    industry.</h1>
                                <p class="fs-12 text-dark fw-medium">INSTRUCTOR : Mark Mathew</p>
                                <h2 class="text-dark fs-5 fw-bold mt-md-5 mt-3">Mark Mathews</h2>
                                <p class="text-dark mb-0">Date: <span class="fw-bold"> Mar 3, 2022</span></p>
                                <p class="text-dark">Length:<span class="fw-bold"> 3 Days</span></p>
                            </div>
                        </div>
                        <p class="text-dark fs-12 pt-2">This certificate above verifies that <span
                                class="blue-text fw-bold">Vaibhav Saini</span> successfully completed the
                            course<span class="blue-text fw-bold"> User
                                Experience Design Essentials - Adobe XD UI UX Design</span> on 03/03/2022 as taught
                            by <span class="blue-text fw-bold"> Mark Mathew </span>on
                            Udemy. The certificate indicates the entire course was completed as validated by the
                            student.
                            The course duration represents the total video hours of the course at time of most
                            recent
                            completion.</p>
                    </div>
                    <div class="modal-footer border-0">
                        <a href=""><button type="button" class="btn btn-secondary fs-7"
                                data-bs-dismiss="modal">Back to
                                Home</button></a>
                        <button type="button" class="btn btn-secondary fs-7">Download</button>
                    </div>
                </div>
            </div>
        </div>

    @stop
