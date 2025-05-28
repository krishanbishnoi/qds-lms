@extends('front.layouts.trainee-default')
@section('content')
    <div class="dashboard-main">
        <div class="containter-fluid">
            <div class="d-flex flex-wrap justify-content-end">
                {{-- Include Sidebar --}}
                @include('front.layouts.trainee-sidebar')
                <div class="dashboard_content dashboardGu">
                    <div class="marqueeo">
                        <marquee behavior="" direction="left" onmouseover="this.stop();" onmouseout="this.start();">
                            @if ($notifications->isEmpty())
                                Live as if you were to die tomorrow. Learn as if you were to live forever.
                            @else
                                @foreach ($notifications as $notification)
                                    {{ $loop->iteration }}.{{ $notification->data['body'] }},&nbsp;&nbsp;&nbsp;
                                @endforeach
                            @endif
                        </marquee>
                    </div>
                    <div class="dashboard-cmment">
                        <div class="dashboard_head mb-md-5 d-lg-flex justify-content-between">
                            <div class="">
                                <h1 class="fs-2">Welcome, <span>{{ ucfirst(Auth::user()->fullname) }}.</span></h1>
                                <p>Hi, this is your dashboard.</p>
                            </div>
                            @if (session('message'))
                                <div class="alert alert-success">
                                    <b>{{ session('message') }}</b>
                                </div>
                            @endif
                            {{-- for ntifications file --}}
                            @include('front.layouts.notification')
                        </div>
                        <div class="continueTraining mb-3 mb-md-5">
                            <div class="d-flex align-items-center justify-content-between">
                                <h2 class="subheading">Continue {{ $sectionNameSingular }},
                                </h2>
                                <a href="{{ Request::url() }}" class="blue-text fs-6 fw-semibold"><u>My Dashboard</u></a>
                            </div>
                            <div class="training-carousel owl-carousel owl-theme">
                                @if (is_array($ongoing) || is_object($ongoing))
                                    @foreach ($ongoing as $training)
                                        <div class="item">
                                            <a href="{{ route('userTrainingDetails.index', $training->id) }}"
                                                class="trainingDetailGroup">
                                                <div class="trainingVideo">
                                                    <img src="{{ TRAINING_DOCUMENT_URL . $training->thumbnail }}"
                                                        alt="video" width="160" height="160">
                                                    <img class="videoPlay" src="{{ asset('front/img/videoPlay.svg') }}"
                                                        alt="icon">


                                                </div>
                                                <div class="trainingcontent">
                                                    <strong>{{ ucwords($training->title) }}</strong>
                                                    <h3>{{ ucwords($training->type) }}</h3>
                                                    <div class="triningbar">
                                                        <span><b class="text-dark">Number of attempts</b> :
                                                            {{ $training->number_of_attempts }}</span>
                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar" style="width: 25%"
                                                                aria-valuenow="25" aria-valuemin="0" aria-valuemax="70">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="item">
                                        <a href="javascript:void()" class="trainingDetailGroup">
                                            <div class="trainingVideo">
                                                <img src="{{ asset('front/img/not-found-1.png') }}" class="not-found-img"
                                                    alt="video" width="160" height="160">
                                            </div>
                                            <div class="trainingcontent">
                                                <strong>Training Not Assigned</strong>
                                                <h3>Training not found. Please try again after assign training.</h3>
                                                <div class="triningbar">
                                                    <span><b class="text-dark">Number of attempts</b> :
                                                        0</span>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: 25%"
                                                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="70"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="upcomingTraining  mb-3 mb-md-5">
                            <h2 class="subheading mb-3">Upcoming Training</h2>
                            <div class="row">
                                @if (is_array($upcoming) || is_object($ongoing))
                                    @foreach ($upcoming as $training)
                                        <div class="upcomingg-carousel owl-carousel owl-theme">
                                            <div class="item">
                                                <figure class="mb-2">
                                                    <img src="{{ TRAINING_DOCUMENT_URL . $training->thumbnail }}"
                                                        alt="image">
                                                </figure>
                                                <h3 class="mb-2">{{ ucwords($training->title) }}</h3>
                                                <p class="mb-2">
                                                    {!! ucfirst(implode(' ', array_slice(str_word_count($training->description, 2), 0, 7))) !!}{{ count(str_word_count($training->description, 2)) > 7 ? '...' : '' }}
                                                </p>
                                                <span>Number of attempts : <b class="text-dark">
                                                        {{ $training->number_of_attempts }}</b></span>
                                            </div>
                                            <div class="item">
                                                <figure class="mb-2">
                                                    <img src="{{ TRAINING_DOCUMENT_URL . $training->thumbnail }}"
                                                        alt="image">
                                                </figure>
                                                <h3 class="mb-2">{{ ucwords($training->title) }}</h3>
                                                <p class="mb-2">
                                                    {!! ucfirst(implode(' ', array_slice(str_word_count($training->description, 2), 0, 7))) !!}{{ count(str_word_count($training->description, 2)) > 7 ? '...' : '' }}
                                                </p>
                                                <span>Number of attempts : <b class="text-dark">
                                                        {{ $training->number_of_attempts }}</b></span>
                                            </div>
                                            <div class="item">
                                                <figure class="mb-2">
                                                    <img src="{{ TRAINING_DOCUMENT_URL . $training->thumbnail }}"
                                                        alt="image">
                                                </figure>
                                                <h3 class="mb-2">{{ ucwords($training->title) }}</h3>
                                                <p class="mb-2">
                                                    {!! ucfirst(implode(' ', array_slice(str_word_count($training->description, 2), 0, 7))) !!}{{ count(str_word_count($training->description, 2)) > 7 ? '...' : '' }}
                                                </p>
                                                <span>Number of attempts : <b class="text-dark">
                                                        {{ $training->number_of_attempts }}</b></span>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-6 col-lg-4 mb-3">
                                                                                    <figure class="mb-2">
                                                                                        <img src="{{ TRAINING_DOCUMENT_URL . $training->thumbnail }}" alt="image">
                                                                                    </figure>
                                                                                    <h3 class="mb-2">{{ ucwords($training->title) }}</h3>
                                                                                    <p class="mb-2">{!! ucfirst(implode(' ', array_slice(str_word_count($training->description, 2), 0, 7))) !!}{{ count(str_word_count($training->description, 2)) > 7 ? '...' : '' }}
                                                                                    </p>
                                                                                    <span>Number of attempts : <b class="text-dark">
                                                                                            {{ $training->number_of_attempts }}</b></span>
                                                                                </div> -->
                                    @endforeach
                                @else
                                    <div class="item">
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <figure class="mb-2 notfoundImg">
                                                <img src="{{ asset('front/img/not-found-1.png') }}" class="not-found-img"
                                                    alt="image">
                                            </figure>
                                            <h3 class="mb-2">Upcoming Training Not Found</h3>
                                            <p class="mb-2">Training not found. Please try again after assign training.
                                            </p>
                                            <span>Number of attempts : <b class="text-dark">
                                                    0</b></span>
                                        </div>
                                @endif

                            </div>
                        </div>
                        <div class="continueTraining mb-3 mb-md-5">
                            <div class="d-flex align-items-center justify-content-between">
                                <h2 class="subheading">Continue Tests,
                                </h2>
                            </div>
                            <div class="training-carousel owl-carousel owl-theme">
                                @if (is_array($ongoingTests) || is_object($ongoingTests))
                                    @foreach ($ongoingTests as $test)
                                        @php
                                            $testResult = App\Models\TestResult::where('test_id', $test->id)
                                                ->where('user_id', Auth::user()->id)
                                                ->first();
                                            $testParticipant = App\Models\TestParticipants::where('test_id', $test->id)
                                                ->where('trainee_id', Auth::user()->id)
                                                ->first();
                                            // dd($testParticipant);
                                        @endphp
                                        <div class="item">

                                            @if (
                                                !$testResult || // If $testResult is null
                                                    ($testResult->result == 'Failed' &&
                                                        $testParticipant &&
                                                        ($testParticipant->status == 0 ||
                                                            ($testParticipant->status == 1 &&
                                                                $testParticipant->number_of_attempts > $testParticipant->user_attempts))))
                                                <a href="{{ route('userTestDetails.index', $test->id) }}"
                                                    class="trainingDetailGroup">
                                                    <div class="trainingVideo">
                                                        <img src="{{ TRAINING_DOCUMENT_URL . $test->thumbnail }}"
                                                            alt="test-thumbnail" width="160" height="160">
                                                        <img class="videoPlay"
                                                            src="{{ asset('front/img/videoPlay.svg') }}" alt="icon">
                                                    </div>
                                                    <div class="trainingcontent">
                                                        <strong>{!! ucfirst(implode(' ', array_slice(str_word_count($test->description, 2), 0, 7))) !!}{{ count(str_word_count($test->description, 2)) > 7 ? '...' : '' }}
                                                        </strong>
                                                        <h3>{{ $test->title }}</h3>
                                                        <div class="triningbar">
                                                            <span><b class="text-dark">Number of attempts</b> :
                                                                {{ $test->number_of_attempts }}</span>
                                                            <div class="progress">
                                                                <div class="progress-bar" role="progressbar"
                                                                    style="width: 25%" aria-valuenow="25"
                                                                    aria-valuemin="0" aria-valuemax="70">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            @else
                                                <a href="javascript:voide()" data-bs-toggle="modal"
                                                    data-bs-target="#alreadySubmittedTest"
                                                    data-title="{{ ucFirst($test->title) }}" class="trainingDetailGroup">
                                                    <div class="trainingVideo">
                                                        <img src="{{ TRAINING_DOCUMENT_URL . $test->thumbnail }}"
                                                            alt="test-thumbnail" width="160" height="160">
                                                    </div>
                                                    <div class="trainingcontent">
                                                        <strong>{!! ucfirst(implode(' ', array_slice(str_word_count($test->description, 2), 0, 7))) !!}{{ count(str_word_count($test->description, 2)) > 7 ? '...' : '' }}
                                                        </strong>
                                                        <h3>{{ $test->title }}</h3>
                                                        <div class="triningbar">
                                                            <span><b class="text-dark">Number of attempts</b> :
                                                                {{ $test->number_of_attempts }}</span>
                                                            <div class="progress">
                                                                <div class="progress-bar" role="progressbar"
                                                                    style="width: 25%" aria-valuenow="25"
                                                                    aria-valuemin="0" aria-valuemax="70">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endif

                                        </div>
                                    @endforeach
                                @else
                                    <div class="item">
                                        <a href="javascript:void()" class="trainingDetailGroup">
                                            <div class="trainingVideo">
                                                <img src="{{ asset('front/img/not-found-1.png') }}" class="not-found-img"
                                                    alt="video" width="160" height="160">

                                            </div>
                                            <div class="trainingcontent">
                                                <strong>Test Not Assigned</strong>
                                                <h3>Test not found. Please try again after assign test.</h3>
                                                <div class="triningbar">
                                                    <span><b class="text-dark">Number of attempts</b> :
                                                        0</span>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: 25%"
                                                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="70">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="upcomingTraining">
                            <h2 class="subheading mb-3">Upcoming Tests</h2>
                            <div class="row">
                                @if (is_array($upcomingTests) || is_object($upcomingTests))
                                    @foreach ($upcomingTests as $test)
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <figure class="mb-2">
                                                <img src="{{ TRAINING_DOCUMENT_URL . $test->thumbnail }}"
                                                    alt="test-thumbnail-image">
                                            </figure>
                                            <h3 class="mb-2">{{ $test->title }}</h3>
                                            <p class="mb-2">
                                                {!! ucfirst(implode(' ', array_slice(str_word_count($test->description, 2), 0, 7))) !!}{{ count(str_word_count($test->description, 2)) > 7 ? '...' : '' }}
                                            </p>
                                            <span>Number of attempts : <b class="text-dark">
                                                    {{ $test->number_of_attempts }}</b></span>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="item">
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <figure class="mb-2 notfoundImg">
                                                <img src="{{ asset('front/img/not-found-1.png') }}" class="not-found-img"
                                                    alt="image">
                                            </figure>
                                            <h3 class="mb-2">Upcoming Test Not Found</h3>
                                            <p class="mb-2">Test not found. Please try again after assign test.</p>
                                            <span>Number of attempts : <b class="text-dark">
                                                    0</b></span>
                                        </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Already Submitted test popup Model --}}
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
                            <div class="fs-6 blue-text text-center">This Test Response Already Submitted.</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#alreadySubmittedTest').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var testName = button.data('title');
                var modal = $(this);
                modal.find('.modal-title').text(testName);
            });
        });
    </script>
@stop
