<div class="mobileScren">

    <div class="modal fade" id="mobile-testInstructionsModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-2 ">
                <div class="modal-header bg-primary text-white">
                    <h3 class="modal-title">Test Instructions</h3>
                </div>
                <div class="modal-body">
                    <div class="mobile-textIntruction d-md-none pb-5">
                        <div class="mobile-headingInt">
                            <span class="d-block">Test Instructions</span>
                            <p>Please read the instructions carefully before starting the test:</p>
                        </div>
                        <ul class="mobile-textType ps-0">
                            <li>
                                <b>1. Test Duration:</b>
                                <p>{{ $trainingTest->time_of_test }}</p>
                            </li>
                            <li>
                                <b>2. Total Questions:</b>
                                <p>{{ count($trainingQuestions) }} </p>
                            </li>
                            <li>
                                <b>3. Passing Score:</b>
                                <p>{{ $trainingTest->minimum_marks }}%</p>
                            </li>
                            <li>
                                <b>4. Question Types:</b>
                                <p>The test consists of MCQS (Multiple Choice Questions), SCQS (Single Correct
                                    Questions), and
                                    True/False type questions </p>
                            </li>
                            <li>
                                <b>5. All Questions Are Mandatory:</b>
                                <p>You must answer every question before submitting. Unanswered questions will
                                    prevent final
                                    submission.</p>
                            </li>
                            <li>
                                <b>6. No Negative Marking:</b>
                                <p>There is no penalty for incorrect answers, so attempt all questions
                                    confidently.</p>
                            </li>
                            <li>
                                <b>7. Stable Internet Required:</b>
                                <p>Any network disconnection may automatically submit your test and log the
                                    activity.</p>
                            </li>
                            <li>
                                <b>8. Stay on Test Page:</b>
                                <p>Switching to another tab or minimizing the browser will trigger warnings.
                                    Multiple violations
                                    will auto-submit your test.</p>
                            </li>
                            <li>
                                <b>9. Do Not Refresh:</b>
                                <p>Reloading, pressing F5, or clicking the back button will end your test
                                    immediately.</p>
                            </li>
                            <li>
                                <b>10. Webcam Must Stay On:</b>
                                <p>Your webcam must detect your face during the entire test.</p>
                            </li>
                            <li>
                                <b>11. Mic Access May Be Monitored:</b>
                                <p>Your microphone may be used to monitor ambient noise levels to detect
                                    suspicious behavior.
                                </p>
                            </li>
                            <li>
                                <b>12. No Mobile Devices:</b>
                                <p>Using your phone, smartwatches, or other digital devices is strictly
                                    prohibited during the
                                    test.</p>
                            </li>
                            <li>
                                <b>13. No External Help:</b>
                                <p>This is an individual assessment. Collaboration or help from others will
                                    result in
                                    disqualification.</p>
                            </li>
                            <li>
                                <b>14. Al Surveillance Active:</b>
                                <p>Face, tab, and activity monitoring tools are in use to ensure test integrity.
                                    Every action is
                                    logged.</p>
                            </li>
                            <li>
                                <b>15. Copy/Paste Disabled:</b>
                                <p>Right-click, inspect element, or using keyboard shortcuts like Ctrl+C/Ctrl+V
                                    is disabled and
                                    logged.</p>
                            </li>
                            <li>
                                <b>16. System Focus Monitoring:</b>
                                <p>Unusual mouse movements or inactivity may be flagged as suspicious.</p>
                            </li>
                            <li>
                                <b>17. Time-Managed Questions:</b>
                                <p>Allocate your time wisely. Some questions may be time-bound within the test.
                                </p>
                            </li>
                            <li>
                                <b>18. Zero Tolerance Policy:</b>
                                <p>Any attempt to bypass restrictions will result in immediate test submission
                                    and logging of
                                    the attempt.</p>
                            </li>
                            <li class="border-0">
                                <b>19. Result Review:</b>
                                <p>Results will be reviewed before finalization. Suspicious attempts may be
                                    invalidated.</p>
                            </li>
                            @if (!empty($trainingTest->description))
                                <ul class="tittleUl">
                                    <li>
                                        <b>Test Title:</b>
                                        <p>{!! $trainingTest->title !!}</p>
                                    </li>
                                    <li>
                                        <b>Test Description:</b>
                                        <p>{!! $trainingTest->description !!}</p>
                                    </li>
                                </ul>
                            @endif

                            <div class="form-check my-4">
                                <input class="form-check-input" type="checkbox" value="" id="mobile-checkDefault">
                                <label class="form-check-label" for="mobile-checkDefault">
                                    I've Read and Start test
                                </label>
                            </div>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="mobile-startTestBtn" class="btn btn-success" disabled>✅ I've Read &
                        Start Test</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Submission Modal -->
    <div class="modal fade" id="mobile-submissionMdl">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center mdlContent">
                    <div class="mb-4">
                        <lottie-player src="{{ asset('front/img/online-exam.json') }}"
                            style="width: 100%;height:200px;margin: auto;" loop autoplay></lottie-player>
                    </div>
                    <span class="d-block mb-3">Confirm Submission</span>
                    <p class="px-3">Are you sure you want to submit your test? Once submitted, you cannot change your
                        answers.</p>
                    <div class="modalSpan text-start mb-4">
                        <strong class="mb-3">Questions Answered: <span
                                id="answered-count">0</span>/{{ count($trainingQuestions) }}</strong>
                        <strong>Time Remaining: <span id="time-remaining">0m 0s</span></strong>
                    </div>
                    <div class="d-flex align-items-center justify-content-center gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="mobile-final-submit-btn" class="btn btn-primary quizBtn">Confirm
                            Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="questionsScrn d-md-none pb-5 w-100 m-0" style="display: none; ">
        <a href="" class="moduleBck d-md-none mb-3 d-block">
            <img src="https://demolms.qdegrees.com/front/img/back-button.png" alt="" width="50"
                class="me-2">
        </a>
        <div class="headingInt">
            <span class="d-block fw-medium">Question <span
                    id="current-question-number">1</span>/{{ count($trainingQuestions) }}</span>
            <div class="barProgress mx-0">
                <div class="progress mb-2 bg-white" role="progressbar" aria-label="Basic example" aria-valuenow="0"
                    aria-valuemin="0" aria-valuemax="100">
                    <div id="test-progress" class="progress-bar" style="width: 0%"></div>
                </div>
            </div>
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
        <div class="questionInner">
            <b id="question-title">Question: 1</b>
            <p id="question-text" class="mb-4"></p>
            <ul id="question-options" class="qualityCheckList p-0"></ul>
            <div class="d-flex justify-content-between mt-4">
                <button type="button" id="prev-btn" class="btn btn-secondary"
                    style="background-color: #6c757d; border-color: #6c757d; color: white;" disabled>Previous</button>
                <button type="button" id="next-btn"
                    style="background-color: hsl(209, 93%, 54%); border-color: hsl(209, 93%, 54%); color: white;"
                    class="btn btn-primary">Submit & Next</button>
            </div>
        </div>
    </div>


    <style>
        .counttimerGroup {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background-color: #f1f8ff;
            color: #00407e;
            padding: 8px 16px;
            border-radius: 30px;
            width: fit-content;
            margin: 16px auto 25px auto;
            box-shadow: 0 0 8px rgba(0, 64, 126, 0.1);
            font-weight: 600;
        }

        .counttimerGroup .timerImg {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #00407e;
        }

        .counttimerGroup #countdown {
            font-size: 18px;
            font-family: 'Courier New', Courier, monospace;
            min-width: 70px;
            text-align: center;
        }

        /* Optional: Flash red when under 1 min */
        .counttimerGroup.low-time {
            background-color: #fff0f0;
            color: #d12b2b;
            animation: pulse 1s infinite;
        }

        .counttimerGroup.low-time .timerImg {
            color: #d12b2b;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            // Show mobile instructions modal first
            const mobileTestInstructionsModal = new bootstrap.Modal(document.getElementById(
                'mobile-testInstructionsModal'));
            mobileTestInstructionsModal.show();

            // Initialize variables
            const questions = {!! json_encode($trainingQuestions) !!};
            const totalQuestions = questions.length;
            let currentQuestionIndex = 0;
            let userAnswers = {};
            let testStarted = false;
            let countdownInterval;

            // Enable Start Test button when checkbox is checked
            $('#mobile-checkDefault').change(function() {
                $('#mobile-startTestBtn').prop('disabled', !this.checked);
            });

            // Start test button handler
            $('#mobile-startTestBtn').click(function() {
                mobileTestInstructionsModal.hide();
                $('.questionsScrn').show(); // Show the existing design div
                startTimer();
                testStarted = true;
                showQuestion(currentQuestionIndex);
            });


            // Function to show question
            // Update showQuestion function to enable/disable previous button
            function showQuestion(index) {
                const question = questions[index];

                // Update question info
                $('#current-question-number').text(index + 1);
                $('#question-title').text(`Question: ${index + 1}`);
                $('#question-text').text(question.question);

                // Update progress
                const progress = ((index + 1) / totalQuestions) * 100;
                $('#test-progress').css('width', `${progress}%`).attr('aria-valuenow', progress);

                // Clear and rebuild options
                const $optionsList = $('#question-options').empty();

                if (question.question_type === 'SCQ' || question.question_type === 'T/F') {
                    question.question_attributes.forEach((option) => {
                        const isChecked = userAnswers[question.id] &&
                            (userAnswers[question.id].answer_id == option.id ||
                                (Array.isArray(userAnswers[question.id].answer_id) &&
                                    userAnswers[question.id].answer_id.includes(option.id.toString())));

                        $optionsList.append(`
                <li>
                    <input type="radio" id="option-${question.id}-${option.id}" name="answer-${question.id}" 
                           value="${option.id}" ${isChecked ? 'checked' : ''}>
                    <label for="option-${question.id}-${option.id}">${option.option}</label>
                </li>
            `);
                    });
                } else if (question.question_type === 'MCQ') {
                    question.question_attributes.forEach((option) => {
                        const isChecked = userAnswers[question.id] &&
                            (Array.isArray(userAnswers[question.id].answer_id) &&
                                userAnswers[question.id].answer_id.includes(option.id.toString()));

                        $optionsList.append(`
                <li>
                    <input type="checkbox" id="option-${question.id}-${option.id}" name="answer-${question.id}[]" 
                           value="${option.id}" ${isChecked ? 'checked' : ''}>
                    <label for="option-${question.id}-${option.id}">${option.option}</label>
                </li>
            `);
                    });
                } else if (question.question_type === 'FreeText') {
                    const answerText = userAnswers[question.id] ? userAnswers[question.id].answer_text : '';
                    $optionsList.append(`
        <li class="free-text-item">
            <textarea class="form-control" name="answer-text-${question.id}" 
                     rows="4" maxlength="150">${answerText}</textarea>
            <div class="wordcounter text-end">${answerText.length}/150</div>
        </li>
    `);

                    // Update word counter
                    $(`textarea[name="answer-text-${question.id}"]`).on('input', function() {
                        $(this).siblings('.wordcounter').text(`${$(this).val().length}/150`);
                    });
                }

                // Update button text
                $('#next-btn').text(
                    index === totalQuestions - 1 ? 'Submit Test' : 'Submit & Next'
                );

                // Enable/disable Previous button
                if (index === 0) {
                    $('#prev-btn').prop('disabled', true);
                } else {
                    $('#prev-btn').prop('disabled', false);
                }
            }

            // Handle Previous button click
            $('#prev-btn').click(function() {
                if (currentQuestionIndex > 0) {
                    // Save current answer (but no validation alert)
                    saveAnswer();
                    currentQuestionIndex--;
                    showQuestion(currentQuestionIndex);
                }
            });


            // Timer function
            // function startTimer() {
            //     let countdown = {{ $trainingTest->time_of_test }} * 60;

            //     updateTimeDisplay(countdown);

            //     countdownInterval = setInterval(function() {
            //         countdown--;
            //         updateTimeDisplay(countdown);

            //         if (countdown <= 0) {
            //             clearInterval(countdownInterval);
            //             submitTest();
            //         } else if (countdown <= 300) { // 5 minutes or less
            //             $('#time-remaining').addClass('text-danger');
            //         }
            //     }, 1000);
            // }

            // function updateTimeDisplay(seconds) {
            //     const minutes = Math.floor(seconds / 60);
            //     const remainingSeconds = seconds % 60;
            //     $('#time-remaining').text(
            //         `${minutes}m ${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}s`
            //     );
            // }

            if (countdown <= 60) {
                $('#countdown-timer').addClass('low-time');
            } else {
                $('#countdown-timer').removeClass('low-time');
            }

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

            // Navigation handler
            $('#next-btn').click(function() {
                if (!saveAnswer()) {
                    alert('Please select an answer before proceeding.');
                    return;
                }

                if (currentQuestionIndex < totalQuestions - 1) {
                    currentQuestionIndex++;
                    showQuestion(currentQuestionIndex);
                } else {
                    showSubmissionModal();
                }
            });

            // function saveAnswer() {
            //     const question = questions[currentQuestionIndex];
            //     let answerData = {};

            //     if (question.question_type === 'SCQ' || question.question_type === 'T/F') {
            //         const selectedOption = $(`input[name="answer-${question.id}"]:checked`).val();
            //         if (selectedOption) {
            //             answerData = {
            //                 question_id: question.id,
            //                 answer_id: selectedOption,
            //                 answer_text: null
            //             };
            //         }
            //     } else if (question.question_type === 'MCQ') {
            //         const selectedOptions = [];
            //         $(`input[name="answer-${question.id}[]"]:checked`).each(function() {
            //             selectedOptions.push($(this).val());
            //         });

            //         if (selectedOptions.length > 0) {
            //             answerData = {
            //                 question_id: question.id,
            //                 answer_id: selectedOptions.join(','),
            //                 answer_text: null
            //             };
            //         }
            //     } else if (question.question_type === 'FreeText') {
            //         const answerText = $(`textarea[name="answer-text-${question.id}"]`).val().trim();
            //         if (answerText) {
            //             answerData = {
            //                 question_id: question.id,
            //                 answer_id: null,
            //                 answer_text: answerText
            //             };
            //         }
            //     }

            //     if (Object.keys(answerData).length > 0) {
            //         userAnswers[question.id] = answerData;
            //         return true;
            //     }
            //     return false;
            // }

            function saveAnswer() {
                const question = questions[currentQuestionIndex];
                const type = question.question_type.trim().toUpperCase();

                let answerData = {};

                if (type === 'SCQ' || type === 'T/F') {
                    const selectedOption = $(`input[name="answer-${question.id}"]:checked`).val();
                    if (selectedOption) {
                        answerData = {
                            question_id: question.id,
                            answer_id: selectedOption,
                            answer_text: null
                        };
                    }
                } else if (type === 'MCQ') {
                    const selectedOptions = [];
                    $(`input[name="answer-${question.id}\\[\\]"]:checked`).each(function() {
                        selectedOptions.push($(this).val());
                    });
                    if (selectedOptions.length > 0) {
                        answerData = {
                            question_id: question.id,
                            answer_id: selectedOptions.join(','),
                            answer_text: null
                        };
                    }
                } else if (type === 'FREETEXT') {
                    const answerText = $(`textarea[name="answer-text-${question.id}"]`).val().trim();
                    if (answerText) {
                        answerData = {
                            question_id: question.id,
                            answer_id: null,
                            answer_text: answerText
                        };
                    }
                }

                if (Object.keys(answerData).length > 0) {
                    userAnswers[question.id] = answerData;
                    return true;
                }
                return false;
            }

            function showSubmissionModal() {
                // Update both desktop and mobile counters to be safe
                $('#answered-count, #mobile-answered-count').text(Object.keys(userAnswers).length);
                $('#submissionMdl, #mobile-submissionMdl').modal('show');
            }

            $('#mobile-final-submit-btn').click(function() {
                submitTest();
            });
            // Initialize the test when instructions modal is hidden
            $('#mobile-testInstructionsModal').on('hidden.bs.modal', function() {
                if (testStarted) {
                    initializeTest();
                }
            });

            function submitTest() {
                // Save the current answer before submitting
                if (!saveAnswer()) {
                    if (!confirm('You have not answered the current question. Are you sure you want to submit?')) {
                        return;
                    }
                }

                clearInterval(countdownInterval);

                // First save participant data
                testParticipantData();

                // Then submit answers
                submitAnswers();
            }

            function testParticipantData() {
                const formData = {
                    user_id: {{ Auth::user()->id }},
                    training_id: {{ $training_id }},
                    course_id: {{ $courseId }},
                    test_id: {{ $trainingTest->id }},
                    _token: '{{ csrf_token() }}'
                };

                $.ajax({
                    type: 'POST',
                    url: '{{ route('training.test.participant.info') }}',
                    data: formData
                });
            }

            function submitAnswers() {
                const answersToSubmit = Object.values(userAnswers);
                let submittedCount = 0;

                function submitNext() {
                    if (submittedCount < answersToSubmit.length) {
                        const answer = answersToSubmit[submittedCount];

                        $.ajax({
                            type: 'POST',
                            url: '{{ URL('/submit-test-response') }}',
                            data: {
                                question_id: answer.question_id,
                                answer_id: answer.answer_id,
                                answer_text: answer.answer_text,
                                user_id: {{ Auth::user()->id }},
                                test_id: {{ $trainingTest->id }},
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                submittedCount++;
                                if (response.successRedirect) {
                                    window.location.href = '{{ URL('/test-already-submitted') }}';
                                    return;
                                }
                                submitNext();
                            },
                            error: function() {
                                submittedCount++;
                                submitNext();
                            }
                        });
                    } else {
                        window.location.href = '{{ route('training.test.result', $trainingTest->id) }}';
                    }
                }

                submitNext();
            }
        });


        $('.moduleBck').click(function(e) {
            e.preventDefault();
            const leaveTest = confirm('If you click OK, your test will end here. Do you want to leave the test?');
            if (leaveTest) {
                window.location.href = '/'; // change URL as needed
            } else {
                // User canceled, do nothing and stay on test
            }
        });
    </script>
</div>
