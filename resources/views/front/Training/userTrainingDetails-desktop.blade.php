<div class="desktopScren">
    <style>
        .courseListingBtn2 {
            position: absolute;
            bottom: 10px;
            width: calc(100% - 30px);
            left: 0;
            right: 0;
            margin: auto;
            z-index: 1111111;
        }

        .star {
            font-size: 30px;
            color: #ccc;
            cursor: pointer;
            margin: 0 5px;
        }

        .star.selected {
            color: #ffcc00;
        }

        .rating-control {
            text-align: center;
            margin-top: 10px;
        }

        .btn-rating {
            position: relative;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 600;
            cursor: not-allowed;
            transition: all 0.3s ease;
            min-width: 200px;
            opacity: 0.7;
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }

        .btn-rating:not(:disabled) {
            background: linear-gradient(135deg, #007bff, #0056b3);
            opacity: 1;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
        }

        .btn-rating:not(:disabled):hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
        }

        .rating-icon {
            font-size: 16px;
            margin-right: 6px;
        }

        .rating-requirement {
            font-size: 11px;
            font-weight: 400;
            opacity: 0.8;
        }

        .btn-rating:not(:disabled) .rating-requirement {
            display: none;
        }
    </style>
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

                        <div class="tab-pane courseDocument{{ $content['id'] }}"
                            id="courseViseView{{ $content['id'] }}">
                            @if ($content['type'] === 'video')
                                <video id="myVideo" class="videoContent" width="100%" height="500px" controls>
                                    <source
                                        src="{{ config('constants.TRAINING_DOCUMENT_URL') . '/' . $content['document'] }}"
                                        type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                                {{-- @elseif ($content['type'] === 'doc' && $content['document_type'] === 'pdf')
                                <iframe src="{{ asset('training_document/' . $content['document']) }}" width="100%"
                                    height="500px" style="border: none;"></iframe> --}}
                            @elseif ($content['type'] === 'doc' && $content['document_type'] === 'pdf')
                                <div id="pdf-viewer-{{ $content['id'] }}" class="pdf-viewer-container"
                                    data-content-id="{{ $content['id'] }}"
                                    data-pdf-url="{{ asset('training_document/' . $content['document']) }}"
                                    style="border:1px solid #ccc; width:100%;">
                                    <canvas id="pdf-canvas-{{ $content['id'] }}"
                                        style="border:1px solid #ccc; width:100%;"></canvas>

                                    <div class="d-flex justify-content-between align-items-center mb-2 gap-2 p-2">
                                        <!-- Previous Page -->
                                        <button id="prevPage-{{ $content['id'] }}" class="btn btn-lightPDF btn-sm">
                                            <i class="bi bi-chevron-left"></i>
                                        </button>



                                        <!-- Current Page / Total Pages -->
                                        <span id="currentPage-{{ $content['id'] }}">1</span> /
                                        <span id="totalPages-{{ $content['id'] }}">1</span>
                                        <!-- Next Page -->
                                        <button id="nextPage-{{ $content['id'] }}" class="btn btn-lightPDF btn-sm">
                                            <i class="bi bi-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            @elseif ($content['type'] === 'pdf' && $content['document_type'] === 'pdf')
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
                    <p><span>Time Left</span><span id="countdown" class="ml-5"></span>Embrace the urgency: Time
                        Remaining
                        for Training.
                        Every second counts as you embark on your training journey. The time left is a precious resource
                        that demands your focus, determination, and dedication. With each passing moment, opportunities
                        for
                        growth and improvement await.</p>
                </div>
                <div class="timerGroup">
                    <div class="rating-control">
                        <button id="giveRatingButton" class="btn-rating" disabled>
                            {{-- <span class="rating-icon">★</span> --}}
                            Rate This Training
                            <span class="rating-requirement">50% completion required</span>
                        </button>
                    </div>
                </div>
            </div>

            @php
                $givenRatings = \App\Models\TrainingRating::where('user_id', Auth::id())
                    ->where('training_id', $training_id)
                    ->value('rating');
            @endphp

            <div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ratingModalLabel">
                                @if (isset($givenRatings) && $givenRatings)
                                    Update Your Rating
                                @else
                                    Rate Your Training
                                @endif
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="stars">
                                <!-- Star Rating (5 Stars) -->
                                <span class="star" data-value="1">&#9733;</span>
                                <span class="star" data-value="2">&#9733;</span>
                                <span class="star" data-value="3">&#9733;</span>
                                <span class="star" data-value="4">&#9733;</span>
                                <span class="star" data-value="5">&#9733;</span>
                            </div>
                            <div style="display:none;" id="debugInfo">
                                Given Ratings PHP Value: {{ $givenRatings ?? 'null' }}<br>
                                Given Ratings Type: {{ gettype($givenRatings) }}<br>
                                Has Existing Rating: {{ isset($givenRatings) && $givenRatings ? 'true' : 'false' }}
                            </div>
                            {{-- <p id="selectedRatingText"> --}}
                            @if (isset($givenRatings) && $givenRatings)
                                <span class="mt-2"> Your current rating: {{ $givenRatings }} stars </span>
                            @else
                                <span class="mt-2"> No rating selected</span>
                            @endif
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" id="submitRating" class="btn btn-primary">
                                @if (isset($givenRatings) && $givenRatings)
                                    Update Rating
                                @else
                                    Submit Rating
                                @endif
                            </button>

                            <!-- Add delete button if rating exists -->
                            {{-- @if (isset($givenRatings) && $givenRatings)
                                <button type="button" id="deleteRating" class="btn btn-danger">Delete Rating</button>
                            @endif --}}
                        </div>
                    </div>
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
                {{-- <hr>
                <div class="certificates">
                    <strong class="mb-2 d-block">Certificates</strong>
                    <p class="mb-2">Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>
                    <p class="mb-2">Complete the training and test to achieve this certificate</p>
                    <a href="javascript:void(0)" class="btn btn-light py-2 px-3 fs-7">Certificate</a>
                </div> --}}
            </div>
        </div>

        <div class="courseContent courses-and-document-listing" id="courseContent">
            <div class="courselisting">
                <strong class="mb-3 d-block fs-5 fw-semibold">Course Content</strong>
                <div class="accordion" id="accordionExample">
                    @foreach ($trainingCourses as $index => $course)
                        @php
                            $canAccess = true;
                        @endphp

                        <div class="accordion-item mb-3 border rounded shadow-sm">
                            <h2 class="accordion-header" id="heading{{ $index }}">
                                <button class="accordion-button collapsed bg-light" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="false"
                                    aria-controls="collapse{{ $index }}"
                                    data-course-id="{{ $course->id }}">
                                    {{ $course['title'] }}
                                </button>
                            </h2>

                            <div id="collapse{{ $index }}"
                                class="accordion-collapse collapse "
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
                                                                    Video • <small style="font-size: 11px"> Study
                                                                        required:
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
                                                        {{-- <div
                                                            class="small {{ $documentCompletionInside ? 'text-success' : 'text-warning' }}">
                                                            {{ $documentCompletionInside ? 'Completed' : 'Not Started' }}
                                                        </div> --}}
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

                                                // $numberOfAttempts = \App\Models\TrainingTestParticipants::where(
                                                //     'trainee_id',
                                                //     Auth::user()->id,
                                                // )
                                                //     ->where('course_id', $course->id)
                                                //     ->first();
                                                if ($testAlreadySubmited) {
                                                    if (
                                                        $testAlreadySubmited->number_of_attempts >
                                                        $testAlreadySubmited->user_attempts
                                                    ) {
                                                        $canAttempt = true;
                                                    } elseif (
                                                        $testAlreadySubmited->number_of_attempts <=
                                                        $testAlreadySubmited->user_attempts
                                                    ) {
                                                        $canAttempt = false;
                                                    } else {
                                                        $canAttempt = true;
                                                    }
                                                } else {
                                                    $canAttempt = true;
                                                }
                                            @endphp

                                            <div class="mt-4 pt-3 border-top ">
                                                @php
                                                    $isDisabled = false;
                                                    $buttonText = $canAttempt ? 'Begin Test' : 'Max Attempts Reached';
                                                    $message = !$canAttempt
                                                        ? 'You have reached the maximum number of attempts'
                                                        : '';
                                                    $testRoute = route('userTraining.test', [
                                                        'training_id' => $training_id,
                                                        'course_id' => $course->id,
                                                        'test_id' => $course->test_id,
                                                    ]);
                                                @endphp

                                                <div class="mt-4 pt-3 border-top ">
                                                    <a class="btn btn-primary w-100 courseListingBtn2{{ $isDisabled ? 'disabled' : '' }}"
                                                        href="{{ $testRoute }}">
                                                        <i class="bi bi-pencil-square me-2"></i>{{ $buttonText }}
                                                        @if ($message)
                                                            <small class="d-block mt-1">{{ $message }}</small>
                                                        @endif
                                                    </a>
                                                </div>
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
    <div class="overllayBg" id="overllayBg" style="display: none;"></div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
{{-- PDF.js library --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const pdfViewers = document.querySelectorAll('.pdf-viewer-container');

        pdfViewers.forEach(function(viewer) {
            const contentId = viewer.dataset
                .contentId; // make sure you add data-content-id="{{ $content['id'] }}"
            const pdfUrl = viewer.dataset
                .pdfUrl; // set data-pdf-url="{{ asset('training_document/' . $content['document']) }}"
            let pdfDoc = null,
                currentPage = 1,
                totalPages = 0,
                scale = 1.2;

            const canvas = document.getElementById(`pdf-canvas-${contentId}`);
            const ctx = canvas.getContext('2d');

            pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
                pdfDoc = pdf;
                totalPages = pdf.numPages;
                document.getElementById(`totalPages-${contentId}`).textContent = totalPages;
                renderPage(currentPage);
            });

            function renderPage(pageNum) {
                pdfDoc.getPage(pageNum).then(function(page) {
                    const viewport = page.getViewport({
                        scale: scale
                    });
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    page.render({
                        canvasContext: ctx,
                        viewport: viewport
                    });
                    document.getElementById(`currentPage-${contentId}`).textContent = pageNum;
                });
            }

            // Navigation buttons
            document.getElementById(`prevPage-${contentId}`).addEventListener('click', function() {
                if (currentPage <= 1) return;
                currentPage--;
                renderPage(currentPage);
            });

            document.getElementById(`nextPage-${contentId}`).addEventListener('click', function() {
                if (currentPage >= totalPages) return;
                currentPage++;
                renderPage(currentPage);
            });
        });
    });
</script>
<script>
    function enableRatingIfEligible() {
        const total = $('a.courseGroup').length;
        const completed = $('a.courseGroup .small.text-success').length;
        const percentage = total > 0 ? (completed / total) * 100 : 0;

        $('#giveRatingButton').prop('disabled', false);

    }
    $(document).ready(function() {
        enableRatingIfEligible();
    });
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
            });
            enableRatingIfEligible();
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
<script>
    $(document).ready(function() {
        let selectedRating = {{ $givenRatings ?? 0 }};
        const trainingId = <?php echo $training_id; ?>;
        const hasExistingRating = {{ isset($givenRatings) && $givenRatings ? 'true' : 'false' }};

        // Auto-fill stars if user has existing rating
        if (hasExistingRating && selectedRating > 0) {
            highlightStars(selectedRating);
            $('#selectedRatingText').text('Your current rating: ' + selectedRating + ' stars');
        }

        // Star click handler
        $('.star').on('click', function() {
            selectedRating = $(this).data('value');
            highlightStars(selectedRating);
            $('#selectedRatingText').text('Selected Rating: ' + selectedRating + ' stars');
        });

        // Function to highlight stars
        function highlightStars(rating) {
            $('.star').removeClass('selected');
            $('.star').each(function() {
                if ($(this).data('value') <= rating) {
                    $(this).addClass('selected');
                }
            });
        }

        // Open modal handler
        $('#giveRatingButton').on('click', function() {
            // Reset to existing rating when modal opens
            if (hasExistingRating && selectedRating > 0) {
                highlightStars(selectedRating);
                $('#selectedRatingText').text('Your current rating: ' + selectedRating + ' stars');
            } else {
                selectedRating = 0;
                $('.star').removeClass('selected');
                $('#selectedRatingText').text('No rating selected');
            }
            $('#ratingModal').modal('show');
        });

        // Submit rating handler
        $('#submitRating').on('click', function() {
            if (selectedRating === 0) {
                alert('Please select a rating.');
                return;
            }

            const userId = {{ auth()->id() }};
            const url = '/submit-rating';

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    training_id: trainingId,
                    user_id: userId,
                    rating: selectedRating,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert(response.message);
                    $('#ratingModal').modal('hide');
                    // Update button text if it was first rating
                    if (!hasExistingRating) {
                        $('#giveRatingButton').html(
                            '<span class="rating-icon">★</span>Update Rating');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Something went wrong. Please try again.');
                }
            });
        });

        // Delete rating handler (if delete button exists)
        $('#deleteRating').on('click', function() {
            if (confirm('Are you sure you want to delete your rating?')) {
                const userId = {{ auth()->id() }};

                $.ajax({
                    url: '/delete-rating',
                    method: 'POST',
                    data: {
                        training_id: trainingId,
                        user_id: userId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert(response.message);
                        $('#ratingModal').modal('hide');
                        // Reset button text
                        $('#giveRatingButton').html(
                            '<span class="rating-icon">★</span>Give Rating');
                        // Reset stars for next time
                        selectedRating = 0;
                        hasExistingRating = false;
                    },
                    error: function(xhr, status, error) {
                        alert('Something went wrong. Please try again.');
                    }
                });
            }
        });
    });
</script>
