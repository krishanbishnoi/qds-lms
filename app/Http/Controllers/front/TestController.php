<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\BaseController;
use App\Models\Test;
use App\Models\User;
use App\Models\TrainingDocument;
use App\Models\TrainingType;
use App\Models\TestParticipants;
use App\Models\StateDescription;
use App\Models\Question;
use App\Models\QuestionAttribute;
use App\Models\UserAssignedTestQuestion;
use App\Models\Answer;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Mail, Redirect, Response, Session, URL, View, Validator, PDF;;

/**
 * TestController Controller
 *
 * Add your methods in the class below
 *
 */
class TestController extends BaseController
{
    public $model        =    'test';
    public $sectionName    =    'Test';
    public $sectionNameSingular    =    'Test';
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
     * @param $modelId id  of area
     *
     * @return view page.
     */
    public function userTests()
    {
        $myTestIds = TestParticipants::where('trainee_id', Auth::user()->id)->pluck('test_id')->toArray();

        if (!empty($myTestIds)) {
            $ongoing = Test::whereIn('tests.id', $myTestIds)->where('tests.status', 0)
                ->leftJoin('users', 'users.id', '=', 'tests.user_id')
                ->select('tests.*', 'users.first_name as created_by')
                ->orderBy('tests.id', 'DESC')
                ->get();

            $upcoming = Test::whereIn('tests.id', $myTestIds)->where('tests.status', 1)
                ->leftJoin('users', 'users.id', '=', 'tests.user_id')
                ->select('tests.*', 'users.first_name as created_by')
                ->orderBy('tests.id', 'DESC')
                ->get();

            $completedTests = Test::whereIn('tests.id', $myTestIds)
                ->leftJoin('test_participants', 'test_participants.test_id', '=', 'tests.id')
                ->where('test_participants.status', 1)
                ->select('tests.*', 'test_participants.status as test_status')
                ->get();

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
        } else {
            $ongoing = [];
            $upcoming = [];
            $completedTests = [];
        }

        // dd($testParticipantStatus);
        // For getting notifications
        $user = User::find(Auth::user()->id);
        $notifications = $user->notifications->where('read_at', '');

        return view("front.test.user-test-listing", compact('ongoing', 'upcoming', 'completedTests', 'notifications'));
    }

    /**
     * Function for mark a couse as deleted
     *
     * @param $userId as id of couse
     *
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
        $startDateTime   = Carbon::parse($testDetails->start_date_time);
        $endDateTime     = Carbon::parse($testDetails->end_date_time);
        $currentDateTime = Carbon::now();
        // dd($startDateTime , $currentDateTime);
        // Check if the current time is within the start and end times
        if ($currentDateTime->lt($startDateTime)) {
            return redirect()->back()->with('error', 'The test has not started yet. Please come back at ' . $startDateTime->format('Y-m-d h:i:s A') . '.');
        }
        if ($currentDateTime->gt($endDateTime)) {
            return redirect()->back()->with('error', 'The test time is over. It ended at ' . $endDateTime->format('Y-m-d h:i:s A') . '.');
        }
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
            return view("front.test.userTest", compact('test_id', 'testDetails', 'testQuestions', 'totalTrainees'));
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

        if ($testResult && $testResult->result == 'Passed' && $testSubmitted->status == 1 && $testSubmitted->user_attempts >= $testSubmitted->number_of_attempts) {
            $testDetails = Test::where('tests.id', $request->test_id)->first();
            return response()->json(['successRedirect' => true, 'testDetails' => $testDetails]);
        } else {
            $answerAlreadyExists = Answer::where('test_id', $request->test_id)
                ->where('question_id', $request->question_id)
                ->where('user_id', $request->user_id)
                ->first();
            // If the answer already exists, we will handle checkbox question behavior
            if ($answerAlreadyExists) {
                $question = $answerAlreadyExists->question;
                if ($question->question_type == 'MCQ') {
                    $existingAnswers = explode(',', $answerAlreadyExists->answer_id);
                    // dd($existingAnswers);
                    $newAnswer = $request->answer_id;
                    if (in_array($newAnswer, $existingAnswers)) {
                        // If the new answer is already in the existing answers, remove it
                        $existingAnswers = array_diff($existingAnswers, [$newAnswer]);
                    } else {
                        // If the new answer is not in the existing answers, add it
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
                // If the answer does not exist, create a new one
                $answer = new Answer();
                $answer->test_id = $request->test_id;
                $answer->question_id = $request->question_id;
                $question = $answer->question;
                $answer->user_id = $request->user_id;
                // For single-choice questions, set the answer directly
                $answer->answer_id = $request->answer_id;
                $question = $answer->question;
                $questionAnswer = $question->questionAnswer;
                // Convert questionAttributes array to a Laravel Collection
                $questionAttributesCollection = collect($questionAnswer);
                // Filter the options to get only the correct ones
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
        $percentage = 0;
        $resultStatus = 'FreeText_Paper';
        $hasFreeTextQuestion = false;

        $user = User::where('id', $userId)->with('parentManager')->first();

        // Fetch the IDs of questions assigned to the user for this test
        $assignedQuestionIds = UserAssignedTestQuestion::where('trainee_id', $userId)
            ->where('test_id', $id)
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
                if ($validAnswer === $userAnswer && $answer->free_text_answer === null) {

                    $obtainedMarks += $question->marks;
                }
                // Calculate partial marks for MCQs
                if ($question->question_type == 'MCQ' || count($validAnswer) > 1) {
                    $correctCount = count(array_intersect($validAnswer, $userAnswer));
                    $obtainedMarks += ($question->marks * $correctCount) / count($validAnswer);
                } elseif ($validAnswer === $userAnswer) {
                    $obtainedMarks += $question->marks;
                }
            }

            $result = [
                'question' => $question->question,
                'user_answer' => $answer->answer_id,
                // 'correct_options' => $correctOptions,
                'marks' => $question->marks
            ];
            $results[] = $result;
            if ($question->question_type == 'FreeText') {
                $hasFreeTextQuestion = true;
            }
        }

        // Check if user has already attempted the test and update user_attempts
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
        $getTestMarks = Test::where('id', $id)->first();

        if (!$hasFreeTextQuestion) {
            $percentage = ($obtainedMarks / $totalMarks) * 100;


            if ($percentage >= $getTestMarks->minimum_marks) {
                $resultStatus = "Passed";
            } else {
                $resultStatus = "Failed";
            }
        }
        $alreadysubmitedTest = TestResult::where('test_id', $id)->where('user_id', $userId)->first();
        if ($alreadysubmitedTest) {
            $alreadysubmitedTest->update([
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
            $testResult->save();
        }
        if ($getTestMarks->publish_result == 1 && !$hasFreeTextQuestion) {
            return view('front.test.user-test-result', [
                'results' => $results,
                'totalMarks' => $totalMarks,
                'obtainedMarks' => $obtainedMarks,
                'percentage' => $percentage,
                'testPar' => $id,
                'resultStatus' => $resultStatus,
                'testDetails' => $getTestMarks,
                'admin' => "QDegrees Services",


            ]);
        } elseif ($hasFreeTextQuestion) {
            $testDetails = Test::where('tests.id', $id)->first();
            $testAttendStatus = 1;
            return View::make("front.$this->model.userTestSubmittedThanksPage", compact('testDetails', 'testAttendStatus'));
        } else {
            Session::flash('flash_notice', trans('Thank you for attending test. your response has been saved for result contect to admin.'));
            return Redirect::intended('/dashboard')->with('message', 'Thank you for attending test. your response has been saved for result contect to admin.');
        }
    }


    public function userTestParticipantStatus(Request $request)
    {
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


    public function userTestCertificateDownload($id)
    {
        $getTestMarks = Test::where('id', $id)->first();
        $start_date = \Carbon\Carbon::parse($getTestMarks->start_date_time);
        $end_date = \Carbon\Carbon::parse($getTestMarks->end_date_time);
        $lengthInDays = $start_date->diffInDays($end_date);

        $data = [
            'title' => $getTestMarks->title,
            'name' => Auth::user()->fullname,
            'admin' => "QDegrees Services",
            'date' => date('m/d/Y'),
            'lengthInDays' => $lengthInDays,
            'logo' => public_path('lms-img/qdegrees-logo.png'),
            'background_img' => asset('front/img/backgroundimage.png')

        ];

        $pdf = PDF::loadView('front.test.certificate-pdf', $data);

        return $pdf->download($getTestMarks->title . '-certificate.pdf');
    }
}// end TestController
