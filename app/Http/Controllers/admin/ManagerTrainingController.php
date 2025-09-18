<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Model\Answer;
use App\Model\Course;
use App\Model\Question;
use App\Model\QuestionAttribute;
use App\Model\Test;
use App\Model\Training;
use App\Model\TrainingParticipants;
use App\Model\TrainingTestParticipants;
use App\Model\TrainingTestResult;
use App\Model\User;
use App\Model\UserAssignedTestQuestion;
use App\Model\UserAssignedTrainingProgress;
use Auth;
use DB;
use Illuminate\Http\Request;
use PDF;
use Redirect;
use View;

/**
 * TrainingController Controller
 *
 * Add your methods in the class below
 */
class ManagerTrainingController extends BaseController
{
    public $model = 'ManagerTraining';

    public $sectionName = 'My Trainings';

    public $sectionNameSingular = 'My Training';

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
    public function userTrainings()
    {
        $myTrainingsIds = TrainingParticipants::where('trainee_id', Auth::user()->id)->pluck('training_id')->toArray();
        //   echo '<pre>'; print_r($myTrainingsIds); die;
        if (! empty($myTrainingsIds)) {
            $ongoing   = Training::whereIn('trainings.id', $myTrainingsIds)->where('trainings.status', 0)->leftJoin('training_types', 'training_types.id', '=', 'trainings.type')->leftJoin('users', 'users.id', '=', 'trainings.user_id')->select('trainings.*', 'training_types.type as type', 'users.first_name as created_by')->orderBy('trainings.id', 'DESC')->get();
            $upcoming  = Training::whereIn('trainings.id', $myTrainingsIds)->where('trainings.status', 1)->leftJoin('training_types', 'training_types.id', '=', 'trainings.type')->leftJoin('users', 'users.id', '=', 'trainings.user_id')->select('trainings.*', 'training_types.type as type', 'users.first_name as created_by')->orderBy('trainings.id', 'DESC')->get();
            $completed = Training::whereIn('trainings.id', $myTrainingsIds)->where('trainings.status', 3)->leftJoin('training_types', 'training_types.id', '=', 'trainings.type')->leftJoin('users', 'users.id', '=', 'trainings.user_id')->select('trainings.*', 'training_types.type as type', 'users.first_name as created_by')->orderBy('trainings.id', 'DESC')->get();

            // Add passing scores to both ongoing and upcoming trainings
            $ongoing->map(function ($training) {
                $training->passing_score = $this->calculatePassingScore($training);
                return $training;
            });

            $upcoming->map(function ($training) {
                $training->passing_score = $this->calculatePassingScore($training);
                return $training;
            });
        } else {
            $ongoing   = '';
            $upcoming  = '';
            $completed = '';
        }
        // For get notifications
        $user            = User::find(Auth::user()->id);
        $notifications   = [];
        $trainingResults = $this->userTrainingReport();

        return View::make("admin.$this->model.userTraining", compact('ongoing', 'upcoming', 'completed', 'trainingResults', 'notifications'));
    }
    private function calculatePassingScore($training)
    {
        // Implement the logic to calculate the passing score for the given training
        $userId  = Auth::user()->id;
        $courses = $training->training_courses;

        $totalMinimumMark = 0;
        $totalTestCount   = 0;

        foreach ($courses as $course) {
            $test = Test::find($course->test_id);
            if ($test) {
                $totalMinimumMark += $test->minimum_marks;
                $totalTestCount++;
            }
        }
        // dd($totalMinimumMark, $totalTestCount);
        return ($totalTestCount > 0) ? ($totalMinimumMark / $totalTestCount) : 0;
    }

    public function userTrainingReport()
    {
        $userId = Auth::user()->id; // Get the authenticated user ID

        // Fetch all trainings where the user has test participants associated
        $trainings = Training::whereHas('training_courses.testParticipants', function ($query) use ($userId) {
            $query->where('trainee_id', $userId);
        })->with('training_courses.test')->get();

        $trainingResults = [];

        // Loop through each training
        foreach ($trainings as $training) {
            $courses          = $training->training_courses;
            $totalMinimumMark = 0;
            $totalTestCount   = 0;
            $totalObtainMarks = 0;
            $totalCount       = 0;
            $totalTests       = 0;

            // Loop through each course in the training
            foreach ($courses as $course) {
                $test = Test::find($course->test_id);
                if ($test) {
                    // Sum the minimum marks of all the tests
                    $totalMinimumMark += $test->minimum_marks;
                    $totalTestCount++; // Count the number of tests
                    $totalTests++;     // Total test count

                    $obtainMarks = TrainingTestResult::where('training_id', $training->id)
                        ->where('course_id', $course->id)
                        ->where('user_id', $userId)
                        ->avg('percentage');

                    if ($obtainMarks !== null) {
                        $totalObtainMarks += $obtainMarks;
                        $totalCount++;
                    }
                }
            }

            // Calculate average minimum marks across all tests
            $averageMinimumMark = ($totalTestCount > 0) ? ($totalMinimumMark / $totalTestCount) : 0;
            // Calculate average obtained marks across all tests
            $averageObtainMarks = ($totalCount > 0) ? ($totalObtainMarks / $totalCount) : 0;

            // Determine the user's status based on their average obtained marks
            $status = ($averageObtainMarks >= $averageMinimumMark) ? 'Passed' : 'Failed';

            // Store the result for this training
            $trainingResults[] = [
                'training'           => $training,
                'averageMinimumMark' => $averageMinimumMark,
                'averageObtainMarks' => $averageObtainMarks,
                'status'             => $status,
                'totalTestCount'     => $totalTests, // Total number of tests
            ];
        }

        return $trainingResults; // Return the array of training results
    }
    /**
     * Function for mark a couse as deleted
     *
     * @param  $userId  as id of couse
     * @return redirect page.
     */
    public function training_details_popup($id)
    {
        $result = Training::where('trainings.id', $id)->leftJoin('training_types', 'training_types.id', '=', 'trainings.type')->select('trainings.*', 'training_types.type as type')->get();

        // return $result;
        //   return $result;
        return View::make('admin.ManagerTraining.popup', compact('result'));
    }

    public function userTrainingDetails($training_id = 0)
    {
        $trainingDetails = Training::where('trainings.id', $training_id)->leftJoin('training_types', 'training_types.id', '=', 'trainings.type')->first();
        $trainingCourses = Course::where('training_id', $training_id)->with('CourseContentAndDocument')
            ->get();
        $userId = Auth::user()->id;

        foreach ($trainingCourses as $course) {
            $trainingDocuments = DB::table('training_documents')
                ->where('course_id', $course->id)
                ->get();

            foreach ($trainingDocuments as $document) {
                // Check if this record already exists for this user, training, course, and document
                $exists = UserAssignedTrainingProgress::where([
                    'user_id'     => $userId,
                    'training_id' => $training_id,
                    'course_id'   => $course->id,
                    'document_id' => $document->id,
                ])->exists();

                if (! $exists) {
                    // Save data in user_assigned_training_progress
                    UserAssignedTrainingProgress::create([
                        'user_id'     => $userId,
                        'training_id' => $training_id,
                        'course_id'   => $course->id,
                        'document_id' => $document->id,
                        'type'        => $document->type,
                        'is_read' => 0,
                        'status'      => 0, // unread
                    ]);
                }
            }
        }
        $trainingQuestions = DB::table('questions')->where('test_id', $training_id)
            ->get();
        $totalTrainees = DB::table('training_participants')->where('training_id', $training_id)->count();

        return View::make("admin.$this->model.userTrainingDetails", compact('training_id', 'trainingDetails', 'trainingCourses', 'trainingQuestions', 'totalTrainees'));
    }
    public function markDocumentRead(Request $request)
    {
        $documentId = $request->input('document_id');
        $courseId = $request->input('course_id');

        $progress = UserAssignedTrainingProgress::where('user_id', Auth::id())
            ->where('course_id', $courseId)
            ->where('document_id', $documentId)
            ->first();

        if ($progress) {
            $progress->status = 1;
            $progress->is_read = 1;
            $progress->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Progress not found.']);
    }
    public function userTrainingTest($training_id, $testId)
    {
        $course_id = request()->query('cou');

        $alreadySubmittedTestResult = TrainingTestResult::where('test_id', $testId)
            ->where('user_id', Auth::user()->id)
            ->where('course_id', $course_id)
            ->first();

        $testSubmitted = TrainingTestParticipants::where('test_id', $testId)
            ->where('trainee_id', Auth::user()->id)
            ->where('course_id', $course_id)
            ->first();

        $test = Test::find($testId);
        if (! $test) {
            return back()->withErrors(['message' => 'Test not found or deleted after assigning in training contact to your trainer']);
        }
        if (($alreadySubmittedTestResult && $testSubmitted && $alreadySubmittedTestResult->result == 'Passed')
            || ($testSubmitted && $testSubmitted->status == 1 && $testSubmitted->user_attempts >= $test->number_of_attempts)
        ) {

            $testAttendStatus = 2;
            $testDetails      = $test;

            return View::make('admin.ManagerTest.userTestSubmittedThanksPage', compact('testDetails', 'testAttendStatus'));
        } else {
            $trainingTest    = $test;
            $trainingDetails = Training::find($training_id);

            // Fetch questions already assigned
            $questionsAlreadyAssigned = UserAssignedTestQuestion::where('test_id', $testId)
                ->where('trainee_id', Auth::user()->id)
                ->pluck('questions_id')
                ->toArray();

            // Check if there are questions assigned to the user
            if (! empty($questionsAlreadyAssigned)) {
                $testDetails       = $test;
                $trainingQuestions = Question::whereIn('id', $questionsAlreadyAssigned)
                    ->where('test_id', $testDetails->id)
                    ->with('questionAttributes')
                    ->get();
            } else {
                $testDetails       = $test;
                $trainingQuestions = Question::inRandomOrder()
                    ->where('test_id', $testDetails->id)
                    ->with('questionAttributes')
                    ->limit($testDetails->number_of_questions)
                    ->get();

                // **Add check for no questions available**
                if ($trainingQuestions->isEmpty() && $trainingQuestions->count() < $testDetails->number_of_questions) {
                    // Redirect back with an error message
                    return back()->withErrors(['message' => 'Not enough questions available for this test. The test requires a minimum of ' . $testDetails->number_of_questions . ' questions. Please contact your admin.']);
                }

                // Assign questions to the user
                foreach ($trainingQuestions as $question) {
                    $userAssignedTestQuestion               = new UserAssignedTestQuestion();
                    $userAssignedTestQuestion->test_id      = $testId;
                    $userAssignedTestQuestion->trainee_id   = Auth::user()->id;
                    $userAssignedTestQuestion->questions_id = $question->id;
                    $userAssignedTestQuestion->save();
                }
            }

            $trainingCoursesTitle = Course::select('id', 'title')
                ->where('training_id', $training_id)
                ->first();

            $totalTrainees = DB::table('training_participants')
                ->where('training_id', $training_id)
                ->count();

            return View::make("admin.$this->model.userTest", compact('training_id', 'trainingCoursesTitle', 'trainingDetails', 'trainingQuestions', 'totalTrainees', 'trainingTest', 'testDetails'));
        }
    }

    public function userTrainingTestSubmit(Request $request)
    {
        $course_id = $request->course_id;

        // Fetch the test submission record for the user and test
        $testSubmitted = TrainingTestParticipants::where('test_id', $request->test_id)
            ->where('trainee_id', $request->user_id)
            ->where('course_id', $course_id)
            ->first();

        $attemptNumber = $testSubmitted->user_attempts ?? '1';

        // Fetch the test result record
        $testResult = TrainingTestResult::where('test_id', $request->test_id)
            ->where('user_id', $request->user_id)
            ->where('course_id', $course_id)
            ->where('attempt_number', $attemptNumber)
            ->first();

        // Fetch test details
        $testAttempts = Test::where('id', $request->test_id)->first();

        // Check if the test has already been passed or max attempts reached
        if (($testResult && $testResult->result == 'Passed') ||
            ($testSubmitted && $testSubmitted->status == 1 && $testSubmitted->user_attempts >= $testAttempts->number_of_attempts)
        ) {
            $testAttendStatus = 2;
            $testDetails      = Test::where('id', $request->test_id)->first();

            return response()->json([
                'successRedirect'  => true,
                'testDetails'      => $testDetails,
                'testAttendStatus' => $testAttendStatus,
            ]);
        } else {
            // Check if answer already exists
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
                $answer->course_id        = $course_id;
                $answer->answer_id        = $answerIds;
                $answer->valid_answer     = implode(',', $questionAnswers);
                $answer->free_text_answer = $request->free_text_answer;
                $answer->attempt_number   = $attemptNumber;
                $answer->save();
            }

            return response()->json(['success' => true]);
        }
    }

    public function userTrainingTestInfoSubmit(Request $request)
    {
        $userId     = $request->input('user_id');
        $trainingId = $request->input('training_id');
        $courseId   = $request->input('course_id');
        $testId     = $request->input('test_id');
        $status     = $request->input('status');
        TrainingTestParticipants::updateOrCreate(
            ['trainee_id' => $userId, 'test_id' => $testId],
            ['training_id' => $trainingId, 'course_id' => $courseId, 'status' => $status]
        );

        return response()->json(['message' => 'Test participant information saved successfully']);
    }

    public function userTrainingTestResultOld($id)
    {
        $userId        = Auth::user()->id;
        $answers       = Answer::where('user_id', $userId)->where('test_id', $id)->get();
        $obtainedMarks = 0;

        // Fetch the IDs of questions assigned to the user for this test
        $assignedQuestionIds = UserAssignedTestQuestion::where('trainee_id', $userId)
            ->where('test_id', $id)
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
                if ($validAnswer === $userAnswer) {
                    $obtainedMarks += $question->marks;
                }
            }

            $result = [
                'question'    => $question->question,
                'user_answer' => $answer->answer_id,
                // 'correct_options' => $correctOptions,
                'marks'       => $question->marks,
            ];
            $results[] = $result;
        }

        $percentage   = ($obtainedMarks / $totalMarks) * 100;
        $getTestMarks = Test::where('id', $id)->first();

        if ($percentage >= $getTestMarks->minimum_marks) {
            $resultStatus = 'Passed';
        } else {
            $resultStatus = 'Failed';
        }
        $trainingTestResultDetails = TrainingTestParticipants::where('trainee_id', $userId)
            ->where('test_id', $id)
            ->select('training_id', 'course_id')
            ->first();

        $testParticipants = TrainingTestParticipants::where('trainee_id', $userId)->where('test_id', $id)->where('course_id', $trainingTestResultDetails->course_id)->first();
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

        $alreadysubmitedTest = TrainingTestResult::where('test_id', $id)->where('user_id', $userId)->where('course_id', $trainingTestResultDetails->course_id)->first();
        if ($alreadysubmitedTest) {
            $alreadysubmitedTest->update([
                'total_questions'          => count($answers),
                'total_attemted_questions' => count($assignedQuestionIds),
                'total_marks'              => $totalMarks,
                'obtain_marks'             => $obtainedMarks,
                'percentage'               => $percentage,
                'result'                   => $resultStatus,
                'user_attempts'            => $trainingTestResultDetails->user_attempts,
            ]);
        } else {
            $trainingTestResult = new TrainingTestResult([
                'test_id'                  => $id,
                'training_id'              => $trainingTestResultDetails->training_id,
                'course_id'                => $trainingTestResultDetails->course_id,
                'user_id'                  => $userId,
                'total_questions'          => count($answers),
                'total_attemted_questions' => count($assignedQuestionIds),
                'total_marks'              => $totalMarks,
                'obtain_marks'             => $obtainedMarks,
                'percentage'               => $percentage,
                'result'                   => $resultStatus,
                'status'                   => 0,
                'user_attempts'            => $trainingTestResultDetails->user_attempts,
            ]);
            $trainingTestResult->save();
        }

        return view('admin.ManagerTraining.training-test-result', [
            'results'       => $results,
            'totalMarks'    => $totalMarks,
            'obtainedMarks' => $obtainedMarks,
            'percentage'    => $percentage,
            'testPar'       => $id,
            'resultStatus'  => $resultStatus,
            'testDetails'   => $getTestMarks,
        ]);
    }
    public function userTrainingTestResult(Request $request, $id, $courseId)
    {
        $userId = Auth::user()->id;
        // dd($id,$userId,$courseId);

        // Get the TrainingTestParticipants record
        $trainingTestParticipant = TrainingTestParticipants::where('test_id', $id)
            ->where('trainee_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        // Determine the attempt number
        if ($trainingTestParticipant->user_attempts) {
            $attemptNumber = $trainingTestParticipant->user_attempts;
        } else {
            $attemptNumber = 1;
        }

        // Fetch answers for the current attempt
        $answers = Answer::where('user_id', $userId)
            ->where('test_id', $id)
            ->where('attempt_number', $attemptNumber)
            ->get();

        $obtainedMarks       = 0;
        $hasFreeTextQuestion = false;
        $results             = [];

        // Fetch the IDs of questions assigned to the user for this test
        $assignedQuestionIds = UserAssignedTestQuestion::where('trainee_id', $userId)
            ->where('test_id', $id)
            ->pluck('questions_id')
            ->toArray();
        $totalMarks = Question::whereIn('id', $assignedQuestionIds)->sum('marks');

        foreach ($answers as $answer) {
            $question = $answer->question;
            if (in_array($question->id, $assignedQuestionIds)) {
                $validAnswer = explode(',', $answer->valid_answer);
                $userAnswer  = explode(',', $answer->answer_id);
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

        // Update attempt number in TrainingTestParticipants
        if ($trainingTestParticipant->user_attempts == null) {
            $trainingTestParticipant->update([
                'status'        => 1,
                'user_attempts' => 1,
            ]);
        } else {
            $trainingTestParticipant->update([
                'user_attempts' => $trainingTestParticipant->user_attempts + 1,
            ]);
        }
        // if ($obtainedMarks == 0) {
        //     return back()->withErrors(['message' => 'Sorry to say but you got 0. Please don\'t refresh the page.']);
        // }
        // Calculate the percentage
        // Calculate the percentage only if $totalMarks is greater than 0
        if ($totalMarks > 0) {
            $percentage = ($obtainedMarks / $totalMarks) * 100;
        } else {
            // Redirect with error message if $totalMarks is 0
            return redirect()->route('dashboard')->with('message', 'You have already attempted this test. Resubmission in the same attempt is not allowed. Please try again if you have remaining attempts.');
        }

        $getTestMarks = Test::where('id', $id)->first();

        $resultStatus = $percentage >= $getTestMarks->minimum_marks ? 'Passed' : 'Failed';

        // Check if a result already exists
        $alreadySubmittedTest = TrainingTestResult::where('test_id', $id)
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
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
            $trainingTestResult = new TrainingTestResult([
                'test_id'                  => $id,
                'training_id'              => $trainingTestParticipant->training_id,
                'course_id'                => $trainingTestParticipant->course_id,
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
            $trainingTestResult->save();
        }

        if ($hasFreeTextQuestion) {
            return view('admin.ManagerTraining.training-test-submit-freetext', [
                'testDetails' => $getTestMarks->title,
            ]);
        } else {
            return view('admin.ManagerTraining.training-test-result', [
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

    public function userTrainingCertificateDownload($id)
    {
        $getTestMarks = Test::where('id', $id)->first();
        $start_date   = \Carbon\Carbon::parse($getTestMarks->start_date_time);
        $end_date     = \Carbon\Carbon::parse($getTestMarks->end_date_time);
        $lengthInDays = $start_date->diffInDays($end_date);

        $data = [
            'title'        => $getTestMarks->title,
            'name'         => Auth::user()->fullname,
            'admin'        => 'Airtel Payments Bank',
            'date'         => date('m/d/Y'),
            'lengthInDays' => $lengthInDays,
            'logo'         => public_path('images/apb-main-logo.png'),

        ];

        $pdf = PDF::loadView('admin.ManagerTraining.certificate-pdf', $data);

        return $pdf->download($getTestMarks->title . 'certificate.pdf');
    }
    public function trainingCertificateDownload($id)
    {
        $getTestMarks = Training::where('id', $id)->first();
        $start_date   = \Carbon\Carbon::parse($getTestMarks->start_date_time);
        $end_date     = \Carbon\Carbon::parse($getTestMarks->end_date_time);
        $lengthInDays = $start_date->diffInDays($end_date);

        $data = [
            'title'        => $getTestMarks->title,
            'name'         => Auth::user()->fullname,
            'admin'        => 'Airtel Payments Bank',
            'date'         => date('m/d/Y'),
            'lengthInDays' => $lengthInDays,
            'logo'         => public_path('images/apb-main-logo.png'),

        ];

        $pdf = PDF::loadView('admin.ManagerTraining.certificate-pdf', $data);

        return $pdf->download($getTestMarks->title . 'certificate.pdf');
    }

    public function userTrainingTestParticipantStatus(Request $request)
    {
        $testId     = $request->input('test_id');
        $courseId   = $request->input('course_id');
        $trainingId = $request->input('training_id');
        $traineeId  = Auth::user()->id;

        // Fetch the training test participant record
        $trainingTestParticipant = TrainingTestParticipants::where('trainee_id', $traineeId)
            ->where('test_id', $testId)
            ->where('course_id', $courseId)
            ->first();

        // Update status and attempts
        if ($trainingTestParticipant->user_attempts === null) {
            $trainingTestParticipant->update([
                'status'        => 1,
                'user_attempts' => 1,
            ]);
        } else {
            $trainingTestParticipant->update([
                'status'        => 1,
                'user_attempts' => $trainingTestParticipant->user_attempts + 1,
            ]);
        }

        // Retrieve answers for the current test and user
        $answers = Answer::where('user_id', $traineeId)
            ->where('test_id', $testId)
            ->where('course_id', $courseId)
            ->where('attempt_number', $trainingTestParticipant->attempt_number)
            ->get();

        // Check if there are answers, if none, return early
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

        // Calculate total marks and obtained marks
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

                if ($question->question_type == 'FreeText') {
                    $hasFreeTextQuestion = true;
                }
            }
        }

        $percentage = $totalMarks > 0 ? ($obtainedMarks / $totalMarks) * 100 : 0;

        // Determine the result status based on the percentage
        $resultStatus = 'FreeText_Paper';
        if (! $hasFreeTextQuestion) {
            $minimumMarks = Test::where('id', $testId)->value('minimum_marks');
            $resultStatus = $percentage >= $minimumMarks ? 'Passed' : 'Failed';
        }

        // Save or update the training test result
        TrainingTestResult::updateOrCreate(
            [
                'test_id'     => $testId,
                'user_id'     => $traineeId,
                'course_id'   => $courseId,
                'training_id' => $trainingId,
            ],
            [
                'total_questions'          => count($answers),
                'total_attemted_questions' => count($assignedQuestionIds),
                'total_marks'              => $totalMarks,
                'obtain_marks'             => $obtainedMarks,
                'percentage'               => $percentage,
                'result'                   => $resultStatus,
                'user_attempts'            => $trainingTestParticipant->user_attempts,
                'status'                   => 0,
                'attempt_number'           => $trainingTestParticipant->user_attempts,
            ]
        );

        return response()->json(['message' => 'Training test status updated successfully']);
    }
} // end TrainingController
