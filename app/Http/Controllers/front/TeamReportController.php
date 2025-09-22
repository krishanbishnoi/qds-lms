<?php

namespace App\Http\Controllers\front;

use App\Exports\TestReportExport;
use App\Exports\TrainingResultsExport;
use App\Http\Controllers\BaseController;
use App\Model\Question;
use App\Model\Test;
use App\Model\TestParticipants;
use App\Model\TestResult;
use App\Model\Training;
use App\Model\TrainingTestResult;
use App\Model\User;
use Auth;
use DB;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;
use Session;
use View;

/**
 * TrainingController Controller
 *
 * Add your methods in the class below
 */
class TeamReportController extends BaseController
{
    public $model = 'report';

    public $sectionName = 'Team Reports';

    public $sectionNameSingular = 'Team Report';

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
        $user = User::where('tl_olms', Auth::user()->olms_id)->get();
        $userRegion = DB::table('regions')->where('id', Auth::user()->region)
            ->first();
        $userTlolms = Auth::user()->olms_id;
        $completedTestScore = Test::with([
            'test_participants' => function ($query) use ($userTlolms) {
                $query->whereHas('user', function ($userQuery) use ($userTlolms) {
                    $userQuery->where('tl_olms', $userTlolms);
                });
            },
            'test_results' => function ($query) use ($userTlolms) {
                $query->whereHas('user', function ($userQuery) use ($userTlolms) {
                    $userQuery->where('tl_olms', $userTlolms);
                });
            }]);

        // dd($userTlolms);

        $completedTestScore = $completedTestScore->get();

        $trainingData = Training::with([
            'training_participants' => function ($query) use ($userTlolms) {
                $query->whereHas('user', function ($userQuery) use ($userTlolms) {
                    $userQuery->where('tl_olms', $userTlolms);
                });
            },
            'training_courses', 'training_courses.test',
            'training_results' => function ($query) {
                $query->where('result', 'Passed');
            },
        ]);

        $trainingResults = $trainingData->get();

        $countCoursesWithTestId = $trainingResults->pluck('training_courses')->flatten(1)->whereNotNull('test_id')->count();

        // $userTests = TestParticipants::where('test_participants.trainee_id', Auth::user()->id)->leftJoin('tests', 'tests.id', '=', 'test_participants.test_id')
        //     ->get();

        // $completedTests = TestParticipants::where('test_participants.trainee_id', Auth::user()->id)->where('test_participants.status', 1)->leftJoin('tests', 'tests.id', '=', 'test_participants.test_id')
        //     ->get();

        // $completedTestScore = TestResult::where('user_id', Auth::user()->id)->with('test')->get();
        // For get notifications
        $user = User::find(Auth::user()->id);
        $notifications = Auth::user()->notifications()->whereNull('read_at')->get();

        // Generate or get the cached hours spent value
        $hoursSpent = Cache::remember('hours_spent_'.date('Y-m-d'), 1440, function () {
            return rand(0, 99); // Generate a random number between 0 and 99
        });

        // Fetch training results for the authenticated user
        // $trainingResults = $this->userTrainingReport();

        // return $trainingResults;

        return View::make('front.team-report.index', compact('user', 'userRegion', 'trainingResults', 'completedTestScore', 'notifications', 'message', 'hoursSpent'));
    }

    public function userTrainingReport()
    {
        $userId = Auth::user()->id; // Get the authenticated user ID
        $trainings = Training::whereHas('training_courses.testParticipants', function ($query) use ($userId) {
            $query->where('trainee_id', $userId);
        })->with('training_courses.test')->get();

        $trainingResults = [];

        foreach ($trainings as $training) {
            $courses = $training->training_courses;
            $totalMinimumMark = 0;
            $totalTestCount = 0;
            $totalObtainMarks = 0;
            $totalCount = 0;
            $totalTests = 0;

            foreach ($courses as $course) {
                $test = Test::find($course->test_id);
                if ($test) {
                    $totalMinimumMark += $test->minimum_marks;
                    $totalTestCount++;
                    $totalTests++;

                    $averageMarks = TrainingTestResult::where('training_id', $training->id)
                        ->where('course_id', $course->id)
                        ->where('user_id', $userId)
                        ->avg('percentage');

                    if ($averageMarks !== null) {
                        $totalObtainMarks += $averageMarks;
                        $totalCount++;
                    }
                }
            }

            $averageMinimumMark = ($totalTestCount > 0) ? ($totalMinimumMark / $totalTestCount) : 0;
            $averageObtainMarks = ($totalCount > 0) ? ($totalObtainMarks / $totalCount) : 0;
            $status = ($averageObtainMarks >= $averageMinimumMark) ? 'Passed' : 'Failed';

            $trainingResults[] = [
                'training' => $training,
                'averageMinimumMark' => $averageMinimumMark,
                'averageObtainMarks' => $averageObtainMarks,
                'status' => $status,
                'totalTestCount' => $totalTests,

            ];
        }

        return $trainingResults;
    }

    public function downloadTestReport($test_id)
    {
        $checkTestResults = TestResult::where('test_id', $test_id)->get();

        if ($checkTestResults->isEmpty()) {
            Session::flash('error', trans('This Test is not completed by any user.'));

            return redirect()->back();
        } else {
            $test = Test::findOrFail($test_id);

            $testParticipants = $test->test_participants;

            if (Auth::user()->user_role_id == 4) {
                $usercenter = Auth::user()->center;

                // Filter the users by center based on the user_id from testParticipants
                $userDetails = User::whereIn('id', $testParticipants->pluck('trainee_id'))
                    ->where('center', $usercenter) // Filter by center
                    ->get();
            } else {
                // Get all user details without filtering if the user role is not 4
                $userDetails = User::whereIn('id', $testParticipants->pluck('trainee_id'))->where('tl_olms', Auth::user()->olms_id)->get();
            }

            $participants = $userDetails;

            // Get the questions related to the test
            $questions = Question::where('test_id', $test_id)->get();

            // Retrieve test results and include user details
            $testResults = TestResult::where('test_id', $test_id)
                ->with('user_details')
                ->get();

            // Download the Excel report
            return Excel::download(new TestReportExport($participants, $questions, $testResults), "{$test->title}_test_report.xlsx");
        }
    }
    public function downloadReportTraining(Request $request, $trainingId)
    {
        $training = Training::findOrFail($trainingId);
        $trainingTitle = $training->title;
        $courses = $training->training_courses;
    
        $usercenter = Auth::user()->center; // Get the authenticated user's center ID
        $authUserRoleId = Auth::user()->user_role_id; // Get the authenticated user's role ID
    
        $allUserDetails = [];
        $noTestsFound = true; // Flag to check if any tests were found
    
        foreach ($courses as $course) {
            $test = Test::find($course->test_id);
    
            if (!$test) {
                // Skip this course and continue with the next one
                continue;
            }
    
            $noTestsFound = false; // At least one test was found
    
            // Get all users' details
            $userDetailsQuery = TrainingTestResult::where('training_id', $trainingId)
                ->where('course_id', $course->id)
                ->orWhere('test_id', $test->id);
    
            if ($authUserRoleId == 4) {
                $userDetailsQuery->whereHas('user', function ($query) use ($usercenter) {
                    $query->where('center', $usercenter);
                });
            }
            if ($authUserRoleId == 3) {
                $userDetailsQuery->whereHas('user', function ($query) use ($usercenter) {
                    // $query->where('center', $usercenter);
                    $query->where('tl_olms', Auth::user()->olms_id);
                });
            }
    
            $userDetails = $userDetailsQuery->get(); // Fetch all user details
    
            if ($userDetails->isEmpty()) {
                Session::flash('error', trans('This training is not completed by any user.'));
                return redirect()->back();
            }
    
            // Add user details to array for export
            $allUserDetails = array_merge($allUserDetails, $userDetails->toArray());
        }
    
        // If no tests were found in any course
        if ($noTestsFound) {
            Session::flash('error', trans('This training does not have any tests, so the report is not available.'));
            return redirect()->back();
        }
    
        // Proceed with exporting the results
        $export = new TrainingResultsExport($trainingTitle, $allUserDetails);
        $fileName = 'training-results-report-' . $training->name . '.xlsx';
    
        Session::flash('success', trans('Training report downloaded successfully'));
    
        return Excel::download($export, $fileName);
    }  
    // public function downloadReportTraining($trainingId)
    // {
    //     $training = Training::findOrFail($trainingId);
    //     $trainingTitle = $training->title;
    //     $courses = $training->training_courses;

    //     $totalMinimumMark = 0;
    //     $totalTestCount = 0;
    //     $totalObtainMarks = 0;
    //     $totalCount = 0;
    //     $usercenter = Auth::user()->center; // Get the authenticated user's center ID
    //     $authUserRoleId = Auth::user()->user_role_id; // Get the authenticated user's role ID

    //     foreach ($courses as $course) {
    //         $test = Test::find($course->test_id);
    //         if ($test == null) {
    //             Session::flash('error', trans('This training does not have any tests, so the report is not available.'));

    //             return redirect()->back();
    //         }

    //         // Check if the course has a test associated with it
    //         if ($test) {
    //             $totalMinimumMark += $test->minimum_marks;
    //             $totalTestCount++;

    //             // Calculate average marks for the test
    //             $averageMarksQuery = TrainingTestResult::where('training_id', $trainingId)
    //                 ->where('course_id', $course->id);

    //             // Apply center filter if the user role is 4
    //             if ($authUserRoleId == 4) {
    //                 $averageMarksQuery->whereHas('user', function ($query) use ($usercenter) {
    //                     $query->where('center', $usercenter);
    //                 });
    //             }

    //             $averageMarks = $averageMarksQuery->avg('percentage');

    //             // Get the user details, applying the center filter if role is 4
    //             $userDetailsQuery = TrainingTestResult::where('training_id', $trainingId)
    //                 ->where('course_id', $course->id)
    //                 ->orWhere('test_id', $test->id);

    //             if ($authUserRoleId == 4) {
    //                 $userDetailsQuery->whereHas('user', function ($query) use ($usercenter) {
    //                     $query->where('center', $usercenter);
    //                 });
    //             }
    //             if ($authUserRoleId == 3) {
    //                 $userDetailsQuery->whereHas('user', function ($query) use ($usercenter) {
    //                     // $query->where('center', $usercenter);
    //                     $query->where('tl_olms', Auth::user()->olms_id);
    //                 });
    //             }

    //             $userDetails = $userDetailsQuery->first();

    //             if ($averageMarks !== null) {
    //                 $totalObtainMarks += $averageMarks;
    //                 $totalCount++;
    //             }
    //         }
    //     }

    //     $averageMinimumMark = ($totalTestCount > 0) ? ($totalMinimumMark / $totalTestCount) : 0;
    //     $averageObtainMarks = ($totalCount > 0) ? ($totalObtainMarks / $totalCount) : 0;
    //     $status = ($averageObtainMarks >= $averageMinimumMark) ? 'Passed' : 'Failed';

    //     if ($userDetails == null) {
    //         Session::flash('error', trans('This training is not completed by any user.'));

    //         return redirect()->back();
    //     } else {
    //         $export = new TrainingResultsExport($trainingTitle, $userDetails, $status, $averageMinimumMark, $averageObtainMarks);
    //         $fileName = 'training-results-report-'.$training->name.'.xlsx';
    //         Session::flash('success', trans('Training report downloaded successfully'));

    //         return Excel::download($export, $fileName);
    //     }
    // }
}
