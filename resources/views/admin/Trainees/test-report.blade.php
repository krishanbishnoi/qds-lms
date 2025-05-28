@extends('admin.layouts.default')
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h1>
                Test Details
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i>
                            Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">User Test Detail's</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="box-header with-border pd-custom">
                            <div class="listing-btns">
                                <h3 class="box-title">User Detail's</h3>
                            </div>
                        </div>
                        <div class="trainingTabContent">

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
                                            <div class="col-md-6 mt-3">
                                                <div class=" row align-items-center">
                                                    <div class="col-md-3">
                                                        <label>Test Submit Date</label>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <input type="email" class="form-control"
                                                            placeholder="connor.spencer@qdegrees.com" readonly
                                                            value="{{ $testResult->created_at->format('d - M - Y') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <div class=" row align-items-center">
                                                    <div class="col-md-3">
                                                        <label>Obtaine Score</label>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control" placeholder="" readonly
                                                            value="{{ $testResult->percentage }}%">
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
                        <h5 class="card-title">Q. {{ $index + 1 }}</h5>
                        <p class="card-text"><strong>{{ $question->question }}</strong></p>
                        <ul class="que_options">
                            @foreach ($question->questionAttributes as $option)
                                <li>
                                    {{ $option->option }}
                                    @if ($option->is_correct)
                                        <i class="fas fa-check text-white isCorrect_option"></i>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="mt-3"><b>User's Answer</b>:
                                @if (isset($userAnswers[$question->id]))
                                    @php
                                        $userAnswerIds = explode(',', $userAnswers[$question->id]);
                                    @endphp
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
                                    @php
                                        $correctAnswerIds = explode(
                                            ',',
                                            $question->questionAttributes
                                                ->where('is_correct', 1)
                                                ->pluck('id')
                                                ->implode(','),
                                        );
                                        $isCorrect = count(array_intersect($userAnswerIds, $correctAnswerIds)) == count($correctAnswerIds);
                                    @endphp
                                    <span
                                        class="badge {{ $isCorrect ? 'badge-success' : 'badge-danger' }}">{{ $isCorrect ? 'Correct' : 'Incorrect' }}</span>
                                @else
                                    <span class="badge badge-danger">Not answered</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- @foreach ($testQuestions as $index => $question)
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title">Q. {{ $index + 1 }}</h5>
                        <p class="card-text"><strong>{{ $question->question }}</strong></p>
                        <ul class="que_options">
                            @foreach ($question->questionAttributes as $option)
                                <li>{{ $option->option }}</li>
                            @endforeach
                        </ul>
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="mt-3"><b>User's Answer</b>:
                                @if (isset($userAnswers[$question->id]))
                                    @foreach ($question->questionAttributes as $option)
                                        @if ($option->id == $userAnswers[$question->id])
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
                                    @php
                                        $isCorrect = $question->questionAttributes->where('id', $userAnswers[$question->id])->first()->is_correct ?? false;
                                    @endphp
                                    <span
                                        class="badge {{ $isCorrect ? 'badge-success' : 'badge-danger' }}">{{ $isCorrect ? 'Correct' : 'Incorrect' }}</span>
                                @else
                                    <span class="badge badge-danger">Not answered</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach --}}

            {{-- If you want to view A,B,C,D .... infront of Options --}}
            {{-- @foreach ($testQuestions as $index => $question)
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title">Q. {{ $index + 1 }}</h5>
                        <p class="card-text"><strong>{{ $question->question }}</strong></p>
                        <ul class="que_options">
                            @php
                                $optionsMapping = ['A', 'B', 'C', 'D', 'E', 'F']; // Add more as needed
                            @endphp
                            @foreach ($question->questionAttributes as $key => $option)
                                <li>{{ $optionsMapping[$key] }}. {{ $option->option }}</li>
                            @endforeach
                        </ul>
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="mt-3"><b>User's Answer</b>:
                                @if (isset($userAnswers[$question->id]))
                                    @php
                                        $userAnswerIndex = array_search($userAnswers[$question->id], array_column($question->questionAttributes->toArray(), 'id'));
                                    @endphp
                                    <span
                                        class="d-inline-block border rounded-1 py-1 px-3 mx-1">{{ $optionsMapping[$userAnswerIndex] }}</span>
                                @else
                                    <span class="d-inline-block border rounded-1 py-1 px-3 mx-1">Not answered</span>
                                @endif

                            </p>
                            <p class="mb-0">Status:
                                @if (isset($userAnswers[$question->id]))
                                    @php
                                        $isCorrect = $question->questionAttributes[$userAnswerIndex]->is_correct ?? false;
                                    @endphp
                                    <span
                                        class="badge {{ $isCorrect ? 'badge-success' : 'badge-danger' }}">{{ $isCorrect ? 'Correct' : 'Incorrect' }}</span>
                                @else
                                    <span class="badge badge-danger">Not answered</span>
                                @endif

                            </p>
                        </div>
                    </div>
                </div>
            @endforeach --}}
        </div>
    </div>





@stop
