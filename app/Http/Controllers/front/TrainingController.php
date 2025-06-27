<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\BaseController;
use App\Models\Training;
use App\Models\User;
use App\Models\TrainingDocument;
use App\Models\TrainingType;
use App\Models\TrainingParticipants;
use App\Models\TrainingTestParticipants;
use App\Models\TestParticipants;
use App\Models\StateDescription;
use App\Models\UserAssignedTestQuestion;
use App\Models\Test;
use App\Models\Question;
use App\Models\QuestionAttribute;
use App\Models\Answer;
use App\Models\Course;
use App\Models\TrainingTestResult;
use App\Models\TraineeAssignedTrainingDocument;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Mail, Redirect, Response, Session, URL, View, Validator, PDF;

require_once app_path('getID3/getid3/getid3.php');

/**
 * TrainingController Controller
 *
 * Add your methods in the class below
 *
 */

class TrainingController extends BaseController
{
    public $model        =    'Training';
    public $sectionName    =    'Training';
    public $sectionNameSingular    =    'Training';

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

    public function userTrainings()
    {
        $myTrainingsIds = TrainingParticipants::where('trainee_id', Auth::user()->id)->pluck('training_id')->toArray();
        //   echo '<pre>';
        // print_r( $myTrainingsIds );
        // die;
        if (!empty($myTrainingsIds)) {
            $ongoing = Training::whereIn('trainings.id', $myTrainingsIds)->where('trainings.status', 1)->leftJoin('training_types', 'training_types.id', '=', 'trainings.type')->leftJoin('users', 'users.id', '=', 'trainings.user_id')->select('trainings.*', 'training_types.type as type', 'users.first_name as created_by')->orderBy('trainings.id', 'DESC')->get();
            $upcoming = Training::whereIn('trainings.id', $myTrainingsIds)->where('trainings.status', 0)->leftJoin('training_types', 'training_types.id', '=', 'trainings.type')->leftJoin('users', 'users.id', '=', 'trainings.user_id')->select('trainings.*', 'training_types.type as type', 'users.first_name as created_by')->orderBy('trainings.id', 'DESC')->get();
            $completed = Training::whereIn('trainings.id', $myTrainingsIds)->where('trainings.status', 2)->leftJoin('training_types', 'training_types.id', '=', 'trainings.type')->leftJoin('users', 'users.id', '=', 'trainings.user_id')->select('trainings.*', 'training_types.type as type', 'users.first_name as created_by')->orderBy('trainings.id', 'DESC')->get();
        } else {
            $ongoing = '';
            $upcoming = '';
            $completed = '';
        }
        // For get notifications
        $user = User::find(Auth::user()->id);
        $notifications = $user->notifications->where('read_at', '');

        return  View::make("front.$this->model.userTraining", compact('ongoing', 'upcoming', 'completed', 'notifications'));
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
        $result = Training::where('trainings.id', $id)->leftJoin('training_types', 'training_types.id', '=', 'trainings.type')->select('trainings.*', 'training_types.type as type')->get();
        //   return $result;
        return View::make('front.Training.popup', compact('result'));
    }

    public function userTrainingDetails($training_id = 0)
    {
        $trainingDetails = Training::where('trainings.id', $training_id)->leftJoin('training_types', 'training_types.id', '=', 'trainings.type')->first();
        $trainingCourses = Course::where('training_id', $training_id)->with('CourseContentAndDocument')
            ->get();

        $trainingQuestions = DB::table('questions')->where('test_id', $training_id)
            ->get();
        $totalTrainees = DB::table('training_participants')->where('training_id', $training_id)->count();

        $totalCoursesCount = TraineeAssignedTrainingDocument::where('training_id', $training_id)
            ->distinct('course_id')
            ->count('course_id');


        $completedCoursesCount = TraineeAssignedTrainingDocument::where('training_id', $training_id)
            ->where('status', 1)
            ->distinct('course_id')
            ->count('course_id');

        return  View::make("front.Training.userTrainingDetails", compact('training_id', 'trainingDetails', 'trainingCourses', 'trainingQuestions', 'totalTrainees', 'totalCoursesCount', 'completedCoursesCount'));
    }

    public function userTrainingDocumentProgress(Request $request)
    {
        $completedDocument = TraineeAssignedTrainingDocument::where('user_id', Auth::user()->id)->where('course_id', $request->input('course_id'))->where('document_id', $request->input('content_id'))->first();
        if ($completedDocument) {
            $completedDocument->update([
                'status' => 1,
            ]);
        } else {
            $trainingId = Course::where('id', $request->course_id)->value('training_id');
            TraineeAssignedTrainingDocument::create([
                'training_id' => $trainingId,
                'course_id' => $request->course_id,
                'document_id' => $request->content_id,
                'user_id' =>  Auth::user()->id,
                'type' =>  $request->content_type,
                'duration' =>  $request->content_length,
                'status' => 1,
            ]);
        }
        return response()->json(['success' => true]);
    }

    public function getVideoDuration()
    {
        $filePath = public_path('training_document/sample-video-test.mp4');

        $getID3 = new \getID3;

        // Analyze the file
        $fileInfo = $getID3->analyze($filePath);

        // Get the duration
        $duration = $fileInfo['playtime_seconds'];

        // You can format the duration as needed
        $formattedDuration = gmdate('H:i:s', $duration);

        return $formattedDuration;
    }

    public function userTrainingTest($training_id, $courseId, $testId)
    {
        // dd($training_id,$courseId,$testId);
        $testAlreadySubmited = TrainingTestParticipants::where('training_id', $training_id)
            ->where('course_id', $courseId)
            ->where('test_id', $testId)
            ->where('trainee_id', Auth::user()->id)
            ->first();

        $trainingTest = Test::where('id', $testId)->first();
        if ($trainingTest) {
            $trainingDetails = Training::where('trainings.id', $training_id)->leftJoin('training_types', 'training_types.id', '=', 'trainings.type')->first();
            // $trainingQuestions = Question::inRandomOrder()->where( 'test_id', $trainingTest->id )->with( 'questionAttributes' )
            // 	->limit( $trainingTest->number_of_questions )->get();
            $questionsAlreadyAssigned = UserAssignedTestQuestion::where('test_id', $testId)
                ->where('trainee_id', Auth::user()->id)
                ->pluck('questions_id')
                ->toArray();
            if ($questionsAlreadyAssigned) {

                $testDetails = Test::where('tests.id', $testId)->first();

                $trainingQuestions = Question::whereIn('id', $questionsAlreadyAssigned)
                    ->where('test_id', $testDetails->id)
                    ->with('questionAttributes')
                    ->get();
            } else {

                $testDetails = Test::where('tests.id', $testId)->first();
                $trainingQuestions = Question::inRandomOrder()->where('test_id', $testDetails->id)->with('questionAttributes')
                    ->limit($testDetails->number_of_questions)->get();

                foreach ($trainingQuestions as $question) {
                    $userAssignedTestQuestion = new UserAssignedTestQuestion();
                    $userAssignedTestQuestion->test_id = $testId;
                    $userAssignedTestQuestion->trainee_id = Auth::user()->id;
                    $userAssignedTestQuestion->questions_id = $question->id;
                    $userAssignedTestQuestion->save();
                }
            }
            $trainingCoursesTitle = Course::select('id', 'title')->where('training_id', $training_id)
                ->first();

            $totalTrainees = DB::table('training_participants')->where('training_id', $training_id)
                ->count();
            // DD($trainingDetails,$trainingTest,$trainingQuestions);
            return View::make("front.$this->model.userTest", compact('training_id', 'courseId', 'trainingCoursesTitle', 'trainingDetails', 'trainingQuestions', 'totalTrainees', 'trainingTest', 'testDetails'));
        } else {
            return redirect()->back()->with('This test not found. Contact to admin.');
        }
    }
    // end userTrainingTest()

    public function userTrainingTestSubmit(Request $request)
    {
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

    // public function userTrainingTestSubmit_old(Request $request)
    // {
    //     $anserAlreadyExist = Answer::where('test_id', $request->test_id)->where('question_id', $request->question_id)
    //         ->where('user_id', $request->user_id)->first();
    //     // return $anserAlreadyExist;
    //     if ($anserAlreadyExist) {
    //         $anserAlreadyExist->answer_id = $request->answer_id;
    //         $anserAlreadyExist->save();
    //     } else {
    //         $answer = new Answer();
    //         $answer->test_id = $request->test_id;
    //         $answer->question_id = $request->question_id;
    //         $answer->answer_id = $request->answer_id;
    //         $answer->user_id = $request->user_id;
    //         $question = $answer->question;
    //         $questionAnswer = $question->questionAnswer;
    //         // Convert questionAttributes array to a Laravel Collection
    //         $questionAttributesCollection = collect($questionAnswer);
    //         // Filter the options to get only the correct ones
    //         $correctOptions = $questionAttributesCollection->where('is_correct', 1)->pluck('id')->toArray();
    //         // return $correctOptions;
    //         $correctOptionString = implode('', $correctOptions);
    //         $answer->valid_answer = $correctOptionString;
    //         $answer->save();
    //     }
    //     return response()->json(['success' => true]);
    // }

    public function userTrainingTestInfoSubmit(Request $request)
    {
        // dd($request);
        $userId = $request->input('user_id');
        $trainingId = $request->input('training_id');
        $courseId = $request->input('course_id');
        $testId = $request->input('test_id');

        $testNoOfAttempt = Test::where('id', $testId)->first()->number_of_attempts;

        $trainingTestParticipants = TrainingTestParticipants::where('trainee_id', $userId)->where('test_id', $testId)->where('course_id', $courseId)->first();

        if ($trainingTestParticipants) {

            $trainingTestParticipants->update([
                'user_attempts' => $trainingTestParticipants->user_attempts + 1,
            ]);
        } else {

            $updateInfo = new TrainingTestParticipants();
            $updateInfo->trainee_id = $userId;
            $updateInfo->test_id = $testId;
            $updateInfo->training_id = $trainingId;
            $updateInfo->course_id = $courseId;
            $updateInfo->status = 1;
            $updateInfo->number_of_attempts = $testNoOfAttempt;
            $updateInfo->user_attempts = 1;
            $updateInfo->save();
        }

        return response()->json(['message' => 'Test participant information saved successfully']);
    }

    public function userTrainingTestResult_old($id)
    {
        $userId = Auth::user()->id;
        $answers = Answer::where('user_id', $userId)->where('test_id', $id)->get();
        $totalMarks = 0;
        $obtainedMarks = 0;
        foreach ($answers as $answer) {
            $question = $answer->question;
            $questionAnswer = $question->questionAnswer;
            $questionAttributesCollection = collect($questionAnswer);
            $correctOptions = $questionAttributesCollection->where('is_correct', 1)->pluck('id')->toArray();
            $validAnswer = explode(',', $answer->valid_answer);
            $userAnswer = explode(',', $answer->answer_id);
            sort($validAnswer);
            sort($userAnswer);
            $totalMarks += $question->marks;
            if ($validAnswer === $userAnswer) {
                $obtainedMarks += $question->marks;
            }
            $result = [
                'question' => $question->question,
                'user_answer' => $answer->answer_id,
                'is_correct' => $obtainedMarks,
                // 'correct_options' => $correctOptions,
                'marks' => $question->marks
            ];
            $results[] = $result;
        }
        TestParticipants::where('trainee_id', $userId)->where('test_id', $id)->update(['status' => 1]);
        $percentage = ($obtainedMarks / $totalMarks) * 100;
        return response()->json([
            'results' => $results,
            'total_marks' => $totalMarks,
            'obtained_marks' => $obtainedMarks,
            'percentage' => $percentage,
            'testPar' => $id,
        ]);
    }

    // // TestController function userTestResult
    public function userTrainingTestResult($id)
    {
        // dd($id);
        $userId = Auth::user()->id;
        $answers = Answer::where('user_id', $userId)->where('test_id', $id)->get();
        $totalMarks = 0;
        $obtainedMarks = 0;
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
                if ($validAnswer === $userAnswer) {
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
        }

        // TestParticipants::where('trainee_id', $userId)->where('test_id', $id)->update(['status' => 1]);
        $percentage = ($obtainedMarks / $totalMarks) * 100;
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

        $alreadysubmitedTest = TrainingTestResult::where('test_id', $id)->where('user_id', $userId)->where('course_id', $trainingTestResultDetails->course_id)->first();
        // DD($alreadysubmitedTest);
        if ($alreadysubmitedTest) {
            $alreadysubmitedTest->update([
                'total_questions' => count($answers),
                'total_attemted_questions' => count($assignedQuestionIds),
                'total_marks' => $totalMarks,
                'obtain_marks' => $obtainedMarks,
                'percentage' => $percentage,
                'result' => $resultStatus,
                'status' => 1,

            ]);
        } else {
            $trainingTestResult = new TrainingTestResult([
                'test_id' => $id,
                'training_id' => $trainingTestResultDetails->training_id,
                'course_id' => $trainingTestResultDetails->course_id,
                'user_id' => $userId,
                'total_questions' => count($answers),
                'total_attemted_questions' => count($assignedQuestionIds),
                'total_marks' => $totalMarks,
                'obtain_marks' => $obtainedMarks,
                'percentage' => $percentage,
                'result' => $resultStatus,
                'status' => 1,
            ]);
            $trainingTestResult->save();
        }



        ###############################################################################################################
        $training = Training::findOrFail($trainingTestResultDetails->training_id);

        $courses = $training->training_courses;

        $totalMinimumMark = 0;
        $totalTestCount = 0;
        $totalObtainMarks = 0;
        $totalCount = 0;
        foreach ($courses as $course) {
            $test = Test::find($course->test_id);
            // Check if the course has a test associated with it
            if ($test) {
                $totalMinimumMark += $test->minimum_marks;
                $totalTestCount++;
                $averageMarks = TrainingTestResult::where('training_id', $trainingTestResultDetails->training_id)->where('user_id', $userId)
                    ->avg('obtain_marks');
                $userDetails = TrainingTestResult::where('training_id', $trainingTestResultDetails->training_id)->where('course_id', $course->id)->orWhere('test_id', $test->id)->first();
                if ($averageMarks !== null) {
                    $totalObtainMarks += $averageMarks;
                    $totalCount++;
                }
            }
        }
        $totalAttendedTestCount = TrainingTestResult::where('training_id', $trainingTestResultDetails->training_id)->where('user_id', $userId)->count();

        $averageMinimumMark = ($totalTestCount > 0) ? ($totalMinimumMark / $totalTestCount) : 0;
        $averageObtainMarks = ($totalCount > 0) ? ($totalObtainMarks / $totalCount) : 0;

        $OverAllStatus = ($averageObtainMarks >= $averageMinimumMark) ? 'Passed' : 'Failed';

        $start_date = \Carbon\Carbon::parse($training->start_date_time);
        $end_date = \Carbon\Carbon::parse($training->end_date_time);
        $lengthInDays = $start_date->diffInDays($end_date);
        #####################################################################################################################################################################

        return view('front.Training.training-test-result', [
            'trainingId' =>  $training->id,
            'results' => $results,
            'totalMarks' => $totalMarks,
            'obtainedMarks' => $obtainedMarks,
            'percentage' => $percentage,
            'testPar' => $id,
            'resultStatus' => $resultStatus,
            'testDetails' => $getTestMarks,
            'admin' => $user->parentManager->fullname,
            'trainingData' => $training,
            'totalTestCount' => $totalTestCount,
            'totalAttendedTestCount' => $totalAttendedTestCount,
            'averageObtainMarks' => $averageObtainMarks,
            'OverAllStatus' => $OverAllStatus,
            'lengthInDays' => $lengthInDays,

        ]);
    }

    public function userTrainingCertificateDownload($id)
    {
        // // For over all Training certification
        $trainingData = Training::where('id', $id)->first();
        $start_date = \Carbon\Carbon::parse($trainingData->start_date_time);
        $end_date = \Carbon\Carbon::parse($trainingData->end_date_time);
        $lengthInDays = $start_date->diffInDays($end_date);

        $user = User::where('id', Auth::user()->id)->with('parentManager')->first();
        $data = [
            'title' => $trainingData->title,
            'name' => Auth::user()->fullname,
            'admin' => $user->parentManager->fullname,
            'date' => date('m/d/Y'),
            'lengthInDays' => $lengthInDays,
            'logo' => public_path('lms-img/qdegrees-logo.png'),
            'background_img' => asset('front/img/backgroundimage.png')
        ];

        $pdf = PDF::loadView('front.Training.certificate-pdf', $data);

        return $pdf->download($trainingData->title . '-certificate.pdf');



        // // For Single test certification from front/TestControllor
        // $getTestMarks = Test::where('id', $id)->first();
        // $start_date = \Carbon\Carbon::parse($getTestMarks->start_date_time);
        // $end_date = \Carbon\Carbon::parse($getTestMarks->end_date_time);
        // $lengthInDays = $start_date->diffInDays($end_date);
        // $user = User::where('id', Auth::user()->id)->with('parentManager')->first();
        // $data = [
        //     'title' => $getTestMarks->title,
        //     'name' => Auth::user()->fullname,
        //     'admin' => $user->parentManager->fullname,
        //     'date' => date('m/d/Y'),
        //     'lengthInDays' => $lengthInDays,
        //     'logo' => public_path('lms-img/qdegrees-logo.png'),
        //     'background_img' => asset('front/img/backgroundimage.png')
        // ];

        // $pdf = PDF::loadView('front.Training.certificate-pdf', $data);

        // return $pdf->download($getTestMarks->title . 'certificate.pdf');
    }
    public function userFeedback()
    {
        //  return "here";
        return View::make('front.Training.feedback');
    }
    public function storeFeedback(Request $request)
    {
        $request->validate([
            'feedback' => 'required',
        ]);
        $alreadyFeedback = Feedback::where('activity_type_id', $request->activity_type_id)->where('user_id', $request->user_id)->first();
        if ($alreadyFeedback) {
            $alreadyFeedback->update([
                'feedback' => $request->feedback,
            ]);
            return redirect()->route('front.dashboard')->with('success', 'Feedback updated successfully');
        } else {

            $feedback = new Feedback();
            $feedback->activity_type_id = $request->activity_type_id;
            $feedback->type = $request->type;
            $feedback->feedback = $request->feedback;
            $feedback->feedback = $request->user_id;

            $feedback->save();
            return redirect()->route('front.dashboard')->with('success', 'Feedback saved successfully');
        }
    }


    public function getDocumentDuration(Request $request)
    {
        $record = TraineeAssignedTrainingDocument::where('user_id', auth()->id())
            ->where('course_id', $request->course_id)
            ->where('document_id', $request->content_id)
            ->first();

        return response()->json(['duration' => $record?->duration ?? 0]);
    }

    public function updateDocumentPartialDuration(Request $request)
    {
        $record = TraineeAssignedTrainingDocument::firstOrCreate([
            'user_id' => auth()->id(),
            'course_id' => $request->course_id,
            'document_id' => $request->content_id,
        ], [
            'training_id' => Course::find($request->course_id)?->training_id,
            'type' => $request->type ?? 'doc',
            'status' => 0,
        ]);

        if ($record->status != 1) {
            $record->update(['duration' => $request->duration]);
        }

        return response()->json(['success' => true]);
    }

   
}
// end TrainingController
