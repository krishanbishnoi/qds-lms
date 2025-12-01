<div class="mobileScren">
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
                <p class="mb-0 text-center">{{ $trainingDetails->title }}</p>
            </div>
            <div class="traningTypes d-md-none">
                <span class="mb-3 d-block">Training Type <b>{{ $trainingDetails->type }}</b></span>
                <div class="d-flex justify-content-between">
                    {{-- <span>Total Time To Finish <b>{{ $hours . 'h ' . $minutes . 'm ' }}</b></span> --}}
                    <span>Total Content <b>{{ count($trainingCourses) }}</b></span>
                </div>

                <ul class="nav nav-tabs tabsMain mb-4" id="myTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#modulesTab"
                            type="button">Modules</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#detailsTab"
                            type="button">Detail</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="modulesTab">
                        <ul class="courselistUl">
                            @foreach ($trainingCourses as $index => $course)
                                <li>
                                    <a href="javascript:void(0)" class="load-course-module"
                                        data-course-id="{{ $course->id }}" data-test-id="{{ $course->test_id ?? '' }}"
                                        data-course-index="{{ $index }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center gap-2">
                                                <strong>{{ $index + 1 }}</strong>
                                                <span>Module {{ $index + 1 }} <b>{{ $course['title'] }}</b></span>
                                            </div>
                                            <figure class="m-0">
                                                <img src="{{ asset('front/img/play-btn-icon.png') }}" alt="">
                                            </figure>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="detailsTab">
                        <div class="detailMain">
                            {!! $trainingDetails->description !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Module Content Section (initially hidden) -->

        <div class="modulesTypes d-md-none" style="display: none;">
            <div class="chepterWise mb-3" id="content-viewer">
            </div>

            <a href="javascript:void(0)" class="moduleBck d-md-none mb-3 d-block back-to-modules">
                <img src="{{ asset('front/img/back-button.png') }}" onclick="location.reload();"
                    style="cursor: pointer;" alt="" width="50" class="me-2">
                <span class="module-title">Back to Modules</span>
            </a>

            <div class="module-content-container">
                <!-- Content list remains here -->
            </div>
        </div>

        <!-- Modals -->
        <div class="modal fade" id="videoMdl">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-transparent border-0">
                    <div class="modal-header bg-transparent px-2 py-3">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <video width="100%" height="220" style="object-fit: cover;" controls>
                            <source src="" class="video-source" type="video/mp4">
                        </video>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="holdMdl">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center mdlContent">
                        <div class="mb-4">
                            <lottie-player src="{{ asset('front/img/pending-new.json') }}"
                                style="width: 100%;height:200px;margin: auto;" loop autoplay></lottie-player>
                        </div>
                        <span class="d-block mb-3">Hold on!</span>
                        <p class="px-3 module-lock-message">Module is locked until you complete the previous module.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="greatMdl">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center mdlContent">
                        <div class="mb-4">
                            <lottie-player src="{{ asset('front/img/task-complete.json') }}"
                                style="width: 100%;height:200px;margin: auto;" loop autoplay></lottie-player>
                        </div>
                        <span class="d-block mb-3">Great Job! Module is done.</span>
                        <p class="px-3">Let's see how much you remember— take a quick test to move ahead!</p>
                        <button type="button" class="btn btn-primary quizBtn w-100 start-test-btn">Start
                            Quiz</button>
                    </div>
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
                    <a href=""><button type="button" class="btn btn-secondary fs-7"
                            data-bs-dismiss="modal">Back
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


    <div class="modal fade" id="docMdl">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 text-center" style="max-height: 80vh; overflow: auto;">
                    <img src="" class="doc-image img-fluid d-none mx-auto"
                        style="max-width: 100%; height: auto;" />
                    <iframe src="" class="doc-frame d-none" width="100%" height="600px"
                        frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>



    <div class="overllayBg" id="overllayBg" style="display: none;"></div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.9.179/pdf.min.js"></script>

<script>
    $(document).ready(function() {
        let globalTracker = {
            intervalId: null,
            viewedTime: 0,
            contentId: null,
            hasUpdated: false,
            type: null,
            isPlaying: false,
            lastUpdateTime: 0
        };
        let currentCourseContent = [];
        let currentContentIndex = 0;

        // Handle module click
        $('.load-course-module').click(function() {
            const courseId = $(this).data('course-id');
            const canAccess = String($(this).data('can-access')) === 'true';
            const isCompleted = String($(this).data('is-completed')) === 'true';
            const isFirst = String($(this).data('is-first')) === 'true';
            const testId = $(this).data('test-id');

            // Load module content
            loadModuleContent(courseId);
        });

        // Back to modules list
        $('.back-to-modules').click(function() {
            stopTracking();
            $('.traningTypes').show();
            $('.modulesTypes').hide();
        });

        function onSurveySubmit() {
            console.log("Button clicked");

            if (window.Android && Android.closeActivity) {
                Android.closeActivity();
            } else {
                // alert("Thank you for finishing the training!");
                // Optionally, redirect or close tab
                console.log("Thank you for finishing the training!");
                // window.close();
            }

            return true;
        }

        function loadModuleContent(courseId) {
            $.ajax({
                url: "{{ route('userTraining.getCourseContentForMobile') }}",
                type: "GET",
                data: {
                    course_id: courseId,
                    training_id: "{{ $training_id }}"
                },
                success: function(response) {
                    if (response.success) {
                        currentCourseContent = response.content;
                        $('.imgwrapper.thumb.m-3').hide();
                        $('.navdiv.d-md-none').show();

                        $('.modulesTypes .module-title').text(response.course.title);

                        let contentHtml =
                            '<b class="mb-3 d-block fs-6 text-black">Lessons</b><ul class="courselistUl">';

                        response.content.forEach((item, index) => {
                            const isCompleted = item.is_completed;
                            let icon = '';
                            let typeText = '';
                            let timeText = formatTime(item.length);

                            if (item.type === 'video') {
                                icon = "{{ asset('front/img/video-icon.svg') }}";
                                typeText = 'Video';
                            } else if (item.document_type === 'pdf') {
                                icon = "{{ asset('front/img/docs-icon.svg') }}";
                                typeText = 'pdf';
                            } else if (item.type === 'doc') {
                                icon = "{{ asset('front/img/docs-icon.svg') }}";
                                typeText = 'DOC';
                            } else if (item.type === 'image') {
                                icon = "{{ asset('front/img/docs-icon.svg') }}";
                                typeText = 'Image';
                            }

                            contentHtml += `
                            <li>
                                <a href="javascript:void(0)" class="load-content" 
                                   data-content-id="${item.id}"
                                   data-course-id="${response.course.id}"
                                   data-content-type="${item.type}"
                                   data-video-duration="${item.length}"
                                   data-doc-type="${item.type}"
                                   data-content-src="${item.document}"
                                   data-index="${index}">
                                    <div class="d-flex justify-content-between align-items-center gap-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <strong>${index + 1}</strong>
                                            <div class="">
                                                <span class="mb-1 d-block"><b>${item.title}</b></span>
                                                <span class="text-gray d-flex align-items-center gap-2">
                                                    <img src="${icon}" alt="" class="me-1">
                                                    ${typeText} • ${timeText}
                                                </span>
                                            </div>
                                        </div>
                                        <figure class="m-0">
                                            <img src="{{ asset('front/img/play-btn-icon.png') }}" alt="">
                                        </figure>
                                    </div>
                                </a>
                            </li>`;
                        });

                        contentHtml += '</ul>';

                        const allContentCompleted = response.content.every(item => item
                            .is_completed);

                        if (response.course.test_id) {

                            contentHtml += `
                                    <div class="mt-4 pt-3 border-top">
                                        <a href="{{ route('userTraining.test', ['training_id' => $training_id, 'course_id' => '__CID__', 'test_id' => '__TID__']) }}"
                                            class="btn btn-primary w-100 start-test-btn"
                                            data-course-id="${response.course.id}"
                                            data-test-id="${response.course.test_id}">
                                            <i class="bi bi-pencil-square me-2"></i>Begin Test
                                        </a>
                                    </div>
                                    `.replace('__CID__', response.course.id).replace('__TID__', response.course
                                .test_id);
                        } else {
                            console.log(response.isLastCourse)
                            if (response.isLastCourse == true) {
                                // Show Finish Training button if last course
                                contentHtml += `
                        <div class="mt-4 pt-3 border-top">
                            <a href="{{ route('userTrainingDetails.index', ['id' => $training_id]) . '?user_id=' . Auth::id() }}"
                                class="btn btn-secondary w-100 finish-trianing-btn ${allContentCompleted ? '' : 'disabled'}"
                                ${allContentCompleted ? '' : 'style="pointer-events: none; opacity: 0.6;"'}>
                                <i class="bi bi-arrow-right me-2" onclick="onSurveySubmit()"></i>Finish Training
                            </a>
                        </div>
                    `;

                            } else {
                                // Add Next Course button if no test exists
                                contentHtml += `
                        <div class="mt-4 pt-3 border-top">
                            <a href="{{ route('userTrainingDetails.index', ['id' => $training_id]) . '?user_id=' . Auth::id() }}"
                                class="btn btn-secondary w-100 next-course-btn ${allContentCompleted ? '' : 'disabled'}"
                                ${allContentCompleted ? '' : 'style="pointer-events: none; opacity: 0.6;"'}>
                                <i class="bi bi-arrow-right me-2"></i>Next Course
                            </a>
                        </div>
                    `;
                            }
                        }

                        $('.module-content-container').html(contentHtml);
                        $('.traningTypes').hide();
                        $('.modulesTypes').show();
                    }
                },
                error: function(xhr) {
                    console.error(xhr);
                    alert('Failed to load module content. Please try again.');
                }
            });
        }

        // Content click handler
        $(document).on('click', '.load-content', function() {
            const contentType = $(this).data('content-type');
            const contentSrc = $(this).data('content-src');
            const contentId = $(this).data('content-id');
            const courseId = $(this).data('course-id');
            const contentLength = $(this).data('video-duration');
            currentContentIndex = $(this).data('index');

            displayContentInViewer(contentType, contentSrc, contentId, courseId, contentLength);
        });

        function displayContentInViewer(contentType, contentSrc, contentId, courseId, contentLength) {
            const content = currentCourseContent[currentContentIndex];
            const fullPath = "{{ url('training_document') }}/" + contentSrc;

            // Clear previous content and stop any tracking
            $('#content-viewer').empty();
            stopTracking();

            if (contentType === 'video') {
                const contentHtml = `
                <video controls class="w-100" id="training-video">
                    <source src="${fullPath}" type="video/mp4">
                </video>
                <div class="d-flex justify-content-between align-items-center px-3 py-2 pb-3">
                    <b>${content.title}</b>
                    <div class="d-flex align-items-center gap-2">
                        <a href="javascript:void(0)" class="prev-content ${currentContentIndex === 0 ? 'disabled' : ''}">
                            <img src="{{ asset('front/img/prew-icon.svg') }}" alt="Previous" width="35">
                        </a>
                        <a href="javascript:void(0)" class="next-content ${currentContentIndex === currentCourseContent.length - 1 ? 'disabled' : ''}">
                            <img src="{{ asset('front/img/next-icon.svg') }}" alt="Next" width="35">
                        </a>
                    </div>
                </div>
                  `;

                $('#content-viewer').html(contentHtml);

                // Get stored progress for this video
                $.ajax({
                    url: "{{ route('tc.userTrainingDetails.document.duration') }}",
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        content_id: contentId,
                        course_id: courseId
                    },
                    success: function(response) {
                        const video = $('#training-video')[0];
                        if (video) {
                            // Initialize tracker
                            globalTracker = {
                                intervalId: null,
                                viewedTime: response.duration || 0,
                                contentId: contentId,
                                hasUpdated: response.duration >= contentLength,
                                type: contentType,
                                isPlaying: false,
                                lastUpdateTime: response.duration || 0
                            };

                            video.currentTime = globalTracker.viewedTime;

                            // Event handlers
                            video.onplay = function() {
                                globalTracker.isPlaying = true;
                                startTracking(contentId, courseId);
                            };

                            video.onpause = function() {
                                globalTracker.isPlaying = false;
                                stopTracking();
                                updatePartialProgress(contentId, courseId, Math.floor(video
                                    .currentTime));
                            };

                            video.onseeked = function() {
                                globalTracker.viewedTime = Math.floor(video.currentTime);
                                updatePartialProgress(contentId, courseId, globalTracker
                                    .viewedTime);
                            };

                            video.ontimeupdate = function() {
                                if (globalTracker.isPlaying) {
                                    globalTracker.viewedTime = Math.floor(video
                                        .currentTime);
                                    if (globalTracker.viewedTime >= contentLength && !
                                        globalTracker.hasUpdated) {
                                        globalTracker.hasUpdated = true;
                                        updateProgress(contentId, courseId, contentLength,
                                            contentType);
                                    }
                                }
                            };

                            video.onended = function() {
                                globalTracker.isPlaying = false;
                                globalTracker.hasUpdated = true;
                                stopTracking();
                                updateProgress(contentId, courseId, contentLength,
                                    contentType);
                            };
                        }
                    }
                });
            } else if (contentType === 'image') {
                const contentHtml = `
                <div class="image-viewer-container">
                    <img src="${fullPath}" class="img-fluid w-100" style="max-height: 60vh; object-fit: contain;">
                    <div class="d-flex justify-content-between align-items-center px-3 py-2 pb-3">
                        <b>${content.title}</b>
                        <div class="d-flex align-items-center gap-2">
                            <a href="javascript:void(0)" class="prev-content ${currentContentIndex === 0 ? 'disabled' : ''}">
                                <img src="{{ asset('front/img/prew-icon.svg') }}" alt="Previous" width="35">
                            </a>
                            <a href="javascript:void(0)" class="next-content ${currentContentIndex === currentCourseContent.length - 1 ? 'disabled' : ''}">
                                <img src="{{ asset('front/img/next-icon.svg') }}" alt="Next" width="35">
                            </a>
                        </div>
                    </div>
                </div>
            `;

                $('#content-viewer').html(contentHtml);

                // Initialize tracker
                globalTracker = {
                    intervalId: null,
                    viewedTime: 0,
                    contentId: contentId,
                    hasUpdated: false,
                    type: contentType,
                    isPlaying: true, // Images are always "playing"
                    lastUpdateTime: 0,
                    contentLength: contentLength
                };

                // Load existing progress
                $.ajax({
                    url: "{{ route('userTrainingDetails.document.duration') }}",
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        content_id: contentId,
                        course_id: courseId
                    },
                    success: function(response) {
                        globalTracker.viewedTime = response.duration || 0;
                        globalTracker.lastUpdateTime = response.duration || 0;
                        globalTracker.hasUpdated = (response.duration >= contentLength);

                        // Start tracking
                        startImageTracking();
                    }
                });

                function startImageTracking() {
                    // Clear existing interval if any
                    if (globalTracker.intervalId) {
                        clearInterval(globalTracker.intervalId);
                    }

                    // Start new tracking interval
                    globalTracker.intervalId = setInterval(function() {
                        globalTracker.viewedTime++;

                        // Update partial progress every 5 seconds
                        if (Math.abs(globalTracker.viewedTime - globalTracker.lastUpdateTime) >=
                            5) {
                            updatePartialProgress(contentId, courseId, globalTracker.viewedTime);
                            globalTracker.lastUpdateTime = globalTracker.viewedTime;
                        }

                        // Check completion
                        if (globalTracker.viewedTime >= contentLength && !globalTracker
                            .hasUpdated) {
                            globalTracker.hasUpdated = true;
                            updateProgress(contentId, courseId, contentLength, contentType);
                            stopTracking();
                        }
                    }, 1000);
                }

                // Stop tracking when navigating away
                $('.prev-content, .next-content').off('click').on('click', function() {
                    stopTracking();

                    // Send final update if needed
                    if (globalTracker.viewedTime > globalTracker.lastUpdateTime) {
                        if (globalTracker.viewedTime >= contentLength) {
                            updateProgress(contentId, courseId, contentLength, contentType);
                        } else {
                            updatePartialProgress(contentId, courseId, globalTracker.viewedTime);
                        }
                    }
                });
            } else if (contentType === 'doc') {
                const pdfUrl = `{{ asset('training_document') }}/${contentSrc}`;

                const contentHtml = `
                    <div class="pdf-viewer-container" style="border:1px solid #ccc; width:100%;">
                       
                        <canvas id="pdf-canvas" style="border:1px solid #ccc; width:100%;"></canvas>
                         <div class="d-flex justify-content-between align-items-center mb-2 gap-2 p-2">
                            <!-- Previous Page Icon -->
                            <button id="prevPage" class="btn btn-lightPDF btn-sm">
                                <i class="bi bi-chevron-left"></i>
                            </button>

                            <!-- Current Page / Total Pages -->
                            <span class="small text-muted">
                                <span id="currentPage">1</span> / <span id="totalPages">1</span>
                            </span>

                            <!-- Next Page Icon -->
                            <button id="nextPage" class="btn btn-lightPDF btn-sm">
                                <i class="bi bi-chevron-right"></i>
                            </button>

                            <!-- Switch Orientation Icon -->
                             <!--  <button id="toggleOrientation" class="btn btn-lightPDF btn-sm">
                                <i class="bi bi-arrows-angle-expand"></i>
                            </button> -->
                        </div>
                    </div>
                `;
                $('#content-viewer').html(contentHtml);

                // Initialize tracker
                globalTracker = {
                    intervalId: null,
                    viewedTime: 0,
                    contentId: contentId,
                    hasUpdated: false,
                    type: contentType,
                    isPlaying: true,
                    lastUpdateTime: 0,
                    contentLength: contentLength
                };

                // PDF.js variables
                let pdfDoc = null;
                let currentPage = 1;
                let totalPages = 0;
                let scale = 1.0;
                let orientation = 'portrait';
                const canvas = document.getElementById('pdf-canvas');
                const ctx = canvas.getContext('2d');

                pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
                    pdfDoc = pdf;
                    totalPages = pdf.numPages;
                    document.getElementById('totalPages').textContent = totalPages;
                    renderPage(currentPage);
                });

                function renderPage(pageNum) {
                    pdfDoc.getPage(pageNum).then(function(page) {
                        const viewport = page.getViewport({
                            scale: scale,
                            rotation: orientation === 'landscape' ? 90 : 0
                        });
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
                        page.render({
                            canvasContext: ctx,
                            viewport: viewport
                        });
                        document.getElementById('currentPage').textContent = pageNum;
                    });
                }

                // Navigation buttons
                $('#prevPage').click(function() {
                    if (currentPage <= 1) return;
                    currentPage--;
                    renderPage(currentPage);
                });

                $('#nextPage').click(function() {
                    if (currentPage >= totalPages) return;
                    currentPage++;
                    renderPage(currentPage);
                });
                $('#toggleOrientation').click(function() {
                    orientation = orientation === 'portrait' ? 'landscape' : 'portrait';
                    $(this).html(
                        `<i class="bi bi-arrows-angle-expand"></i> ${orientation === '' ? '' : ''}`
                    );
                    renderPage(currentPage);
                });


                // Start tracking
                startDocTracking();

                function startDocTracking() {
                    if (globalTracker.intervalId) clearInterval(globalTracker.intervalId);
                    globalTracker.intervalId = setInterval(function() {
                        globalTracker.viewedTime++;
                        if (Math.abs(globalTracker.viewedTime - globalTracker.lastUpdateTime) >= 5) {
                            updatePartialProgress(contentId, courseId, globalTracker.viewedTime);
                            globalTracker.lastUpdateTime = globalTracker.viewedTime;
                        }
                        if (globalTracker.viewedTime >= contentLength && !globalTracker.hasUpdated) {
                            globalTracker.hasUpdated = true;
                            updateProgress(contentId, courseId, contentLength, contentType);
                            stopTracking();
                        }
                    }, 1000);
                }

                // Stop tracking on prev/next
                $('.prev-content, .next-content').off('click').on('click', function() {
                    stopTracking();
                    if (globalTracker.viewedTime > globalTracker.lastUpdateTime) {
                        if (globalTracker.viewedTime >= contentLength) {
                            updateProgress(contentId, courseId, contentLength, contentType);
                        } else {
                            updatePartialProgress(contentId, courseId, globalTracker.viewedTime);
                        }
                    }
                });
            }
        }

        // Navigation handlers
        $(document).on('click', '.prev-content:not(.disabled)', function() {
            if (currentContentIndex > 0) {
                currentContentIndex--;
                const content = currentCourseContent[currentContentIndex];
                displayContentInViewer(
                    content.type,
                    content.document,
                    content.id,
                    $('.module-content-container').find('.load-content').first().data(
                        'course-id'),
                    content.length
                );
            }
        });

        $(document).on('click', '.next-content:not(.disabled)', function() {
            if (currentContentIndex < currentCourseContent.length - 1) {
                currentContentIndex++;
                const content = currentCourseContent[currentContentIndex];
                displayContentInViewer(
                    content.type,
                    content.document,
                    content.id,
                    $('.module-content-container').find('.load-content').first().data(
                        'course-id'),
                    content.length
                );
            }
        });

        function startTracking(contentId, courseId) {
            stopTracking();
            updatePartialProgress(contentId, courseId, globalTracker.viewedTime);
            globalTracker.intervalId = setInterval(function() {
                if (globalTracker.isPlaying) {
                    updatePartialProgress(contentId, courseId, globalTracker.viewedTime);
                }
            }, 1000);
        }

        function stopTracking() {
            if (globalTracker.intervalId) {
                clearInterval(globalTracker.intervalId);
                globalTracker.intervalId = null;
            }
        }

        function updatePartialProgress(contentId, courseId, duration) {
            if (Math.abs(duration - globalTracker.lastUpdateTime) >= 5 || duration === 0) {
                $.post("{{ route('tc.userTrainingDetails.document.partial') }}", {
                    _token: '{{ csrf_token() }}',
                    content_id: contentId,
                    course_id: courseId,
                    duration: duration,
                    training_id: "{{ $training_id }}" // Add training_id if needed
                }, function(response) {
                    if (response.success) {
                        globalTracker.lastUpdateTime = duration;

                        // Additional update to trainee_assigned_training_documents
                        $.post("{{ route('tc.userTrainingDetails.document.partial') }}", {
                            _token: '{{ csrf_token() }}',
                            content_id: contentId,
                            course_id: courseId,
                            duration: duration,
                            training_id: "{{ $training_id }}"
                        });
                    }
                });
            }
        }

        // Update the updateProgress function to ensure final duration is saved
        function updateProgress(contentId, courseId, contentLength, contentType) {
            // First update the progress
            $.post("{{ route('userTrainingDetails.document.progress') }}", {
                _token: '{{ csrf_token() }}',
                content_id: contentId,
                course_id: courseId,
                content_length: contentLength,
                content_type: contentType,
                duration: contentLength
            }, function(response) {
                if (response.success) {
                    // Then update the trainee_assigned_training_documents table
                    $.post("{{ route('tc.userTrainingDetails.document.partial') }}", {
                        _token: '{{ csrf_token() }}',
                        content_id: contentId,
                        course_id: courseId,
                        duration: contentLength,
                        training_id: "{{ $training_id }}",
                        is_completed: 1
                    }, function(updateResponse) {
                        if (updateResponse.success) {
                            // Update UI
                            $('.load-content[data-content-id="' + contentId + '"]')
                                .find('.small')
                                .removeClass('text-warning')
                                .addClass('text-success')
                                .text('Completed');
                        }
                    });
                }
            });
        }




        function formatTime(seconds) {
            if (seconds < 60) {
                return `Study required: ${seconds} sec`;
            } else {
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = seconds % 60;
                return `Study required: ${minutes}:${remainingSeconds.toString().padStart(2, '0')} min`;
            }
        }
    });
</script>
