 @extends('front.layouts.trainee-default')
 @section('content')
     <?php
     // $startTime = strtotime($testDetails->start_date_time);
     $endTime = strtotime($testDetails->end_date_time); // get taining end dateTime
     $currentDateTime = date('Y-m-d H:i:s'); //get current end dateTime
     $currentTimestamp = strtotime($currentDateTime);
     $difference = $endTime - $currentTimestamp;
     $hours = floor($difference / (60 * 60));
     $minutes = floor(($difference - $hours * 60 * 60) / 60);
     $trainingId = request()->route('id');
     ?>

     <body>
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
                                     <circle id="Ellipse_589" data-name="Ellipse 589" cx="0.821" cy="0.821"
                                         r="0.821" transform="translate(17.163 13.017)" fill="currentColor" />
                                     <circle id="Ellipse_590" data-name="Ellipse 590" cx="1.076" cy="1.076"
                                         r="1.076" transform="translate(16.908 15.506)" fill="currentColor" />
                                 </g>
                             </svg>
                         </div>
                         <span id="countdown"></span>
                     </div>
                     <div class="courseProgress">
                         {{-- <div class="progress-main">
                             <div class="progress-circle" data-progress="90"></div>
                         </div> --}}
                         {{-- <div class="progressText">
                             <strong>Progress</strong>
                             <span>1<i>/4</i></span>
                         </div> --}}
                         <button type="button" class="optionBtn d-lg-none"><img src="../front/img/option.svg"
                                 alt="icon" width="23" height="23"></button>
                         <a href="{{ route('front.dashboard') }}"><button type="button" class="exitBtn"><img
                                     src="../front/img/exit.svg" alt="icon" width="23" height="23"></button></a>
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
                         {{-- @csrf --}}
                         <div class="testid d-flex justify-content-between">
                             <h3>Test For Training : {{ $testDetails->title }}</h3>
                             {{-- <span class="questionNum">1<i>/3</i></span> --}}
                             <input type="hidden" name="test_id" value="{{ $testDetails->id }}">
                         </div>
                         @if (is_array($testQuestions) || is_object($testQuestions))
                             @foreach ($testQuestions as $index => $question)
                                 @if ($question->question_type == 'SCQ')
                                     <div class="testQuestion">
                                         <div class="clickType">Single Choice Question</div>
                                         <h4>{{ $index + 1 }}. {{ $question->question }}</h4>
                                         <div class="ansCheck">
                                             @foreach ($question->questionAttributes as $option)
                                                 <span>
                                                     <input type="radio" id="radio{{ $option->id }}"
                                                         name="answer_id-{{ $question->id }}" value="{{ $option->id }}"
                                                         data-question-id="{{ $question->id }}" class="answer-checkbox">
                                                     <label for="radio{{ $option->id }}">{{ $option->option }}</label>
                                                 </span>
                                                 {{-- <input type="hidden" name="option_id" value="{{ $option->id }}"> --}}
                                             @endforeach
                                         </div>
                                     </div>
                                 @elseif($question->question_type == 'MCQ')
                                     <div class="testQuestion">
                                         <div class="clickType">Multiple Choice Question</div>
                                         <h4>{{ $index + 1 }}. {{ $question->question }}</h4>
                                         <input type="hidden" name="question_id" value="{{ $question->id }}">
                                         <div class="ansCheck">
                                             @foreach ($question->questionAttributes as $option)
                                                 <span>
                                                     <input type="checkbox" id="checkbox{{ $option->id }}"
                                                         name="answer_id-{{ $question->id }}" value="{{ $option->id }}"
                                                         data-question-id="{{ $question->id }}" class="answer-radio">
                                                     <label
                                                         for="checkbox{{ $option->id }}">{{ $option->option }}</label>
                                                     {{-- <input type="hidden" name="option_id" value="{{ $option->id }}"  data-question-id="{{ $question->id }}"> --}}
                                                 </span>
                                             @endforeach
                                         </div>
                                     </div>
                                 @elseif($question->question_type == 'T/F')
                                     <div class="testQuestion">
                                         <div class="clickType">True False Question</div>
                                         <h4>{{ $index + 1 }}. {{ $question->question }}</h4>
                                         <input type="hidden" name="question_id" value="{{ $question->id }}">
                                         <div class="ansCheck">
                                             @foreach ($question->questionAttributes as $option)
                                                 <span>
                                                     <input type="radio" id="radio{{ $option->id }}"
                                                         name="answer_id-{{ $question->id }}" value="{{ $option->id }}"
                                                         data-question-id="{{ $question->id }}" class="answer-radio">
                                                     <label for="radio{{ $option->id }}">{{ $option->option }}</label>
                                                     {{-- <input type="hidden" name="option_id" value="{{ $option->id }}"  data-question-id="{{ $question->id }}"> --}}
                                                 </span>
                                             @endforeach
                                         </div>
                                     </div>
                                 @elseif($question->question_type == 'FreeText')
                                     <div class="testQuestion">
                                         <div class="clickType">Free Text</div>
                                         <h4>{{ $index + 1 }}. {{ $question->question }}</h4>

                                         <div class="ansCheck pb-3">
                                             <textarea class="form-control free-text-input" data-question-id="{{ $question->id }}"></textarea>
                                             <div class="wordcounter w-100">0/150</div>
                                         </div>
                                     </div>
                                 @endif
                             @endforeach
                         @endif
                         <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                         <div class="d-flex mt-2">
                             <a href="javascript:voide()" data-bs-toggle="modal" data-bs-target="#confirmSubmit"><button
                                     style="display: none" type="button"
                                     class="btn btn-secondary smallBtn  py-2 px-4 view-result-button">Submit</button></a>
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
                                 <p>{!! $testDetails->description !!}</p>
                             </a>
                         </li>
                     </ul>
                 </div>
             </div>
         </div>
         <div class="overllayBg" id="overllayBg" style="display: none;"></div>
         {{-- Varify Result Model --}}
         <div class="modal fade " id="confirmSubmit" tabindex="-1" aria-hidden="true">
             <div class="modal-dialog modal-dialog-centered">
                 <div class="modal-content">
                     <div class="modal-header">
                         <h3 class="modal-title">Confirm ?</h3>
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                     </div>
                     <div class="modal-body">
                         <div class="selectUser">
                             <div class="d-sm-flex justify-content-between">
                                 <div class="fs-6 blue-text">Are you sure you want to Submit? Once submitted, you cannot
                                     change your answers.
                                 </div>
                             </div>
                             <hr>
                             <div class="d-sm-flex justify-content-between">
                                 <button type="button"data-bs-dismiss="modal" aria-label="Close"
                                     class="btn btn-secondary smallBtn  py-2 px-4">Cancel</button>
                                 <div class="d-flex mt-2">
                                     <button type="button"
                                         class="btn btn-secondary smallBtn  py-2 px-4 view-result-button"
                                         onclick="openTestResultPage()">Confirm Submit</button>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
  
         <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
         <?php
         $totalQuestions = count($testQuestions);
         ?>
         <script>
             // Submit test question answer in answer table Script
             $(document).ready(function() {
                 var answeredQuestions = [];

                 function submitAnswer(questionId, answerId, answerText = null) {
                     var userId = {{ Auth::user()->id }};
                     var testId = {{ $testDetails->id }};
                     var formData = {
                         question_id: questionId,
                         answer_id: answerId,
                         answer_text: answerText,
                         user_id: userId,
                         test_id: testId,
                         _token: '{{ csrf_token() }}' // Add the CSRF token here
                     };

                     $.ajaxSetup({
                         headers: {
                             'X-CSRF-TOKEN': formData._token
                         }
                     });

                     $.ajax({
                         type: 'POST',
                         url: '{{ URL('/submit-test-response') }}',
                         data: formData,
                         success: function(response) {
                             console.log('Response submitted successfully', response);
                             if (response.successRedirect) {
                                 window.location.href = '{{ URL('/test-already-submitted') }}';
                             }
                         },
                         error: function(jqXHR, textStatus, errorThrown) {
                             console.error('Error submitting response:', errorThrown);
                             alert(
                                 'Error submitting this response. Please check your internet connection and re-click on the same question option.');
                         }
                     });
                 }

                 $('.answer-radio, .answer-checkbox').on('click', function() {
                     var questionId = $(this).data('question-id');
                     var answerId = $(this).val();
                     if (!answeredQuestions.includes(questionId)) {
                         answeredQuestions.push(questionId);
                     }
                     // Enable or disable the "View Result" button based on the number of answered questions
                     var totalQuestions = {{ $totalQuestions }};
                     var viewResultButton = $('.view-result-button');
                     if (answeredQuestions.length === totalQuestions) {
                         viewResultButton.show();
                     } else {
                         viewResultButton.hide();
                     }

                     submitAnswer(questionId, answerId);
                 });

                 // for FreeText questions
                 $('.free-text-input').on('input', function() {
                     var questionId = $(this).data('question-id');
                     var answerText = $(this).val();

                     if (!answeredQuestions.includes(questionId)) {
                         answeredQuestions.push(questionId);
                     }

                     var totalQuestions = {{ $totalQuestions }};
                     var viewResultButton = $('.view-result-button');

                     if (answeredQuestions.length === totalQuestions) {
                         viewResultButton.show();
                     } else {
                         viewResultButton.hide();
                     }

                     submitAnswer(questionId, null, answerText);
                 });
             });



             //  // Show Test result in new page Script
             function openTestResultPage() {
                 window.location.href = '{{ route('user.test.result', $testDetails->id) }}';
             }

             // Time Countdown view for $testDetails->time_of_test
             $(document).ready(function() {
                 var countdownElement = $('#countdown');
                 var timerImgElement = $('.timerImg');
                 var countdown = {{ $testDetails->time_of_test }} * 60;
                 var timer = setInterval(function() {
                     var minutes = Math.floor(countdown / 60);
                     var seconds = countdown % 60;

                     var formattedMinutes = (minutes < 10) ? '0' + minutes : minutes;
                     var formattedSeconds = (seconds < 10) ? '0' + seconds : seconds;

                     countdownElement.text(formattedMinutes + 'm ' + formattedSeconds + 's');

                     if (countdown <= 0) {
                         clearInterval(timer);

                         // Update the test_participants status in test_participants table
                         var testId = {{ $testDetails->id }};
                         $.ajax({
                             type: 'POST',
                             url: '{{ route('update.test.participant.status.attempts') }}',
                             data: {
                                 test_id: testId,
                                 _token: '{{ csrf_token() }}'
                             },
                             success: function(response) {
                                 console.log('Test status updated successfully');
                                 //  window.location.href = '{{ route('front.dashboard') }}';
                                 window.location.href = '{{ route('user.test.result', $testDetails->id) }}';
                                },
                             error: function(jqXHR, textStatus, errorThrown) {
                                 console.error('Error updating test status:', errorThrown);
                             }
                         });
                     } else if (countdown <= 300) { // 5 minutes or less (300 seconds)
                         countdownElement.addClass('blinking');
                         timerImgElement.addClass('redBorder');
                     } else {
                         countdownElement.removeClass('blinking');
                         timerImgElement.removeClass('redBorder');
                     }

                     countdown--;
                 }, 1000); // Update the timer every 1 second
             });
         </script>
     @stop
