@extends('front.layouts.trainee-default')
@section('content')
    <?php
    $endTime = strtotime($testDetails->end_date_time);
    $currentDateTime = date('Y-m-d H:i:s');
    $currentTimestamp = strtotime($currentDateTime);
    $difference = $endTime - $currentTimestamp;
    $hours = floor($difference / (60 * 60));
    $minutes = floor(($difference - $hours * 60 * 60) / 60);
    $trainingId = request()->route('id');
    ?>

    <!-- Test Instructions Modal -->
    <div class="modal fade" id="testInstructionsModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h3 class="modal-title">Test Instructions</h3>
                </div>
                <div class="modal-body">
                    <div class="instruction-content">
                        <h5 class="mb-3">Please read the instructions carefully before starting the test:</h5>
                        <div class="instructions">
                            {!! $testDetails->description !!}
                        </div>
                        <div class="test-details mt-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Test Name:</strong> {{ $testDetails->title }}</p>
                                    <p><strong>Duration:</strong> {{ $testDetails->time_of_test }} minutes</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Total Questions:</strong> {{ count($testQuestions) }}</p>
                                    <p><strong>Passing Score:</strong> {{ $testDetails->minimum_marks }}%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="startTestBtn">Start Test</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Test Interface -->
    <div id="testInterface" style="display: none;">
        <header class="traineeHead">
            <div class="container-fluid">
                <div class="d-flex flex-wrap align-items-center">
                    <div class="logoSec">
                        <a href="{{ route('front.dashboard') }}"><img src="../lms-img/qdegrees-logo.svg" alt="logo"
                                width="130" height="33px"></a>
                    </div>
                    <div class="courseName">
                        <p class="mb-0">{{ $testDetails->title }}</p>
                    </div>
                    <div id="countdown-timer" class="counttimerGroup">
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
                        <a href="{{ route('front.dashboard') }}" class="exitBtn">
                            <img src="../front/img/exit.svg" alt="icon" width="23" height="23">
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <div class="d-flex flex-wrap paddingTop">
            <div class="courseName trainingNameMobile d-lg-none w-100">
                <p class="mb-0">{{ $testDetails->title }}</p>
            </div>

            <div class="abouttraining">
                <div class="testGroup">
                    <form id="questionForm">
                        <input type="hidden" name="test_id" value="{{ $testDetails->id }}">
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                        <div class="test-header">
                            <h3 class="test-title">{{ $testDetails->title }}</h3>
                            <div class="test-meta">
                                <span class="question-counter">Question <span id="current-question-number">1</span> of
                                    {{ count($testQuestions) }}</span>
                                <div class="progress-container">
                                    <div class="progress">
                                        <div id="test-progress" class="progress-bar" role="progressbar" style="width: 0%"
                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span id="progress-percentage">0%</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <!-- Question container -->
                                <div id="question-container" class="question-container"></div>

                                <!-- Navigation buttons -->
                                <div class="navigation-buttons">
                                    <button type="button" id="prev-btn" class="btn btn-outline-primary" disabled>
                                        <i class="fas fa-arrow-left"></i> Previous
                                    </button>

                                    <button type="button" id="next-btn" class="btn btn-primary">
                                        Save & Next <i class="fas fa-arrow-right"></i>
                                    </button>
                                    <button type="button" id="submit-btn" class="btn btn-success"
                                        style="display: none;">
                                        <i class="fas fa-check-circle"></i> Submit Test
                                    </button>
                                </div>
                            </div>



                        </div>
                    </form>
                </div>
            </div>
            <!-- Test Instructions Card -->
            <div class="courseContent" id="courseContent">
                <div class="courselisting">
                    <div>
                        <div class="card sticky-top shadow-sm" style="top: 20px;">
                            <!-- Card Header -->
                            <div class="card-header">
                                <h5 class="mb-0">Test Instructions</h5>
                            </div>

                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="instructions-sidebar mb-3">
                                    {!! $testDetails->description !!}
                                </div>

                                <div class="test-summary">
                                    <div class="summary-item">
                                        <div class="summary-label">Duration:</div>
                                        <div class="summary-value">{{ $testDetails->time_of_test }} minutes</div>
                                    </div>
                                    <div class="summary-item">
                                        <div class="summary-label">Total Questions:</div>
                                        <div class="summary-value">{{ count($testQuestions) }}</div>
                                    </div>
                                    <div class="summary-item">
                                        <div class="summary-label">Passing Score:</div>
                                        <div class="summary-value">{{ $testDetails->minimum_marks }}%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




        </div>

        <!-- Confirm Submit Modal -->
        <div class="modal fade" id="confirmSubmit" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h3 class="modal-title">Confirm Submission</h3>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="selectUser">
                            <div class="d-sm-flex justify-content-between">
                                <div class="fs-6">
                                    <p>Are you sure you want to submit your test?</p>
                                    <p class="text-muted">Once submitted, you cannot change your answers.</p>
                                </div>
                            </div>
                            <div class="test-summary mt-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Questions Answered:</strong> <span
                                                id="answered-count">0</span>/{{ count($testQuestions) }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Time Remaining:</strong> <span id="time-remaining-display">0m 0s</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="final-submit-btn" class="btn btn-primary">Confirm Submission</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Enhanced Test Interface Styles */
        #testInterface {
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .traineeHead {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
        }

        .testGroup {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            padding: 25px;
            margin-bottom: 30px;
        }

        .test-header {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .test-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .test-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .question-counter {
            font-weight: 500;
            color: #495057;
        }

        .progress-container {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-grow: 1;
            max-width: 300px;
        }

        .progress {
            flex-grow: 1;
            height: 8px;
            border-radius: 4px;
        }

        .progress-bar {
            background-color: #4e73df;
            transition: width 0.3s ease;
        }

        #progress-percentage {
            font-size: 0.85rem;
            color: #6c757d;
            min-width: 40px;
            text-align: right;
        }

        .question-container {
            margin: 25px 0;
            padding: 20px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .testQuestion {
            margin-bottom: 0;
        }

        .clickType {
            font-size: 0.85rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .testQuestion h4 {
            font-size: 1.1rem;
            color: #343a40;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .ansCheck {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            /* 2 columns per row */
            gap: 12px;
        }

        .ansCheck .form-check {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            transition: all 0.2s;
            width: 100%;
        }

        .ansCheck .form-check:hover {
            border-color: #4e73df;
            background-color: #f8f9fe;
        }

        .ansCheck input[type="radio"],
        .ansCheck input[type="checkbox"] {
            margin-right: 12px;
            width: 18px;
            height: 18px;
        }

        .ansCheck label {
            cursor: pointer;
            margin-bottom: 0;
            flex-grow: 1;
        }

        .free-text-input {
            min-height: 120px;
            border-radius: 6px;
            border: 1px solid #dee2e6;
            padding: 15px;
        }

        .wordcounter {
            text-align: right;
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 5px;
        }

        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        /* Timer styles */
        .counttimerGroup {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #2c3e50;
        }

        .timerImg {
            display: flex;
            align-items: center;
        }

        .timerImg.redBorder svg {
            color: #e74c3c;
        }

        #countdown.blinking {
            animation: blink 1s linear infinite;
            color: #e74c3c;
        }

        @keyframes blink {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }

        /* Instruction modal styles */
        #testInstructionsModal .modal-content {
            border: none;
            border-radius: 10px;
            overflow: hidden;
        }

        #testInstructionsModal .modal-header {
            border-bottom: none;
        }

        .instruction-content {
            padding: 0 10px;
        }

        .instructions {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            max-height: 400px;
            overflow-y: auto;
        }

        .test-details {
            background: #f1f8fe;
            border-radius: 8px;
            padding: 15px;
        }

        /* Sidebar instructions */
        :root {
            --blue: #007bff;
            /* fallback */
        }

        /* Card Header Styling */
        .card-header {
            background-color: var(--blue);
            color: #fff;
            border-bottom: none;
            font-weight: 600;
            font-size: 1.3rem;
            padding: 1rem 1.25rem;
        }

        /* Scrollable Instructions Area */
        .instructions-sidebar {
            max-height: 300px;
            overflow-y: auto;
            padding-right: 10px;
            font-size: 0.95rem;
            line-height: 1.5;
            color: #333;
        }

        .instructions-sidebar p {
            margin-bottom: 10px;
        }

        /* Summary Section */
        .test-summary {
            margin-top: 1rem;
            font-size: 1rem;
            color: #222;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        /* Label-Value Pair (Row) */
        .summary-item {
            display: flex;
            align-items: center;
        }

        /* Label (fixed width) */
        .summary-label {
            width: 140px;
            font-weight: 600;
            color: var(--blue);
        }

        /* Value (grows to fill) */
        .summary-value {
            flex-grow: 1;
            font-weight: 500;
            color: #555;
        }



        /* Responsive adjustments */
        @media (max-width: 768px) {
            .test-meta {
                flex-direction: column;
                align-items: flex-start;
            }

            .progress-container {
                width: 100%;
                max-width: 100%;
            }

            .navigation-buttons {
                flex-wrap: wrap;
                gap: 10px;
            }

            .col-md-4 {
                margin-top: 30px;
            }
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            // Show instructions modal first
            const testInstructionsModal = new bootstrap.Modal(document.getElementById('testInstructionsModal'));
            testInstructionsModal.show();

            // Initialize variables
            const questions = {!! json_encode($testQuestions) !!};
            const totalQuestions = questions.length;
            let currentQuestionIndex = 0;
            let userAnswers = {};
            let testStarted = false;
            let countdownInterval;

            // Start test button handler
            $('#startTestBtn').click(function() {
                testInstructionsModal.hide();
                $('#testInterface').show();
                startTimer();
                testStarted = true;
            });

            // Initialize the test by showing the first question
            function initializeTest() {
                showQuestion(currentQuestionIndex);
                updateProgressBar();
                updateNavigationButtons();
                updateAnswerStatus();
            }

            function showQuestion(index) {
                const question = questions[index];
                const questionContainer = $('#question-container');
                const questionNumber = $('#current-question-number');

                // Update current question indicator
                currentQuestionIndex = index;
                questionNumber.text(index + 1);
                questionContainer.empty();

                // Build the question HTML
                let questionHtml = `
        <div class="testQuestion">
            <div class="clickType mb-2">${getQuestionTypeLabel(question.question_type)}</div>
            <h4 class="mb-3">${question.question}</h4>
    `;

                if (question.question_type === 'SCQ' || question.question_type === 'T/F') {
                    questionHtml += `<div class="ansCheck ">`;
                    question.question_attributes.forEach(option => {
                        const isChecked = userAnswers[question.id] &&
                            (userAnswers[question.id].answer_id == option.id ||
                                (Array.isArray(userAnswers[question.id].answer_id) &&
                                    userAnswers[question.id].answer_id.includes(option.id.toString())));

                        questionHtml += `
                    <div class="form-check">
                        <input class="form-check-input" type="radio"
                            id="option-${option.id}" name="answer-${question.id}" value="${option.id}"
                            ${isChecked ? 'checked' : ''}>
                        <label class="form-check-label" for="option-${option.id}">
                            ${option.option}
                        </label>
                    </div>
            `;
                    });
                    questionHtml += `</div>`;

                } else if (question.question_type === 'MCQ') {
                    questionHtml += `<div class="ansCheck ">`;
                    question.question_attributes.forEach(option => {
                        const isChecked = userAnswers[question.id] &&
                            (userAnswers[question.id].answer_id == option.id ||
                                (Array.isArray(userAnswers[question.id].answer_id) &&
                                    userAnswers[question.id].answer_id.includes(option.id.toString())));

                        questionHtml += `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                            id="option-${option.id}" name="answer-${question.id}[]" value="${option.id}"
                            ${isChecked ? 'checked' : ''}>
                        <label class="form-check-label" for="option-${option.id}">
                            ${option.option}
                        </label>
                    </div>
            `;
                    });
                    questionHtml += `</div>`;

                } else if (question.question_type === 'FreeText') {
                    const answerText = userAnswers[question.id] ? userAnswers[question.id].answer_text : '';
                    questionHtml += `
            <div class="ansCheck pb-3">
                <textarea class="form-control free-text-input mb-2" name="answer-text-${question.id}" rows="4" maxlength="150">${answerText}</textarea>
                <div class="wordcounter text-end">${answerText.length}/150</div>
            </div>
        `;
                }

                questionHtml += `</div>`;
                questionContainer.html(questionHtml);

                // Update word counter for FreeText questions
                if (question.question_type === 'FreeText') {
                    $(`textarea[name="answer-text-${question.id}"]`).on('input', function() {
                        const length = $(this).val().length;
                        $(this).siblings('.wordcounter').text(`${length}/150`);
                    });
                }

                // Update navigation buttons
                updateNavigationButtons();
                updateProgressBar();
            }


            // Helper function to get question type label
            function getQuestionTypeLabel(type) {
                switch (type) {
                    case 'SCQ':
                        return 'Single Choice Question';
                    case 'MCQ':
                        return 'Multiple Choice Question';
                    case 'T/F':
                        return 'True/False Question';
                    case 'FreeText':
                        return 'Free Text';
                    default:
                        return 'Question';
                }
            }

            // Function to check if current question has an answer
            function hasAnswerForCurrentQuestion() {
                const question = questions[currentQuestionIndex];

                if (question.question_type === 'SCQ' || question.question_type === 'T/F') {
                    return $(`input[name="answer-${question.id}"]:checked`).length > 0;
                } else if (question.question_type === 'MCQ') {
                    return $(`input[name="answer-${question.id}[]"]:checked`).length > 0;
                } else if (question.question_type === 'FreeText') {
                    return $(`textarea[name="answer-text-${question.id}"]`).val().trim().length > 0;
                }

                return false;
            }

            // Function to save the current answer
            function saveCurrentAnswer() {
                const question = questions[currentQuestionIndex];
                let answerData = {};

                if (question.question_type === 'SCQ' || question.question_type === 'T/F') {
                    const selectedOption = $(`input[name="answer-${question.id}"]:checked`).val();
                    if (selectedOption) {
                        answerData = {
                            question_id: question.id,
                            answer_id: selectedOption,
                            answer_text: null
                        };
                    }
                } else if (question.question_type === 'MCQ') {
                    const selectedOptions = [];
                    $(`input[name="answer-${question.id}[]"]:checked`).each(function() {
                        selectedOptions.push($(this).val());
                    });

                    if (selectedOptions.length > 0) {
                        answerData = {
                            question_id: question.id,
                            answer_id: selectedOptions.join(','),
                            answer_text: null
                        };
                    }
                } else if (question.question_type === 'FreeText') {
                    const answerText = $(`textarea[name="answer-text-${question.id}"]`).val();
                    if (answerText) {
                        answerData = {
                            question_id: question.id,
                            answer_id: null,
                            answer_text: answerText
                        };
                    }
                }

                // Only save if there's an answer
                if (Object.keys(answerData).length > 0) {
                    userAnswers[question.id] = answerData;
                    updateAnswerStatus();
                    return true;
                }

                return false;
            }

            // Function to navigate to the next question
            function goToNextQuestion() {
                // First try to save the current answer
                if (!saveCurrentAnswer()) {
                    // If no answer was selected, show alert and prevent navigation
                    alert('Please select an answer before proceeding.');
                    return;
                }

                if (currentQuestionIndex < totalQuestions - 1) {
                    currentQuestionIndex++;
                    showQuestion(currentQuestionIndex);
                }
            }

            // Function to navigate to the previous question
            function goToPreviousQuestion() {
                if (currentQuestionIndex > 0) {
                    // Save current answer if any
                    saveCurrentAnswer();
                    currentQuestionIndex--;
                    showQuestion(currentQuestionIndex);
                }
            }

            // Function to update navigation buttons state
            function updateNavigationButtons() {
                $('#prev-btn').prop('disabled', currentQuestionIndex === 0);

                if (currentQuestionIndex === totalQuestions - 1) {
                    $('#next-btn').hide();
                    $('#submit-btn').show();
                } else {
                    $('#next-btn').show();
                    $('#submit-btn').hide();
                }
            }

            // Function to update progress bar
            function updateProgressBar() {
                const progress = ((currentQuestionIndex + 1) / totalQuestions) * 100;
                $('#test-progress').css('width', progress + '%').attr('aria-valuenow', progress);
                $('#progress-percentage').text(Math.round(progress) + '%');
            }

            // Function to update answer status indicators
            function updateAnswerStatus() {
                const answeredCount = Object.keys(userAnswers).length;

                // Update answered count in confirmation modal
                $('#answered-count').text(answeredCount);
            }

            // Function to start the timer
            function startTimer() {
                const countdownElement = $('#countdown');
                const timerImgElement = $('.timerImg');
                let countdown = {{ $testDetails->time_of_test }} * 60;

                // Update time remaining in confirmation modal
                function updateTimeRemainingDisplay(seconds) {
                    const minutes = Math.floor(seconds / 60);
                    const remainingSeconds = seconds % 60;
                    $('#time-remaining-display').text(
                        `${minutes}m ${remainingSeconds}s`
                    );
                }

                updateTimeRemainingDisplay(countdown);

                countdownInterval = setInterval(function() {
                    const minutes = Math.floor(countdown / 60);
                    const seconds = countdown % 60;

                    countdownElement.text(
                        (minutes < 10 ? '0' + minutes : minutes) + 'm ' +
                        (seconds < 10 ? '0' + seconds : seconds) + 's'
                    );

                    updateTimeRemainingDisplay(countdown);

                    if (countdown <= 0) {
                        clearInterval(countdownInterval);
                        submitTest(); // Auto-submit when time expires
                    } else if (countdown <= 300) { // 5 minutes or less
                        countdownElement.addClass('blinking');
                        timerImgElement.addClass('redBorder');
                    }

                    countdown--;
                }, 1000);
            }

            // Function to submit the test
            function submitTest() {
                // Save the current answer before submitting
                if (!saveCurrentAnswer()) {
                    if (!confirm('You have not answered the current question. Are you sure you want to submit?')) {
                        return;
                    }
                }

                // Clear the timer
                clearInterval(countdownInterval);

                // Submit all answers to the server
                const userId = {{ Auth::user()->id }};
                const testId = {{ $testDetails->id }};
                const csrfToken = '{{ csrf_token() }}';

                // Prepare all answers for submission
                const answersToSubmit = Object.values(userAnswers);

                // Submit each answer
                let submittedCount = 0;

                function submitNextAnswer() {
                    if (submittedCount < answersToSubmit.length) {
                        const answer = answersToSubmit[submittedCount];

                        $.ajax({
                            type: 'POST',
                            url: '{{ URL('/submit-test-response') }}',
                            data: {
                                question_id: answer.question_id,
                                answer_id: answer.answer_id,
                                answer_text: answer.answer_text,
                                user_id: userId,
                                test_id: testId,
                                _token: csrfToken
                            },
                            success: function(response) {
                                submittedCount++;
                                if (response.successRedirect) {
                                    window.location.href = '{{ URL('/test-already-submitted') }}';
                                    return;
                                }
                                submitNextAnswer();
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.error('Error submitting response:', errorThrown);
                                // Continue with next answer even if one fails
                                submittedCount++;
                                submitNextAnswer();
                            }
                        });
                    } else {
                        // All answers submitted, redirect to results page
                        window.location.href = '{{ route('user.test.result', $testDetails->id) }}';
                    }
                }

                // Start the submission process
                submitNextAnswer();
            }

            // Event handlers
            $('#prev-btn').click(goToPreviousQuestion);
            $('#next-btn').click(goToNextQuestion);
            $('#submit-btn').click(function() {
                $('#confirmSubmit').modal('show');
            });
            $('#final-submit-btn').click(submitTest);

            // Initialize the test when instructions modal is hidden
            $('#testInstructionsModal').on('hidden.bs.modal', function() {
                if (testStarted) {
                    initializeTest();
                }
            });
        });
    </script>
@stop
