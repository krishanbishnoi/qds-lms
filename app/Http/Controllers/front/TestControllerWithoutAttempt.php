<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\BaseController;
use App\Model\Answer;
use App\Model\Question;
use App\Model\Test;
use App\Model\TestParticipants;
use App\Model\TestResult;
use App\Model\User;
use App\Model\UserAssignedTestQuestion;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use PDF;
use Redirect;
use View;

/**
 * TestController Controller
 *
 * Add your methods in the class below
 */
class TestController extends BaseController
{
    public $model = 'test';

    public $sectionName = 'Test';

    public $sectionNameSingular = 'Test';

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

        $myTestIds = TestParticipants::where('trainee_id', Auth::user()->id)->pluck('test_id')->toArray();
        //   echo '<pre>'; print_r($myTestIds); die;
        if (!empty($myTestIds)) {
            $ongoing = Test::whereIn('tests.id', $myTestIds)->where('tests.status', 0)->leftJoin('users', 'users.id', '=', 'tests.user_id')->select('tests.*', 'users.first_name as created_by')->orderBy('tests.id', 'DESC')->get();
            $upcoming = Test::whereIn('tests.id', $myTestIds)->where('tests.status', 1)->leftJoin('users', 'users.id', '=', 'tests.user_id')->select('tests.*', 'users.first_name as created_by')->orderBy('tests.id', 'DESC')->get();
            $completedTests = Test::whereIn('tests.id', $myTestIds)->leftJoin('test_participants', 'test_participants.test_id', '=', 'tests.id')->where('test_participants.status', 1)->select('tests.*', 'test_participants.status as test_status')->get();
            // Calculate the score for each completed test
            foreach ($completedTests as $completedTest) {
                $testId = $completedTest->id;
                $userResponses = Answer::where('test_id', $testId)
                    ->where('user_id', Auth::user()->id)
                    ->get();
                $totalQuestionMarks = 0;
                $score = 0;
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
            $ongoing = '';
            $upcoming = '';
            $completedTests = '';
        }
        $testParticipantStatus = TestParticipants::whereIn('test_id', $myTestIds)->where('status', 1)->pluck('test_id')->toArray();
        // For get notifications
        $user = User::find(Auth::user()->id);
        $notifications = Auth::user()->notifications()->whereNull('read_at')->get();
        $testResult = TestResult::whereIn('test_id', $myTestIds)->where('result', 'Passed')->pluck('test_id')->toArray();

        // dd($testResult);
        return View::make("front.$this->model.user-test-listing", compact('ongoing', 'upcoming', 'completedTests', 'testParticipantStatus', 'notifications', 'testResult'));
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
            return View::make('front.Training.popup', compact('result'));
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

                $testDetails = Test::where('tests.id', $test_id)->first();

                $testQuestions = Question::whereIn('id', $questionsAlreadyAssigned)
                    ->where('test_id', $testDetails->id)
                    ->with('questionAttributes')
                    ->get();
            } else {

                $testDetails = Test::where('tests.id', $test_id)->first();
                $testQuestions = Question::inRandomOrder()->where('test_id', $testDetails->id)->with('questionAttributes')
                    ->limit($testDetails->number_of_questions)->get();

                foreach ($testQuestions as $question) {
                    $userAssignedTestQuestion = new UserAssignedTestQuestion();
                    $userAssignedTestQuestion->test_id = $test_id;
                    $userAssignedTestQuestion->trainee_id = Auth::user()->id;
                    $userAssignedTestQuestion->questions_id = $question->id;
                    $userAssignedTestQuestion->save();
                }
            }
            $totalTrainees = DB::table('test_participants')->where('test_id', $test_id)
                ->count();

            return View::make("front.$this->model.userTest", compact('test_id', 'testDetails', 'testQuestions', 'totalTrainees'));
        } else {
            return redirect()->back()->with('This test not found. Contact to admin.');
        }
    }

    public function userTestSubmit(Request $request)
    {
        $testResult = TestResult::where('test_id', $request->test_id)
            ->where('user_id', $request->user_id)->first();

        $testSubmitted = TestParticipants::where('test_id', $request->test_id)
            ->where('trainee_id', $request->user_id)->first();
        $testAttemps = Test::where('id', $request->test_id)->first();
        if ($testResult && $testResult->result == 'Passed' || $testSubmitted->status == 1 && $testSubmitted->user_attempts >= $testAttemps->number_of_attempts) {
            $testAttendStatus = 2;
            $testDetails = Test::where('tests.id', $request->test_id)->first();
            return response()->json(['successRedirect' => true, 'testDetails' => $testDetails, 'testAttendStatus' => $testAttendStatus]);
        } else {

            $answerAlreadyExists = Answer::where('test_id', $request->test_id)
                ->where('question_id', $request->question_id)
                ->where('user_id', $request->user_id)
                ->first();
            if ($answerAlreadyExists) {
                $question = $answerAlreadyExists->question;
                if ($question->question_type == 'MCQ') {
                    $existingAnswers = explode(',', $answerAlreadyExists->answer_id);
                    // dd($existingAnswers);
                    $newAnswer = $request->answer_id;
                    if (in_array($newAnswer, $existingAnswers)) {
                        $existingAnswers = array_diff($existingAnswers, [$newAnswer]);
                    } else {
                        $existingAnswers[] = $newAnswer;
                    }
                    $updatedAnswerIds = implode(',', $existingAnswers);
                    $answerAlreadyExists->answer_id = $updatedAnswerIds;
                    $answerAlreadyExists->save();
                } elseif ($question->question_type == 'FreeText') {
                    // For FreeText questions, update the existing answer
                    $answerAlreadyExists->free_text_answer = $request->answer_text;
                    $answerAlreadyExists->save();
                } else {
                    // For single-choice questions, update the existing answer
                    $answerAlreadyExists->answer_id = $request->answer_id;
                    $answerAlreadyExists->save();
                }
            } else {
                $answer = new Answer();
                $answer->test_id = $request->test_id;
                $answer->question_id = $request->question_id;
                $question = $answer->question;
                $answer->user_id = $request->user_id;
                $answer->answer_id = $request->answer_id;
                $question = $answer->question;
                $questionAnswer = $question->questionAnswer;
                $questionAttributesCollection = collect($questionAnswer);

                $correctOptions = $questionAttributesCollection->where('is_correct', 1)->pluck('id')->toArray();
                $correctOptionString = implode(',', $correctOptions);
                $answer->valid_answer = $correctOptionString;
                $answer->free_text_answer = $request->answer_text;
                // Save the answer in the database
                $answer->save();
            }

            return response()->json(['success' => true]);
        }
    }

    public function userTestResult($id)
    {
        $userId = Auth::user()->id;
        $answers = Answer::where('user_id', $userId)->where('test_id', $id)->get();
        $totalMarks = 0;
        $obtainedMarks = 0;
        $hasFreeTextQuestion = false;

        // Fetch the IDs of questions assigned to the user for this test
        $assignedQuestionIds = UserAssignedTestQuestion::where('trainee_id', $userId)
            ->where('test_id', $id)
            ->pluck('questions_id')
            ->toArray();

        $results = [];

        foreach ($answers as $answer) {
            $question = $answer->question;

            if (in_array($question->id, $assignedQuestionIds)) {
                $questionAnswer = $question->questionAnswer;
                $questionAttributesCollection = collect($questionAnswer);
                $correctOptions = $questionAttributesCollection->where('is_correct', 1)->pluck('id')->toArray();
                $validAnswer = explode(',', $answer->valid_answer);
                $userAnswer = explode(',', $answer->answer_id);
                sort($validAnswer);
                sort($userAnswer);
                $totalMarks += $question->marks;


                if ($question->question_type === "SCQ") {
                    if ($validAnswer === $userAnswer && $answer->free_text_answer === null) {
                        $obtainedMarks += $question->marks;
                    }
                } elseif ($question->question_type === 'MCQ') {
                    $rightAnswers = count(array_intersect($validAnswer, $userAnswer));
                    $marksByValidAnswer = $question->marks / count($validAnswer);
                    $obtainedMarks += $rightAnswers * $marksByValidAnswer;
                }
                $result = [
                    'question' => $question->question,
                    'user_answer' => $answer->answer_id,
                    // 'correct_options' => $correctOptions,
                    'marks' => $question->marks,
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
                'status' => 1,
                'user_attempts' => 1,
            ]);
        } else {
            $testParticipants->update([
                'status' => 1,
                'user_attempts' => $testParticipants->user_attempts + 1,
            ]);
        }

        $percentage = ($obtainedMarks / $totalMarks) * 100;
        $getTestMarks = Test::where('id', $id)->first();

        if ($percentage >= $getTestMarks->minimum_marks) {
            $resultStatus = 'Passed';
        } else {
            $resultStatus = 'Failed';
        }

        $alreadySubmittedTest = TestResult::where('test_id', $id)->where('user_id', $userId)->first();
        if ($alreadySubmittedTest) {
            $alreadySubmittedTest->update([
                'total_questions' => count($answers),
                'total_attemted_questions' => count($assignedQuestionIds),
                'total_marks' => $totalMarks,
                'obtain_marks' => $obtainedMarks,
                'percentage' => $percentage,
                'result' => $resultStatus,
                'user_attempts' => $testParticipants->user_attempts,
            ]);
        } else {
            $testResult = new TestResult([
                'test_id' => $id,
                'user_id' => $userId,
                'total_questions' => count($answers),
                'total_attemted_questions' => count($assignedQuestionIds),
                'total_marks' => $totalMarks,
                'obtain_marks' => $obtainedMarks,
                'percentage' => $percentage,
                'result' => $resultStatus,
                'status' => 0,
                'user_attempts' => $testParticipants->user_attempts,
            ]);
            // return $testResult;
            $testResult->save();
        }


        if ($hasFreeTextQuestion) {
            return view('front.test.user-test-submit-freetext', [
                'testDetails' => $getTestMarks->title,
            ]);
        } else {
            return view('front.test.user-test-result', [
                'results' => $results,
                'totalMarks' => $totalMarks,
                'obtainedMarks' => $obtainedMarks,
                'percentage' => $percentage,
                'testPar' => $id,
                'resultStatus' => $resultStatus,
                'testDetails' => $getTestMarks,
            ]);
        }
    }
    public function userTestParticipantStatus(Request $request)
    {
        // return $request;
        $testId = $request->input('test_id');
        $traineeId = Auth::user()->id;

        $testParticipants = TestParticipants::where('trainee_id', $traineeId)->where('test_id', $testId)->first();
        if ($testParticipants->user_attempts == null) {
            $testParticipants->update([
                'status' => 1,
                'user_attempts' => 1,
            ]);
        } else {
            $testParticipants->update([
                'status' => 1,
                'user_attempts' => $testParticipants->user_attempts + 1,
            ]);
        }
        // Calculate the test result
        $answers = Answer::where('user_id', $traineeId)->where('test_id', $testId)->get();
        $totalMarks = 0;
        $obtainedMarks = 0;
        $hasFreeTextQuestion = false;

        $assignedQuestionIds = UserAssignedTestQuestion::where('trainee_id', $traineeId)
            ->where('test_id', $testId)
            ->pluck('questions_id')
            ->toArray();

        foreach ($answers as $answer) {
            $question = $answer->question;
            if (in_array($question->id, $assignedQuestionIds)) {
                $questionAnswer = $question->questionAnswer;
                $questionAttributesCollection = collect($questionAnswer);
                $correctOptions = $questionAttributesCollection->where('is_correct', 1)->pluck('id')->toArray();
                $validAnswer = explode(',', $answer->valid_answer);
                $userAnswer = explode(',', $answer->answer_id);
                sort($validAnswer);
                sort($userAnswer);
                $totalMarks += $question->marks;

                // Calculate partial marks for MCQs
                if ($question->question_type == 'MCQ' || count($validAnswer) > 1) {
                    $correctCount = count(array_intersect($validAnswer, $userAnswer));
                    $obtainedMarks += ($question->marks * $correctCount) / count($validAnswer);
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
        if (!$hasFreeTextQuestion) {
            $minimumMarks = Test::where('id', $testId)->value('minimum_marks');
            $resultStatus = $percentage >= $minimumMarks ? 'Passed' : 'Failed';
        }

        // Save or update the test result
        TestResult::updateOrCreate(
            ['test_id' => $testId, 'user_id' => $traineeId],
            [
                'total_questions' => count($answers),
                'total_attemted_questions' => count($assignedQuestionIds),
                'total_marks' => $totalMarks,
                'obtain_marks' => $obtainedMarks,
                'percentage' => $percentage,
                'result' => $resultStatus,
                'user_attempts' => $testParticipants->user_attempts,
                'status' => 0,
            ]
        );
        return response()->json(['message' => 'Test status updated successfully']);
    }

    public function userTestSubmittedThanksPage(Request $request)
    {

        $testDetails = Test::where('tests.id', 29292929)->first();
        if (!$testDetails) {
            $testDetails = (object) ['title' => null];
        } else {
            $testDetails->title = null; // Set the title to null
        }
        $testAttendStatus = 2;
        return View::make("front.$this->model.userTestSubmittedThanksPage", compact('testDetails', 'testAttendStatus'));
    }
    public function userTestCertificateDownload($id)
    {
        $getTestMarks = Test::where('id', $id)->first();
        $start_date = \Carbon\Carbon::parse($getTestMarks->start_date_time);
        $end_date = \Carbon\Carbon::parse($getTestMarks->end_date_time);
        $lengthInDays = $start_date->diffInDays($end_date);

        $data = [
            'title' => $getTestMarks->title,
            'name' => Auth::user()->fullname,
            'admin' => 'Airtel Payments Bank',
            'date' => date('m/d/Y'),
            'lengthInDays' => $lengthInDays,
            'logo' => public_path('images/apb-main-logo.png'),
            'background_img' => asset('front/img/backgroundimage.png'),

        ];

        $pdf = PDF::loadView('front.test.certificate-pdf', $data);

        return $pdf->download($getTestMarks->title . 'certificate.pdf');
    }
}// end TestController