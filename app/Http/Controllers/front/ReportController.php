<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\BaseController;
use App\Models\Training;
use App\Models\User;
use App\Models\TrainingDocument;
use App\Models\TrainingType;
use App\Models\TrainingParticipants;
use App\Models\StateDescription;
use App\Models\Question;
use App\Models\QuestionAttribute;
use App\Models\Answer;
use App\Models\Test;
use App\Models\TestParticipants;
use App\Models\TrainingTestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Carbon\CarbonPeriod;


use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Mail, Redirect, Response, Session, URL, View, Validator;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Tymon\JWTAuth\Contracts\Providers\Auth as ProvidersAuth;

/**
 * Training Controller
 *
 * Add your methods in the class below
 *
 */
class ReportController extends BaseController
{

    public $model        =    'report';
    public $sectionName    =    'Report';
    public $sectionNameSingular    =    'Report';

    public function __construct()
    {
        parent::__construct();
        View::share('modelName', $this->model);
        View::share('sectionName', $this->sectionName);
        View::share('sectionNameSingular', $this->sectionNameSingular);
    }


    public function userReport()
    {
        $user = User::where('id', Auth::user()->id)->with('parentManager')->first();

        $userRegion = DB::table('regions')->where('id', Auth::user()->region)->first();

        $userTrainings = TrainingParticipants::select('training_id')->where('trainee_id', Auth::user()->id)->get();

        $reportData = [];

        foreach ($userTrainings as $training) {
            $trainingInfo = DB::table('trainings')
                ->join('training_types', 'trainings.type', '=', 'training_types.id')
                ->where('trainings.id', $training->training_id)
                ->select('trainings.title as trainingName', 'training_types.type as trainingType', 'trainings.end_date_time')
                ->first();

            $isExpired = now() > $trainingInfo->end_date_time;
            $timeLeft = $isExpired ? 'Expired' : now()->diffInHours($trainingInfo->end_date_time) . ' hours';

            $totalMinimumMarks = DB::table('courses')
                ->join('tests', 'courses.test_id', '=', 'tests.id')
                ->where('courses.training_id', $training->training_id)
                ->sum('tests.minimum_marks');

            $totalObtainMarks = DB::table('training_test_results')
                ->where('training_id', $training->training_id)
                ->where('user_id', Auth::user()->id)
                ->sum('obtain_marks');

            $status = $totalObtainMarks >= $totalMinimumMarks ? 'Passed' : 'Failed';

            if ($status === 'Failed' && $isExpired) {
                $timeLeft = 'Expired';
            }

            $reportData[] = [
                'trainingName' => $trainingInfo->trainingName,
                'trainingType' =>  $trainingInfo->trainingType,
                'timeLeft' => $timeLeft,
                'passingMarks' => $totalMinimumMarks,
                'obtainedMarks' => $totalObtainMarks,
                'status' => $status,
            ];
        }

        $userTests = TestParticipants::where('test_participants.trainee_id', Auth::user()->id)->leftJoin('tests', 'tests.id', '=', 'test_participants.test_id')
            ->get();
        $completedTests = TestParticipants::where('test_participants.trainee_id', Auth::user()->id)->where('test_participants.status', 1)->where('tests.type', 'regular_test')->leftJoin('tests', 'tests.id', '=', 'test_participants.test_id')
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

        // For get notifications
        $user = User::find(Auth::user()->id);
        $notifications = $user->notifications->where('read_at', '');

        return view("front.$this->model.index", compact('user', 'userRegion', 'userTrainings', 'userTests', 'completedTests', 'notifications', 'reportData'));
    }


    public function downloadReportTraining(Request $request, $trainingId)
    {
        $training = Training::findOrFail($trainingId);
        $trainingTitle = $training->title;
        $courses = $training->training_courses;

        $totalMinimumMarks = 0;
        $totalTestCount = 0;
        $totalObtainMarks = 0;
        $totalCount = 0;
        foreach ($courses as $course) {
            $test = Test::find($course->test_id);
            // Check if the course has a test associated with it
            if ($test) {
                $totalMinimumMarks += $test->minimum_marks;
                $totalTestCount++;
                $averageMarks = TrainingTestResult::where('training_id', $trainingId)
                    ->where('course_id', $course->id)
                    ->avg('obtain_marks');
                $userDetails = TrainingTestResult::where('training_id', $trainingId)->where('course_id', $course->id)->orWhere('test_id', $test->id)->first();
                if ($averageMarks !== null) {
                    $totalObtainMarks += $averageMarks;
                    $totalCount++;
                }
            }
        }

        $averageMinimumMark = ($totalTestCount > 0) ? ($totalMinimumMarks / $totalTestCount) : 0;
        $averageObtainMarks = ($totalCount > 0) ? ($totalObtainMarks / $totalCount) : 0;
        $status = ($averageObtainMarks >= $averageMinimumMark) ? 'Passed' : 'Failed';
        if ($userDetails == null) {
            // dd('here');
            Session::flash('error', trans("This training is not completed by any user."));
            return redirect()->back();
        } else {
            $export = new TrainingResultsExport($trainingTitle, $userDetails, $status, $averageMinimumMark, $averageObtainMarks);
            $fileName = 'training-results-report' . $training->name . '.xlsx';
            Session::flash('success', trans("Training report downloaded successfully"));
            return Excel::download($export, $fileName);
        }
    }
}// end ReportController
