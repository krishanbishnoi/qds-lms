<div id="testQuestionsContainer">
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
                                                            value="{{ $latestAttempt->created_at->format('d - M - Y') }}">
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

                                    <div class="row">
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
                                    </div>

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

                    <div class="clickType">{{ $question->question_type }}</div>

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
