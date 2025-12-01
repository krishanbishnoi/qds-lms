<div class="mobileScren">
    <style>
        .rating-star {
            font-size: 28px;
            color: #ccc;
            /* default empty star */
            transition: color 0.2s;
        }

        .rating-star.checked-star {
            color: #ffc107;
            /* filled star */
        }
    </style>

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
                            <li><b>5. All Questions Mandatory</b></li>
                            <li><b>6. No Negative Marking</b></li>
                            <li><b>7. Stable Internet Required</b></li>
                            <li><b>8. Stay on Test Page</b></li>
                            <li><b>9. Do Not Refresh</b></li>
                            <li><b>10. Webcam & Mic Must Stay On</b></li>
                            <li><b>11. No Mobile Devices</b></li>
                            <li><b>12. No External Help</b></li>
                            <li><b>13. AI Monitoring Active</b></li>
                            <li><b>14. Violations = Auto Submit</b></li>
                            <li><b>15. Results Reviewed Before Finalization</b></li>
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
                                <input class="form-check-input" type="checkbox" required value=""
                                    id="mobile-checkDefault">
                                <label class="form-check-label" for="mobile-checkDefault" id="mobile-checkDefaultBtn">
                                    I've Read and Start test
                                </label>
                            </div>
                        </ul>
                    </div>
                </div>

                <div class="modal-footer" id="mobile-startTestBtnWrapper">
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
                        {{-- <strong class="mb-3">Questions Answered: <span
                                id="answered-count">0</span>/{{ count($trainingQuestions) }}</strong> --}}
                        {{-- <strong>Time Remaining: <span id="time-remaining-display">0m 0s</span></strong> --}}
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
        <a href="{{ url()->previous() }}" class="moduleBck d-md-none mb-3 d-block">
            <img src="https://lms.qdegrees.com/front/img/back-button.png" alt="" width="50" class="me-2">
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

        .highlight-checkbox {
            outline: 2px solid red;
            outline-offset: 2px;
            box-shadow: 0 0 5px red;
            animation: shake 0.3s ease-in-out 0s 2;
        }

        /* Shake animation keyframes */
        @keyframes shake {
            0% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-4px);
            }

            50% {
                transform: translateX(4px);
            }

            75% {
                transform: translateX(-4px);
            }

            100% {
                transform: translateX(0);
            }
        }
    </style>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            // Enable checkbox to start test
            $('#mobile-checkDefault').change(function() {
                $('#mobile-startTestBtn').prop('disabled', !this.checked);
            });

            $('#mobile-startTestBtnWrapper').click(function() {
                if (!$('#mobile-checkDefault').is(':checked')) {
                    const checkbox = $('#mobile-checkDefault');
                    checkbox.addClass('highlight-checkbox');
                    checkbox[0].scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    setTimeout(function() {
                        checkbox.removeClass('highlight-checkbox');
                    }, 1500);
                }
            });

            // Show mobile instructions modal
            const mobileTestInstructionsModal = new bootstrap.Modal(document.getElementById(
                'mobile-testInstructionsModal'));
            mobileTestInstructionsModal.show();

            const questions = {!! json_encode($trainingQuestions) !!};
            const totalQuestions = questions.length;
            let currentQuestionIndex = 0;
            let userAnswers = {};
            let testStarted = false;
            let countdownInterval;

            // Start test
            $('#mobile-startTestBtn').click(function() {
                mobileTestInstructionsModal.hide();
                $('.questionsScrn').show();
                testStarted = true;
                startTimer();
                showQuestion(currentQuestionIndex);
            });

            function showQuestion(index) {
                const question = questions[index];
                $('#current-question-number').text(index + 1);
                $('#question-title').text(`Question: ${index + 1}`);
                $('#question-text').text(question.question);

                const progress = ((index + 1) / totalQuestions) * 100;
                $('#test-progress').css('width', `${progress}%`).attr('aria-valuenow', progress);

                const $optionsList = $('#question-options').empty();

                if (question.question_type === 'SCQ' || question.question_type === 'T/F') {
                    question.question_attributes.forEach(option => {
                        const isChecked = userAnswers[question.id] &&
                            (userAnswers[question.id].answer_id == option.id ||
                                (Array.isArray(userAnswers[question.id].answer_id) &&
                                    userAnswers[question.id].answer_id.includes(option.id.toString())));

                        $optionsList.append(`
                    <li>
                        <input type="radio" id="option-${question.id}-${option.id}" name="answer-${question.id}" value="${option.id}" ${isChecked ? 'checked' : ''}>
                        <label for="option-${question.id}-${option.id}">${option.option}</label>
                    </li>
                `);
                    });

                } else if (question.question_type === 'MCQ') {
                    question.question_attributes.forEach(option => {
                        const isChecked = userAnswers[question.id] &&
                            userAnswers[question.id].answer_id &&
                            userAnswers[question.id].answer_id.split(',').includes(option.id.toString());

                        $optionsList.append(`
                    <li>
                        <input type="checkbox" id="option-${question.id}-${option.id}" name="answer-${question.id}[]" value="${option.id}" ${isChecked ? 'checked' : ''}>
                        <label for="option-${question.id}-${option.id}">${option.option}</label>
                    </li>
                `);
                    });

                } else if (question.question_type === 'FreeText') {
                    const answerText = userAnswers[question.id] ? userAnswers[question.id].answer_text : '';
                    $optionsList.append(`
                <li class="free-text-item">
                    <textarea class="form-control" name="answer-text-${question.id}" rows="4" maxlength="150">${answerText}</textarea>
                    <div class="wordcounter text-end">${answerText.length}/150</div>
                </li>
            `);

                    $(`textarea[name="answer-text-${question.id}"]`).on('input', function() {
                        $(this).siblings('.wordcounter').text(`${$(this).val().length}/150`);
                    });

                } else if (question.question_type === 'File') {
                    // File capture HTML
                    $optionsList.append(`
                        <li class="file-question-item">
                            <video id="camera-${question.id}" autoplay playsinline class="w-100 rounded shadow mb-2"></video>
                            <canvas id="snapshot-${question.id}" style="display:none;"></canvas>
                            <button type="button" class="btn btn-sm btn-primary mb-2" id="capture-${question.id}">Capture Photo</button>
                            <input type="hidden" id="file-input-${question.id}">
                            <div id="preview-${question.id}"></div>
                        </li>
                    `);

                    startCamera(question.id);

                    $(`#capture-${question.id}`).click(function() {
                        takePhoto(question.id);
                    });
                } else if (question.question_type === 'Rating') {
                    const savedRating = userAnswers[question.id] ? userAnswers[question.id].answer_id : 0;

                    let starsHtml = '<li class="rating-question-item d-flex gap-1">';
                    for (let i = 1; i <= 5; i++) {
                        const filled = i <= savedRating ? 'checked-star' : '';
                        starsHtml += `
            <i class="fa fa-star rating-star ${filled}"
               data-value="${i}"
               data-question="${question.id}"
               style="font-size: 28px; cursor: pointer; color: ${filled ? '#ffc107' : '#ccc'};"></i>`;
                    }
                    starsHtml += '</li>';

                    $optionsList.append(starsHtml);

                    // Click handler for stars
                    $(`.rating-star[data-question="${question.id}"]`).off('click').on('click', function() {
                        const ratingValue = $(this).data('value');
                        $(`.rating-star[data-question="${question.id}"]`).each(function() {
                            $(this).css('color', $(this).data('value') <= ratingValue ? '#ffc107' :
                                '#ccc');
                        });
                        userAnswers[question.id] = {
                            question_id: question.id,
                            answer_id: ratingValue,
                            answer_text: null
                        };
                    });
                }


                $('#next-btn').text(index === totalQuestions - 1 ? 'Submit Test' : 'Submit & Next');
                $('#prev-btn').prop('disabled', index === 0);
            }

            function startCamera(questionId) {
                const video = document.getElementById(`camera-${questionId}`);
                navigator.mediaDevices.getUserMedia({
                        video: true
                    })
                    .then(stream => video.srcObject = stream)
                    .catch(err => alert("Camera access denied: " + err.message));
            }

            function takePhoto(questionId) {
                const video = document.getElementById(`camera-${questionId}`);
                const canvas = document.getElementById(`snapshot-${questionId}`);
                const input = document.getElementById(`file-input-${questionId}`);
                const preview = document.getElementById(`preview-${questionId}`);

                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                canvas.getContext("2d").drawImage(video, 0, 0);

                const dataUrl = canvas.toDataURL("image/png");
                input.value = dataUrl;
                userAnswers[questionId] = {
                    question_id: questionId,
                    answer_id: null,
                    answer_text: dataUrl
                };
                preview.innerHTML = `<img src="${dataUrl}" class="img-fluid rounded">`;
            }

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

            $('#prev-btn').click(function() {
                if (currentQuestionIndex > 0) {
                    saveAnswer();
                    currentQuestionIndex--;
                    showQuestion(currentQuestionIndex);
                }
            });

            function saveAnswer() {
                const question = questions[currentQuestionIndex];
                let answerData = {};

                if (question.question_type === 'SCQ' || question.question_type === 'T/F') {
                    const selectedOption = $(`input[name="answer-${question.id}"]:checked`).val();
                    if (selectedOption) answerData = {
                        question_id: question.id,
                        answer_id: selectedOption,
                        answer_text: null
                    };

                } else if (question.question_type === 'MCQ') {
                    const selectedOptions = [];
                    $(`input[name="answer-${question.id}\\[\\]"]:checked`).each(function() {
                        selectedOptions.push($(this).val());
                    });
                    if (selectedOptions.length > 0) answerData = {
                        question_id: question.id,
                        answer_id: selectedOptions.join(','),
                        answer_text: null
                    };

                } else if (question.question_type === 'FreeText') {
                    const answerText = $(`textarea[name="answer-text-${question.id}"]`).val().trim();
                    if (answerText) answerData = {
                        question_id: question.id,
                        answer_id: null,
                        answer_text: answerText
                    };

                } else if (question.question_type === 'File') {
                    const fileData = document.getElementById(`file-input-${question.id}`).value;
                    if (fileData) answerData = {
                        question_id: question.id,
                        answer_id: null,
                        answer_text: fileData
                    };
                } else if (question.question_type === 'Rating') {
                    const saved = userAnswers[question.id];
                    if (saved && saved.answer_id) answerData = {
                        question_id: question.id,
                        answer_id: null,
                        answer_text: saved.answer_id
                    };
                }

                if (Object.keys(answerData).length > 0) {
                    userAnswers[question.id] = answerData;
                    return true;
                }
                return false;
            }

            function showSubmissionModal() {
                $('#mobile-submissionMdl').modal('show');
                $('#mobile-answered-count').text(Object.keys(userAnswers).length);
            }

            $('#mobile-final-submit-btn').click(submitTest);

            function submitTest() {
                if (!saveAnswer()) {
                    if (!confirm('You have not answered the current question. Are you sure you want to submit?'))
                        return;
                }
                clearInterval(countdownInterval);
                testParticipantData();
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
                $.post('{{ route('training.test.participant.info') }}', formData);
            }

            function submitAnswers() {
                const answersToSubmit = Object.values(userAnswers);
                let submittedCount = 0;

                function submitNext() {
                    if (submittedCount < answersToSubmit.length) {
                        const answer = answersToSubmit[submittedCount];
                        $.post('{{ URL('/submit-test-response') }}', {
                            question_id: answer.question_id,
                            answer_id: answer.answer_id,
                            answer_text: answer.answer_text,
                            user_id: {{ Auth::user()->id }},
                            test_id: {{ $trainingTest->id }},
                            _token: '{{ csrf_token() }}'
                        }, function(response) {
                            submittedCount++;
                            if (response.successRedirect) {
                                window.location.href = '{{ URL('/test-already-submitted') }}';
                                return;
                            }
                            submitNext();
                        }).fail(function() {
                            submittedCount++;
                            submitNext();
                        });
                    } else {
                        window.location.href = '{{ route('training.test.result', $trainingTest->id) }}';
                    }
                }

                submitNext();
            }

            function startTimer() {
                const countdownElement = $('#countdown');
                let countdown = {{ $testDetails->time_of_test }} * 60;

                function updateDisplay(sec) {
                    const min = Math.floor(sec / 60);
                    const s = sec % 60;
                    $('#time-remaining-display').text(`${min}m ${s}s`);
                }

                updateDisplay(countdown);

                countdownInterval = setInterval(function() {
                    const min = Math.floor(countdown / 60);
                    const sec = countdown % 60;
                    countdownElement.text(`${min < 10 ? '0' + min : min}m ${sec < 10 ? '0' + sec : sec}s`);

                    if (countdown <= 0) {
                        clearInterval(countdownInterval);
                        submitTest();
                    }
                    countdown--;
                }, 1000);
            }
        });
    </script>


</div>
