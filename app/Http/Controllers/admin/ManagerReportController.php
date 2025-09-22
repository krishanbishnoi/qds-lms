<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Model\Test;
use App\Model\TestParticipants;
use App\Model\TestResult;
use App\Model\Training;
use App\Model\TrainingTestResult;
use App\Model\User;
use Auth;
use DB;
use Illuminate\Support\Facades\Cache;
use View;

/**
 * TrainingController Controller
 *
 * Add your methods in the class below
 */
class ManagerReportController extends BaseController
{
    public $model = 'MyReport';

    public $sectionName = 'My Report';

    public $sectionNameSingular = 'My Report';

    public function __construct()
    {
        parent::__construct();
        View::share('modelName', $this->model);
        View::share('sectionName', $this->sectionName);
        View::share('sectionNameSingular', $this->sectionNameSingular);
    }

    public function index()
    {
        $message = request('message'); // Get the message from the query parameter
        // dd($message);
        $user = User::where('id', Auth::user()->id)->first();
        $userRegion = DB::table('regions')->where('id', Auth::user()->region)
            ->first();

        $userTests = TestParticipants::where('test_participants.trainee_id', Auth::user()->id)->leftJoin('tests', 'tests.id', '=', 'test_participants.test_id')
            ->get();

        $completedTests = TestParticipants::where('test_participants.trainee_id', Auth::user()->id)->where('test_participants.status', 1)->leftJoin('tests', 'tests.id', '=', 'test_participants.test_id')
            ->get();

        $completedTestScore = TestResult::where('user_id', Auth::user()->id)->with('test')->get();
        // For get notifications
        $user = User::find(Auth::user()->id);
        $notifications = [];
        // Generate or get the cached hours spent value
        $hoursSpent = Cache::remember('hours_spent_' . date('Y-m-d'), 1440, function () {
            return rand(0, 99); // Generate a random number between 0 and 99
        });

        // Fetch training results for the authenticated user
        $trainingResults = $this->userTrainingReport();

        return View::make('admin.MyReport.index', compact('user', 'userRegion', 'trainingResults', 'userTests', 'completedTests', 'completedTestScore', 'notifications', 'message', 'hoursSpent'));
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
            $courses = $training->training_courses;
            $totalMinimumMark = 0;
            $totalTestCount = 0;
            $totalObtainMarks = 0;
            $totalCount = 0;
            $totalTests = 0;

            // Loop through each course in the training
            foreach ($courses as $course) {
                $test = Test::find($course->test_id);
                if ($test) {
                    // Sum the minimum marks of all the tests
                    $totalMinimumMark += $test->minimum_marks;
                    $totalTestCount++; // Count the number of tests
                    $totalTests++; // Total test count


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
                'training' => $training,
                'averageMinimumMark' => $averageMinimumMark,
                'averageObtainMarks' => $averageObtainMarks,
                'status' => $status,
                'totalTestCount' => $totalTests, // Total number of tests
            ];
        }

        return $trainingResults; // Return the array of training results
    }

}
