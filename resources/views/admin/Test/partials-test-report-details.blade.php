<div id="testQuestionsContainer">
    <!-- Custom CSS -->
    <style>
        .ai-score-card {
            border: 2px solid transparent;
            border-radius: 12px;
            background-image: linear-gradient(#fff, #fff),
                linear-gradient(135deg, #007bff, #6f42c1);
            background-origin: border-box;
            background-clip: content-box, border-box;
            transition: all 0.3s ease-in-out;
            cursor: pointer;
        }

        .ai-score-card:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .ai-score-card .card-title {
            font-weight: 600;
        }
    </style>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="box-header with-border pd-custom">
                        <div class="listing-btns">
                            <h4 class="box-title">View Attempt Vise Data:</h4>
                        </div>
                    </div>
                    <div class="trainingTabContent">
                        <div class="form-group">
                            @if ($test->type == 'training_test')
                                <select id="user_attempts" class="form-control">
                                    <option value="">Select Training Courses Test</option>
                                    @foreach ($testResults->sortBy('attempt_number')->values() as $index => $result)
                                        <option value="{{ $result->attempt_number }}"
                                            {{ $result->attempt_number == $latestAttempt->attempt_number ? 'selected' : '' }}>
                                            Training Test {{ $index + 1 }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <select id="user_attempts" class="form-control">
                                    <option value="">Select Attempt</option>
                                    @foreach ($testResults->sortBy('attempt_number')->values() as $index => $result)
                                        <option value="{{ $result->attempt_number }}"
                                            {{ $result->attempt_number == $latestAttempt->attempt_number ? 'selected' : '' }}>
                                            Attempt Number {{ $index + 1 }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="tab-content" id="tab-Content">
                            <div class="tab-pane fade show active" id="allTab" role="tabpanel">
                                <div class="box p-0">
                                    <div class="row">
                                        <div class="col-md-6 mt-3">
                                            <div class=" row align-items-center">
                                                <div class="col-md-3">
                                                    <label>User Name</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" readonly
                                                        value="{{ ucwords($userData->fullname) }}" placeholder="">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <div class=" row align-items-center">
                                                <div class="col-md-3">
                                                    <label>Email Id</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" readonly
                                                        value="{{ $userData->email }}" placeholder="">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <hr class="hrline">
                                    <div class="row">
                                        <div class="col-md-6 mt-3">
                                            <div class=" row align-items-center">
                                                <div class="col-md-3">
                                                    <label>Test Name</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="email" class="form-control"
                                                        placeholder="connor.spencer@qdegrees.com" readonly
                                                        value="{{ $testData->test_details->title }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <div class=" row align-items-center">
                                                <div class="col-md-3">
                                                    <label>Passing Score</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" placeholder="" readonly
                                                        value="{{ $testData->test_details->minimum_marks }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="hrline">
                                    <div class="row">
                                        {{-- @if ($test->type == 'training_test')
                                            <div class="col-md-6 mt-3">
                                                <div class=" row align-items-center">
                                                    <div class="col-md-3">
                                                        <label>Training Name</label>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <input type="email" class="form-control"
                                                            placeholder="connor.spencer@qdegrees.com" readonly
                                                            value="{{ $testData->training_details->title }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @else --}}
                                        <div class="col-md-6 mt-3">
                                            <div class=" row align-items-center">
                                                <div class="col-md-3">
                                                    <label>Test Submit Date</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="email" class="form-control"
                                                        placeholder="connor.spencer@qdegrees.com" readonly
                                                        value="{{ $latestAttempt->updated_at->format('d - M - Y H:i') }}">
                                                </div>
                                            </div>
                                        </div>
                                        {{-- @endif --}}

                                        <div class="col-md-6 mt-3">
                                            <div class=" row align-items-center">
                                                <div class="col-md-3">
                                                    <label>Obtain Score</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" placeholder="" readonly
                                                        value="{{ $latestAttempt->percentage }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="hrline">

                                    {{-- <div class="row">
                                        @if ($test->type == 'training_test')
                                            <div class="col-md-6 mt-3">
                                                <div class=" row align-items-center">
                                                    <div class="col-md-3">
                                                        <label>Test Submit Date</label>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <input type="email" class="form-control"
                                                            placeholder="connor.spencer@qdegrees.com" readonly
                                                            value="{{ $latestAttempt->created_at->format('d - M - Y') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-md-6 mt-3">
                                            <div class=" row align-items-center">
                                                <div class="col-md-3">
                                                    <label>Last Updated</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" placeholder="" readonly
                                                        value="{{ $latestAttempt->updated_at }}     ">
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <div class="mt-4">
        <h3>Test Questions</h3>
        @foreach ($testQuestions as $index => $question)
            <div class="card mt-3">
                <div class="card-body">

                    {{-- <div class="clickType">{{ $question->question_type }}</div> --}}

                    <p class="card-text"><strong>{{ $index + 1 }} . {{ $question->question }}</strong></p>

                    @if ($question->question_type == 'FreeText')
                        {{-- Free Text Answer --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="freetext-answer">
                                @php
                                    $answer = App\Model\Answer::where('question_id', $question->id)
                                        ->where('user_id', $latestAttempt->user_id)
                                        ->where('attempt_number', $attemptNumber)
                                        ->first();
                                @endphp
                                @if ($answer && $answer->free_text_answer)
                                    <strong>User Answer:</strong> {{ $answer->free_text_answer }}
                                @else
                                    <span class="text-muted">No freetext answer submitted.</span>
                                @endif
                            </p>
                            <p class="mb-0">Given Score:
                                @if ($answer && $answer->score)
                                    <strong>{{ $answer->score }}</strong>
                                @else
                                    <span class="text-muted">Not Given.</span>
                                @endif
                            </p>
                        </div>
                    @elseif ($question->question_type == 'Rating')
                        {{-- Rating Answer --}}
                        @php
                            $answer = App\Model\Answer::where('question_id', $question->id)
                                ->where('user_id', $latestAttempt->user_id)
                                ->where('attempt_number', $attemptNumber)
                                ->first();
                            $rating = $answer && $answer->free_text_answer ? (int) $answer->free_text_answer : 0;
                        @endphp
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="rating-answer mb-0">
                                <strong>User Rating:</strong>
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $rating)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-muted"></i>
                                    @endif
                                @endfor
                            </p>
                        </div>
                    @elseif ($question->question_type == 'File')
                        {{-- Rating Answer --}}
                        @php
                            $answer = App\Model\Answer::where('question_id', $question->id)
                                ->where('user_id', $latestAttempt->user_id)
                                ->where('attempt_number', $attemptNumber)
                                ->first();
                            $img = $answer && $answer->free_text_answer ? $answer->free_text_answer : '';
                        @endphp
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="rating-answer mb-0">
                                <strong>User Img:</strong>
                                <!-- Thumbnail -->
                                <img src="{{ asset($img) }}" alt="Image"
                                    style="max-width:30%; height:auto; cursor:pointer;" data-bs-toggle="modal"
                                    data-bs-target="#imageModal">

                                @php
                                    $answer = App\Model\Answer::where('question_id', $question->id)
                                        ->where('user_id', $latestAttempt->user_id)
                                        ->where('attempt_number', $attemptNumber)
                                        ->first();
                                    $img = $answer && $answer->free_text_answer ? $answer->free_text_answer : '';

                                    $ruleMap = [
                                        'Take a photo of a croissant placed correctly on the baking tray, making sure your hand is visible while holding it.' =>
                                            'rule_food_hygiene_gloves',
                                        'After baking, check the internal temperature of the croissant. It should be above 40°C (French Butter) or above 45°C (Frangipane Almond).  Take a photo showing the thermometer reading inside the croissant.' =>
                                            'rule_food_temperature_check',
                                    ];
                                    $mappedRuleId = $ruleMap[trim($question->question)] ?? null;
                                @endphp

                            <div class="card ai-score-card mt-3" style="max-width: 30%;"
                                data-question-id="{{ $question->id }}" data-rule-id="{{ $mappedRuleId }}"
                                data-img="{{ asset($img) }}">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="bi bi-cpu text-primary fs-4 me-2"></i>
                                        <h5 class="card-title mb-0">AI Matcher Score</h5>
                                    </div>
                                    <p id="aiScore-{{ $question->id }}" class="mb-4 fs-5 fw-bold text-success">
                                        Loading...</p>

                                    {{-- <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-chat-left-text text-info fs-5 me-2"></i>
                                        <h6 class="card-subtitle mb-0">Remark</h6>
                                    </div>
                                    <p id="aiRemark-{{ $question->id }}" class="mt-2 mb-0 text-muted">Loading...</p> --}}
                                </div>
                            </div>

                            </p>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">

                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <h5 class="modal-title">User Answer Image</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <!-- Modal Body -->
                                    <div class="modal-body text-center">
                                        <img src="{{ asset($img) }}" alt="Full Image" class="img-fluid rounded">
                                    </div>

                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Multiple Choice Options --}}
                        <ul class="que_options">
                            @foreach ($question->questionAttributes as $option)
                                <li>
                                    {{ $option->option }}
                                    @if ($option->is_correct)
                                        <i class="fas fa-check text-green isCorrect_option" style="color:green;"></i>
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                        <div class="d-flex justify-content-between align-items-center">
                            <p class="mt-3"><b>User Answer:</b>
                                @if (isset($userAnswers[$question->id]))
                                    @php
                                        // Convert user answer IDs from string to integer
                                        $userAnswerIds = array_map('intval', explode(',', $userAnswers[$question->id]));

                                        // Fetch correct answers for the current question (already integers)
                                        $correctAnswerIds = $question->questionAttributes
                                            ->where('is_correct', 1)
                                            ->pluck('id')
                                            ->toArray();

                                        // Sort both arrays to ensure order doesn't affect comparison
sort($userAnswerIds);
sort($correctAnswerIds);
// Check if the user's answers match the correct answers
                                        $isCorrect = $userAnswerIds == $correctAnswerIds;
                                    @endphp

                                    {{-- Display the user's selected answers --}}
                                    @foreach ($question->questionAttributes as $option)
                                        @if (in_array($option->id, $userAnswerIds))
                                            <span
                                                class="d-inline-block border rounded-1 py-1 px-3 mx-1">{{ $option->option }}</span>
                                        @endif
                                    @endforeach
                                @else
                                    <span class="d-inline-block border rounded-1 py-1 px-3 mx-1">Not answered</span>
                                @endif
                            </p>

                            <p class="mb-0">Status:
                                @if (isset($userAnswers[$question->id]))
                                    @if ($isCorrect)
                                        <span class="badge badge-success">Correct</span>
                                    @else
                                        <span class="badge badge-danger">Incorrect</span>
                                    @endif
                                @else
                                    <span class="badge badge-danger">Not answered/saved</span>
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const fileCards = document.querySelectorAll('.ai-score-card');

        fileCards.forEach(card => {
            const questionId = card.getAttribute('data-question-id');
            const ruleId = card.getAttribute('data-rule-id');
            const imageUrl = card.getAttribute('data-img');

            if (!imageUrl || !ruleId) return;

            axios.post('{{ route('ai.score') }}', {
                    image_url: imageUrl,
                    rule_id: ruleId
                })
                .then(function(response) {
                    console.log('AI Score Response:', response.data);

                    const scoreEl = document.getElementById('aiScore-' + questionId);
                    const remarkEl = document.getElementById('aiRemark-' + questionId);

                    let confidence = response.data.confidence;
                    // format confidence to 2 decimal places
                    if (confidence !== null && confidence !== undefined) {
                        confidence = parseFloat(confidence).toFixed(2);
                    } else {
                        confidence = 'N/A';
                    }

                    if (scoreEl) scoreEl.innerText = confidence + '%';
                    if (remarkEl) remarkEl.innerText = response.data.reason ??
                        'No remarks available';
                })


                // .then(function(response) {
                //     // console.log(response);

                //     // document.getElementById('aiScore-' + questionId).innerText =
                //     //     (response.data.confidence ?? 'N/A') + '%';
                //     // document.getElementById('aiRemark-' + questionId).innerText =
                //     //     response.data.reason ?? 'No remarks available';
                // })
                .catch(function(error) {
                    console.error(error);
                    document.getElementById('aiScore-' + questionId).innerText = 'Error';
                    document.getElementById('aiRemark-' + questionId).innerText =
                        'Failed to fetch AI result';
                });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#user_attempts').change(function() {
            var attemptNumber = $(this).val();
            var userId = "{{ $userData->id }}";
            var testId = "{{ $testData->test_id }}";

            $.ajax({
                url: "{{ route('test.wise.report', ['user_id' => $userData->id, 'test_id' => $testData->test_id]) }}",
                method: "GET",
                data: {
                    attempt_number: attemptNumber
                },
                success: function(response) {
                    $('#testQuestionsContainer').html(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });
        });
    });
</script>
