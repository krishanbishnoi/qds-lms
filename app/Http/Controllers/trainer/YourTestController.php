<?php
namespace App\Http\Controllers\trainer;

use App\Http\Controllers\BaseController;
use App\Model\Answer;
use App\Model\Question;
use App\Model\QuestionAttribute;
use App\Model\Test;
use App\Model\TestParticipants;
use App\Model\TestResult;
use App\Model\User;
use App\Model\UserAssignedTestQuestion;
use Auth;
use DB;
use Illuminate\Http\Request;
use PDF;
use Redirect;
use View;

/**
 * TestController Controller
 *
 * Add your methods in the class below
 */
class YourTestController extends BaseController
{
    public $model = 'YourTest';

    public $sectionName = 'My Test';

    public $sectionNameSingular = 'My Test';

    public function __construct()
    {
        parent::__construct();
        View::share('modelName', $this->model);
        View::share('sectionName', $this->sectionName);
        View::share('sectionNameSingular', $this->sectionNameSingular);
    }

    /**
     * Function for display page for edit area
     *
     * @param  $modelId  id  of area
     * @return view page.
     */
    public function userTests()
    {
        $userId    = Auth::id();
        $myTestIds = TestParticipants::where('trainee_id', $userId)->pluck('test_id')->toArray();
        // dd($myTestIds);
        //   echo '<pre>'; print_r($myTestIds); die;
        if (! empty($myTestIds)) {
            $ongoing        = Test::whereIn('tests.id', $myTestIds)->where('tests.status', 0)->where('tests.type', 'regular_test')->join('users', 'users.id', '=', 'tests.user_id')->select('tests.*', 'users.first_name as created_by')->orderBy('tests.id', 'DESC')->get();
            $upcoming       = Test::whereIn('tests.id', $myTestIds)->where('tests.status', 1)->where('tests.type', 'regular_test')->leftJoin('users', 'users.id', '=', 'tests.user_id')->join('test_participants', 'test_participants.test_id', '=', 'tests.id')->where('test_participants.trainee_id', $userId)->where('test_participants.status', 0)->select('tests.*', 'users.first_name as created_by')->orderBy('tests.id', 'DESC')->get();
            $completedTests = Test::whereIn('tests.id', $myTestIds)->leftJoin('users', 'users.id', '=', 'tests.user_id')->where('tests.type', 'regular_test')->join('test_participants', 'test_participants.test_id', '=', 'tests.id')->where('test_participants.trainee_id', $userId)->where('test_participants.status', 1)->select('tests.*', 'test_participants.status as test_status')->get();
            // dd($completedTests);
            // Calculate the score for each completed test
            foreach ($completedTests as $completedTest) {
                $testId        = $completedTest->id;
                $userResponses = Answer::where('test_id', $testId)
                    ->where('user_id', Auth::user()->id)
                    ->get();
                $totalQuestionMarks = 0;
                $score              = 0;
                // Calculate the total question marks for the test and the score
                foreach ($userResponses as $response) {
                    $question = Question::find($response->question_id);
                    $totalQuestionMarks += $question->marks;
                    if ($response->answer_id === $response->valid_answer) {
                        $score += $question->marks;
                    }
                }
                if ($totalQuestionMarks > 0) {
                    $percentageScore = ($score / $totalQuestionMarks) * 100;
                } else {
                    $percentageScore = 0;
                }
                $completedTest->score = $percentageScore;
            }
            // Add the calculated score to the completed test object
        } else {
            $ongoing        = '';
            $upcoming       = '';
            $completedTests = '';
        }
        $testParticipantStatus = TestParticipants::whereIn('test_id', $myTestIds)->where('status', 1)->pluck('test_id')->toArray();
        // For get notifications
        $user          = User::find(Auth::user()->id);
        $notifications = [];
        $testResult    = TestResult::whereIn('test_id', $myTestIds)->where('result', 'Passed')->pluck('test_id')->toArray();
        $testResultIds = TestResult::where('user_id', $userId)
            ->select('test_id', \DB::raw('MAX(id) as id')) // Select distinct test_id with the max id
            ->groupBy('test_id')->with('test')->pluck('id');
        $cmpTest = TestResult::whereIn('id', $testResultIds)->with('test')->get();

        return View::make("trainer.$this->model.user-test-listing", compact('ongoing', 'upcoming', 'completedTests', 'cmpTest', 'testParticipantStatus', 'notifications', 'testResult'));
    }

    /**
     * Function for mark a couse as deleted
     *
     * @param  $userId  as id of couse
     * @return redirect page.
     */
    public function training_details_popup($id)
    {
        if (Request::ajax()) {
            $result = Training::where('trainings.id', $id)->leftJoin('training_types', 'training_types.id', '=', 'trainings.type')->select('trainings.*', 'training_types.type as type')->get();

            //echo '<pre>'; print_r($result); die;
            return View::make('trainer.Training.popup', compact('result'));
        }
    }

    public function userTestDetails($test_id = 0)
    {
        $testDetails = Test::where('tests.id', $test_id)->first();
        if ($testDetails) {
            $questionsAlreadyAssigned = UserAssignedTestQuestion::where('test_id', $test_id)
                ->where('trainee_id', Auth::user()->id)
                ->pluck('questions_id')
                ->toArray();

            if ($questionsAlreadyAssigned) {
                $testQuestions = Question::whereIn('id', $questionsAlreadyAssigned)
                    ->where('test_id', $testDetails->id)
                    ->with('questionAttributes')
                    ->get();
            } else {
                $testQuestions = Question::inRandomOrder()
                    ->where('test_id', $testDetails->id)
                    ->with('questionAttributes')
                    ->limit($testDetails->number_of_questions)
                    ->get();

                foreach ($testQuestions as $question) {
                    $userAssignedTestQuestion               = new UserAssignedTestQuestion();
                    $userAssignedTestQuestion->test_id      = $test_id;
                    $userAssignedTestQuestion->trainee_id   = Auth::user()->id;
                    $userAssignedTestQuestion->questions_id = $question->id;
                    $userAssignedTestQuestion->save();
                }
            }

            $totalTrainees = DB::table('test_participants')->where('test_id', $test_id)->count();

            // Pass attemptNumber to the view
            return View::make("trainer.$this->model.userTest", compact('test_id', 'testDetails', 'testQuestions', 'totalTrainees'));
        } else {
            return redirect()->back()->with('This test not found. Contact to admin.');
        }
    }
    public function userTestSubmit(Request $request)
    {
        $r = TestParticipants::where('test_id', $request->test_id)
            ->where('trainee_id', $request->user_id)
            ->first();

        $attemptNumber = $r->user_attempts ?? '1';

        $testResult = TestResult::where('test_id', $request->test_id)
            ->where('user_id', $request->user_id)
            ->where('attempt_number', $attemptNumber)
            ->first();

        $testSubmitted = TestParticipants::where('test_id', $request->test_id)
            ->where('trainee_id', $request->user_id)
            ->first();

        $testAttempts = Test::where('id', $request->test_id)->first();

        if (($testResult && $testResult->result == 'Passed') ||
            ($testSubmitted->status == 1 && $testSubmitted->user_attempts >= $testAttempts->number_of_attempts)
        ) {
            $testAttendStatus = 2;
            $testDetails      = Test::where('tests.id', $request->test_id)->first();

            return response()->json([
                'successRedirect'  => true,
                'testDetails'      => $testDetails,
                'testAttendStatus' => $testAttendStatus,
            ]);
        } else {
            $answerAlreadyExists = Answer::where('test_id', $request->test_id)
                ->where('question_id', $request->question_id)
                ->where('user_id', $request->user_id)
                ->where('attempt_number', $attemptNumber)
                ->first();

            // Convert `answer_ids` array to a comma-separated string
            $answerIds = is_array($request->answer_ids) ? implode(',', $request->answer_ids) : $request->answer_ids;

            if ($answerAlreadyExists) {
                $question = $answerAlreadyExists->question;

                if ($question->question_type == 'MCQ') {
                    $existingAnswers = explode(',', $answerAlreadyExists->answer_id);
                    $newAnswers      = explode(',', $answerIds);

                    // Merge new answers while avoiding duplicates
                    $updatedAnswers = array_unique(array_merge($existingAnswers, $newAnswers));
                    // $answerAlreadyExists->answer_id = implode(',', $updatedAnswers);
                    $answerAlreadyExists->answer_id = implode(',', $newAnswers);
                    $answerAlreadyExists->save();
                } elseif ($question->question_type == 'FreeText') {
                    $answerAlreadyExists->free_text_answer = $request->free_text_answer;
                    $answerAlreadyExists->save();
                } else {
                    $answerAlreadyExists->answer_id = $answerIds;
                    $answerAlreadyExists->save();
                }
            } else {
                // Retrieve the valid answers directly using QuestionAttribute
                $questionAnswers = QuestionAttribute::where('question_id', $request->question_id)
                    ->where('is_correct', 1)
                    ->pluck('id')
                    ->toArray();

                $answer                   = new Answer();
                $answer->test_id          = $request->test_id;
                $answer->question_id      = $request->question_id;
                $answer->user_id          = $request->user_id;
                $answer->answer_id        = $answerIds;
                $answer->valid_answer     = implode(',', $questionAnswers);
                $answer->free_text_answer = $request->free_text_answer;
                $answer->attempt_number   = $attemptNumber;
                $answer->save();
            }

            return response()->json(['success' => true]);
        }
    }
    public function userTestSubmitOldForAllQuestion(Request $request)
    {
        $r = TestParticipants::where('test_id', $request->test_id)
            ->where('trainee_id', $request->user_id)->first();
        if ($r->user_attempts) {
            $attemptNumber = $r->user_attempts;
        } else {
            $attemptNumber = '1';
        }
        $testResult = TestResult::where('test_id', $request->test_id)
            ->where('user_id', $request->user_id)
            ->where('attempt_number', $attemptNumber)
            ->first();

        $testSubmitted = TestParticipants::where('test_id', $request->test_id)
            ->where('trainee_id', $request->user_id)
            ->first();
        $testAttempts = Test::where('id', $request->test_id)->first();

        if (($testResult && $testResult->result == 'Passed') ||
            ($testSubmitted->status == 1 && $testSubmitted->user_attempts >= $testAttempts->number_of_attempts)
        ) {
            $testAttendStatus = 2;
            $testDetails      = Test::where('tests.id', $request->test_id)->first();

            return response()->json(['successRedirect' => true, 'testDetails' => $testDetails, 'testAttendStatus' => $testAttendStatus]);
        } else {
            $answerAlreadyExists = Answer::where('test_id', $request->test_id)
                ->where('question_id', $request->question_id)
                ->where('user_id', $request->user_id)
                ->where('attempt_number', $attemptNumber)
                ->first();

            if ($answerAlreadyExists) {
                $question = $answerAlreadyExists->question;
                if ($question->question_type == 'MCQ') {
                    $existingAnswers = explode(',', $answerAlreadyExists->answer_id);
                    $newAnswer       = $request->answer_id;
                    if (in_array($newAnswer, $existingAnswers)) {
                        $existingAnswers = array_diff($existingAnswers, [$newAnswer]);
                    } else {
                        $existingAnswers[] = $newAnswer;
                    }
                    $updatedAnswerIds               = implode(',', $existingAnswers);
                    $answerAlreadyExists->answer_id = $updatedAnswerIds;
                    $answerAlreadyExists->save();
                } elseif ($question->question_type == 'FreeText') {
                    $answerAlreadyExists->free_text_answer = $request->answer_text;
                    $answerAlreadyExists->save();
                } else {
                    $answerAlreadyExists->answer_id = $request->answer_id;
                    $answerAlreadyExists->save();
                }
            } else {
                $answer                   = new Answer();
                $answer->test_id          = $request->test_id;
                $answer->question_id      = $request->question_id;
                $answer->user_id          = $request->user_id;
                $answer->answer_id        = $request->answer_id;
                $answer->valid_answer     = implode(',', collect($answer->question->questionAnswer)->where('is_correct', 1)->pluck('id')->toArray());
                $answer->free_text_answer = $request->answer_text;
                $answer->attempt_number   = $attemptNumber;
                $answer->save();
            }

            return response()->json(['success' => true]);
        }
    }

    public function userTestResult($id, Request $request)
    {
        $userId = Auth::user()->id;

        $r = TestParticipants::where('test_id', $id)
            ->where('trainee_id', $userId)->first();
        if ($r->user_attempts) {
            $attemptNumber = $r->user_attempts;
        } else {
            $attemptNumber = '1';
        }

        $answers = Answer::where('user_id', $userId)
            ->where('test_id', $id)
            ->where('attempt_number', $attemptNumber)
            ->get();

        $obtainedMarks       = 0;
        $hasFreeTextQuestion = false;

        $assignedQuestionIds = UserAssignedTestQuestion::where('trainee_id', $userId)
            ->where('test_id', $id)
            ->pluck('questions_id')
            ->toArray();
        $totalMarks = Question::whereIn('id', $assignedQuestionIds)->sum('marks');

        $results = [];

        foreach ($answers as $answer) {
            $question = $answer->question;

            if (in_array($question->id, $assignedQuestionIds)) {
                $correctOptions = collect($question->questionAnswer)->where('is_correct', 1)->pluck('id')->toArray();
                $validAnswer    = explode(',', $answer->valid_answer);
                $userAnswer     = explode(',', $answer->answer_id);
                sort($validAnswer);
                sort($userAnswer);

                if ($question->question_type === 'SCQ') {
                    if ($validAnswer === $userAnswer && $answer->free_text_answer == null) {
                        $obtainedMarks += $question->marks;
                    }
                } elseif ($question->question_type === 'T/F') {
                    if ($validAnswer === $userAnswer && $answer->free_text_answer == null) {
                        $obtainedMarks += $question->marks;
                    }
                } elseif ($question->question_type === 'MCQ') {
                    // If all correct answers match, give full marks; otherwise, give 0
                    if ($validAnswer === $userAnswer && $answer->free_text_answer == null) {
                        $obtainedMarks += $question->marks; // Full marks if all answers match
                    }
                }
                $result = [
                    'question'    => $question->question,
                    'user_answer' => $answer->answer_id,
                    'marks'       => $question->marks,
                ];
                $results[] = $result;

                if ($question->question_type == 'FreeText') {
                    $hasFreeTextQuestion = true;
                }
            }
        }

        $testParticipants = TestParticipants::where('trainee_id', $userId)->where('test_id', $id)->first();
        if ($testParticipants->user_attempts == null) {
            $testParticipants->update([
                'status'        => 1,
                'user_attempts' => 1,
            ]);
        } else {
            $testParticipants->update([
                'status'        => 1,
                'user_attempts' => $testParticipants->user_attempts + 1,
            ]);
        }
        // Calculate the percentage only if $totalMarks is greater than 0
        if ($totalMarks > 0) {
            $percentage = ($obtainedMarks / $totalMarks) * 100;
        } else {
            // Redirect with error message if $totalMarks is 0
            return redirect()->route('trainer.dashboard')->with('message', 'You have already attempted this test. Resubmission in the same attempt is not allowed. Please try again if you have remaining attempts.');
        }
        $getTestMarks = Test::where('id', $id)->first();

        $resultStatus = $percentage >= $getTestMarks->minimum_marks ? 'Passed' : 'Failed';

        $alreadySubmittedTest = TestResult::where('test_id', $id)
            ->where('user_id', $userId)
            ->where('attempt_number', $attemptNumber)
            ->first();

        if ($alreadySubmittedTest) {
            $alreadySubmittedTest->update([
                'total_questions'          => count($answers),
                'total_attemted_questions' => count($assignedQuestionIds),
                'total_marks'              => $totalMarks,
                'obtain_marks'             => $obtainedMarks,
                'percentage'               => $percentage,
                'result'                   => $resultStatus,
                'attempt_number'           => $attemptNumber,
            ]);
        } else {
            $testResult = new TestResult([
                'test_id'                  => $id,
                'user_id'                  => $userId,
                'total_questions'          => count($answers),
                'total_attemted_questions' => count($assignedQuestionIds),
                'total_marks'              => $totalMarks,
                'obtain_marks'             => $obtainedMarks,
                'percentage'               => $percentage,
                'result'                   => $resultStatus,
                'status'                   => 0,
                'attempt_number'           => $attemptNumber,
            ]);
            $testResult->save();
        }

        if ($hasFreeTextQuestion) {
            return view('trainer.YourTest.user-test-submit-freetext', [
                'testDetails' => $getTestMarks->title,
            ]);
        } else {
            return view('trainer.YourTest.user-test-result', [
                'results'       => $results,
                'totalMarks'    => $totalMarks,
                'obtainedMarks' => $obtainedMarks,
                'percentage'    => $percentage,
                'testPar'       => $id,
                'resultStatus'  => $resultStatus,
                'testDetails'   => $getTestMarks,
            ]);
        }
    }
    public function userTestParticipantStatus(Request $request)
    {
        $testId    = $request->input('test_id');
        $traineeId = Auth::user()->id;

        $testParticipants = TestParticipants::where('trainee_id', $traineeId)->where('test_id', $testId)->first();

        if ($testParticipants->user_attempts == null) {
            $testParticipants->update([
                'status'        => 1,
                'user_attempts' => 1,
            ]);
        } else {
            $testParticipants->update([
                'status'        => 1,
                'user_attempts' => $testParticipants->user_attempts + 1,
            ]);
        }
        $attemptNumber       = $testParticipants->user_attempts;
        $latestAttemptNumber = Answer::where('user_id', $traineeId)
            ->where('test_id', $testId)
            ->max('attempt_number');

        $answers = Answer::where('user_id', $traineeId)
            ->where('test_id', $testId)
            ->where('attempt_number', $latestAttemptNumber)
            ->get();

        if ($answers->isEmpty()) {
            return response()->json(['message' => 'No answers found for this test, test result not updated.']);
        }

        $obtainedMarks       = 0;
        $hasFreeTextQuestion = false;

        // Get the IDs of assigned questions for this user
        $assignedQuestionIds = UserAssignedTestQuestion::where('trainee_id', $traineeId)
            ->where('test_id', $testId)
            ->pluck('questions_id')
            ->toArray();
        $totalMarks = Question::whereIn('id', $assignedQuestionIds)->sum('marks');

        foreach ($answers as $answer) {
            $question = $answer->question;
            if (in_array($question->id, $assignedQuestionIds)) {
                $correctOptions = collect($question->questionAnswer)->where('is_correct', 1)->pluck('id')->toArray();
                $validAnswer    = explode(',', $answer->valid_answer);
                $userAnswer     = explode(',', $answer->answer_id);
                sort($validAnswer);
                sort($userAnswer);

                // Calculate partial marks for MCQs
                if ($question->question_type == 'MCQ' || count($validAnswer) > 1) {
                    // If all correct answers match, give full marks; otherwise, give 0
                    if ($validAnswer === $userAnswer && $answer->free_text_answer == null) {
                        $obtainedMarks += $question->marks; // Full marks if all answers match
                    }
                } elseif ($validAnswer == $userAnswer) {
                    $obtainedMarks += $question->marks;
                }

                if ($question->question_type == 'FreeText') {
                    $hasFreeTextQuestion = true;
                }
            }
        }

        $percentage = $totalMarks > 0 ? ($obtainedMarks / $totalMarks) * 100 : 0;

        // Set the result status (Passed/Failed or FreeText_Paper)
        $resultStatus = 'FreeText_Paper';
        if (! $hasFreeTextQuestion) {
            $minimumMarks = Test::where('id', $testId)->value('minimum_marks');
            $resultStatus = $percentage >= $minimumMarks ? 'Passed' : 'Failed';
        }
        // Save or update the test result, including partial submissions
        TestResult::updateOrCreate(
            ['test_id' => $testId, 'user_id' => $traineeId, 'attempt_number' => $latestAttemptNumber],
            [
                'total_questions'          => count($assignedQuestionIds), // Total assigned questions
                'total_attemted_questions' => $answers->count(),           // Total answered questions
                'total_marks'              => $totalMarks,
                'obtain_marks'             => $obtainedMarks,
                'percentage'               => $percentage,
                'result'                   => $resultStatus,
                'user_attempts'            => $attemptNumber,
                'status'                   => 1, // Marking the test as completed
            ]
        );

        return response()->json(['message' => 'Test status updated successfully']);
    }
    public function userTestParticipantStatusOLD(Request $request)
    {
        // return $request;
        $testId    = $request->input('test_id');
        $traineeId = Auth::user()->id;

        $testParticipants = TestParticipants::where('trainee_id', $traineeId)->where('test_id', $testId)->first();
        if ($testParticipants->user_attempts == null) {
            $testParticipants->update([
                'status'        => 1,
                'user_attempts' => 1,
            ]);
        } else {
            $testParticipants->update([
                'status'        => 1,
                'user_attempts' => $testParticipants->user_attempts + 1,
            ]);
        }

        $answers = Answer::where('user_id', $traineeId)->where('test_id', $testId)->where('attempt_number', $testParticipants->user_attempts)
            ->get();

        // If no answers found, do not proceed with test result calculation
        if ($answers->isEmpty()) {
            return response()->json(['message' => 'No answers found for this test, test result not updated.']);
        }

        $obtainedMarks       = 0;
        $hasFreeTextQuestion = false;

        $assignedQuestionIds = UserAssignedTestQuestion::where('trainee_id', $traineeId)
            ->where('test_id', $testId)
            ->pluck('questions_id')
            ->toArray();
        $totalMarks = Question::whereIn('id', $assignedQuestionIds)->sum('marks');

        foreach ($answers as $answer) {
            $question = $answer->question;
            if (in_array($question->id, $assignedQuestionIds)) {
                $questionAnswer               = $question->questionAnswer;
                $questionAttributesCollection = collect($questionAnswer);
                $correctOptions               = $questionAttributesCollection->where('is_correct', 1)->pluck('id')->toArray();
                $validAnswer                  = explode(',', $answer->valid_answer);
                $userAnswer                   = explode(',', $answer->answer_id);
                sort($validAnswer);
                sort($userAnswer);

                // Calculate partial marks for MCQs
                if ($question->question_type == 'MCQ' || count($validAnswer) > 1) {
                    // If all correct answers match, give full marks; otherwise, give 0
                    if ($validAnswer === $userAnswer && $answer->free_text_answer == null) {
                        $obtainedMarks += $question->marks; // Full marks if all answers match
                    }
                } elseif ($validAnswer == $userAnswer) {
                    $obtainedMarks += $question->marks;
                }

                if ($question->question_type == 'FreeText') {
                    $hasFreeTextQuestion = true;
                }
            }
        }

        $percentage = $totalMarks > 0 ? ($obtainedMarks / $totalMarks) * 100 : 0;

        $resultStatus = 'FreeText_Paper';
        if (! $hasFreeTextQuestion) {
            $minimumMarks = Test::where('id', $testId)->value('minimum_marks');
            $resultStatus = $percentage >= $minimumMarks ? 'Passed' : 'Failed';
        }

        // Save or update the test result
        TestResult::updateOrCreate(
            ['test_id' => $testId, 'user_id' => $traineeId],
            [
                'total_questions'          => count($answers),
                'total_attemted_questions' => count($assignedQuestionIds),
                'total_marks'              => $totalMarks,
                'obtain_marks'             => $obtainedMarks,
                'percentage'               => $percentage,
                'result'                   => $resultStatus,
                'user_attempts'            => $testParticipants->user_attempts,
                'status'                   => 0,
                'attempt_number'           => $testParticipants->user_attempts,
            ]
        );

        return response()->json(['message' => 'Test status updated successfully']);
    }

    public function userTestSubmittedThanksPage(Request $request)
    {
        $id               = $request->route('id');
        $testDetails      = Test::where('tests.id', $id)->first();
        $title            = $testDetails->title;
        $testAttendStatus = 2;

        return View::make("trainer.$this->model.userTestSubmittedThanksPage", compact('testDetails', 'testAttendStatus'));
    }

    public function userTestCertificateDownload($id)
    {

        // dd('here');
        $getTestMarks = Test::where('id', $id)->first();
        $getResult    = TestResult::where('test_id', $id)->where('result', 'Passed')->first();
        $start_date   = \Carbon\Carbon::parse($getTestMarks->start_date_time);
        $end_date     = \Carbon\Carbon::parse($getTestMarks->end_date_time);
        $lengthInDays = $start_date->diffInDays($end_date);

        $data = [
            'title'          => $getTestMarks->title,
            'name'           => Auth::user()->fullname,
            'admin'          => 'Airtel Payments Bank',
            'date'           => $getResult->created_at->format('d-M-Y'),
            'downloadDate'   => date('d-M-Y'),
            'lengthInDays'   => $lengthInDays,
            'logo'           => public_path('images/apb-main-logo.png'),
            'background_img' => asset('front/img/backgroundimage.png'),

        ];

        $pdf = PDF::loadView('trainer.YourTest.certificate-pdf', $data);

        return $pdf->download($getTestMarks->title . 'certificate.pdf');
    }
}
