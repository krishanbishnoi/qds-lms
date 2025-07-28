<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Models\Test;
use App\Models\Training;
use App\Models\TestResult;
use App\Models\TrainingTestResult;
use App\Models\TrainingParticipants;
use App\Models\StateDescription;
use App\Exports\TestResultsExport;
use App\Exports\TrainingDocumentResultsExport;
use App\Exports\TrainingResultsExport;
use App\Exports\TrainingWithTestResultsExport;
use App\Exports\userTestReportExport;
use App\Models\Course;
use App\Models\Question;
use App\Models\TraineeAssignedTrainingDocument;
use App\Models\TrainingDocument;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Mail, Redirect, Response, Session, URL, View, Validator;

/**
 * ReportsController Controller
 *
 * Add your methods in the class below
 *
 */
class ReportsController extends BaseController
{

    public $model        =    'Reports';
    public $sectionName    =    'Report';
    public $sectionNameSingular    =    'Report';

    public function __construct()
    {
        parent::__construct();
        View::share('modelName', $this->model);
        View::share('sectionName', $this->sectionName);
        View::share('sectionNameSingular', $this->sectionNameSingular);
    }

    public function test(Request $request)
    {
        $testType = $request->input('test_type', 'regular_test');
        if ($testType == 'regular_test') {
            $query = Test::orderBy('created_at', 'desc')->with([
                'test_participants',
                'test_results',
            ])->where('type', 'regular_test');
            // Apply Search Filter
            if ($request->filled('search')) {
                $query->where('title', 'LIKE', '%' . $request->input('search') . '%');
            }

            // // If the authenticated user's role ID is 4 (training manager), filter participants based on their data
            // if (Auth::user()->user_role_id == 4) {
            // }
            $allTest = $query->get();
            // return $allTest;
        } elseif ($testType == 'training_test') {
            $query = Test::where('type', 'training_test')->with([
                'test_participants',
                'test_results',
            ]);

            // Apply Search Filter
            if ($request->filled('search')) {
                $query->where('title', 'LIKE', '%' . $request->input('search') . '%');
            }
            // if (Auth::user()->user_role_id == 4) {
            // }
            $allTest = $query->get();
            // return $allTest;
        } else {
            $allTest = [];
        }

        return View::make("admin.Reports.test-index", compact('allTest', 'testType'));
    }
    public function training(Request $request)
    {
        $allTraining = Training::orderBy('created_at', 'desc')->with([
            'training_participants',
            'training_participants_count',
            'training_courses',
            'training_courses.test',
            'training_results' => function ($query) {
                $query->where('result', 'Passed');
            },
        ]);

        // Filter training participants by data if user role is 4 (training manager)
        // if (Auth::user()->user_role_id == 4) {
        // }

        $allTraining = $allTraining->get();

        $countCoursesWithTestId = $allTraining->pluck('training_courses')->flatten(1)->whereNotNull('test_id')->count();
        return View::make("admin.Reports.training-index", compact('allTraining',));
    }
    // public function downloadReport($test_id)
    // {
    //     $test = Test::find($test_id);
    //     $testResults = TestResult::where('test_id', $test_id)->get();
    //     if ($testResults->isEmpty()) {
    //         Session::flash('error', trans("This Test is not completed by any user."));
    //         return redirect()->back();
    //     } else {
    //         $export = new TestResultsExport($test, $testResults);

    //         Session::flash('success', trans("Training report downloaded successfully"));
    //         $fileName = 'test-results' . $test_id . '.xlsx';

    //         return Excel::download($export, $fileName);
    //     }
    // }


    public function downloadReport($test_id)
    {
        $checkTestResults = TestResult::where('test_id', $test_id)->get();

        if ($checkTestResults->isEmpty()) {
            Session::flash('error', trans("This Test is not completed by any user."));
            return redirect()->back();
        } else {
            $test = Test::findOrFail($test_id);

            $testParticipants = $test->test_participants;
            // Get all user details without filtering if the user role is not 4
            $userDetails = User::whereIn('id', $testParticipants->pluck('trainee_id'))->get();

            $participants = $userDetails;

            // Get the questions related to the test
            $questions = Question::where('test_id', $test_id)->get();

            // Retrieve test results and include user details
            $testResults = TestResult::where('test_id', $test_id)
                ->with('user_details')
                ->get();

            // Download the Excel report
            return Excel::download(new userTestReportExport($participants, $questions, $testResults), "{$test->title}_test_report.xlsx");
        }
    }


    public function downloadReportTraining(Request $request, $trainingId)
    {
        $training      = Training::findOrFail($trainingId);
        $trainingTitle = $training->title;
        $courses       = $training->training_courses;
        $allUserDetails = [];
        $allUserAssined = [];
        $noTestsFound   = true; // Flag to check if any tests were found

        foreach ($courses as $course) {
            $test = Test::find($course->test_id);

            if (! $test) {
                // Skip this course and continue with the next one
                continue;
            }

            $noTestsFound = false; // At least one test was found

            // Get all users' details
            $userDetailsQuery = TrainingTestResult::where('training_id', $trainingId)
                ->where('course_id', $course->id)
                ->orWhere('test_id', $test->id);

            $userDetails = $userDetailsQuery->get(); // Fetch all user details
            // dd($userDetails);
            if ($userDetails->isEmpty()) {
                Session::flash('error', trans('This training is not completed by any user.'));
                return redirect()->back();
            }
            // Add user details to array for export
            $allUserDetails = array_merge($allUserDetails, $userDetails->toArray());
        }

        // If no tests were found in any course
        if ($noTestsFound) {
            $userassignedQuery = TrainingParticipants::where('training_id', $trainingId);
            $userAssined = $userassignedQuery->get();
            // dd($userAssined);
            // Fetch all user details
            // dd($userAssined);
            if ($userAssined->isEmpty()) {
                Session::flash('error', trans('This training is not completed by any user.'));
                return redirect()->back();
            }
            // Add user details to array for export
            $allUserAssined = array_merge($allUserAssined, $userAssined->toArray());
            $export = new TrainingDocumentResultsExport($trainingTitle, $allUserAssined, $courses);
            $fileName = 'training-documents-report-' . $trainingTitle . '.xlsx';
            Session::flash('success', trans('Training report downloaded successfully'));

            return Excel::download($export, $fileName);
        }

        // Proceed with exporting the results
        $export   = new TrainingWithTestResultsExport($trainingTitle, $allUserDetails);
        $fileName = 'training-results-report-' . $trainingTitle . '.xlsx';

        Session::flash('success', trans('Training report downloaded successfully'));

        return Excel::download($export, $fileName);
    }



    public function showTrainingUsers($trainingId)
    {
        $training = Training::findOrFail($trainingId);

        $participantIds = TrainingParticipants::where('training_id', $trainingId)->pluck('trainee_id')->toArray();

        $users = User::whereIn('id', $participantIds)->get();

        $courseIds = Course::where('training_id', $trainingId)->pluck('id')->toArray();
        $documentIds = TrainingDocument::whereIn('course_id', $courseIds)->pluck('id')->toArray();
        $totalDocuments = count($documentIds);

        $userProgress = [];

        foreach ($users as $user) {
            $completedDocs = TraineeAssignedTrainingDocument::where('user_id', $user->id)
            ->where('training_id', $trainingId)
            ->where('status', 1)
            ->whereIn('document_id', $documentIds)
            ->count();

            $completionPercentage = $totalDocuments > 0
                ? round(($completedDocs / $totalDocuments) * 100, 2)
                : 0;

            $userProgress[] = [
                'name' => $user->fullname,
                'email' => $user->email,
                'total_documents' => $totalDocuments,
                'completed_documents' => $completedDocs,
                'completion_percentage' => $completionPercentage,
                'status' => $completionPercentage == 100 ? 'Completed' : 'In Progress',
            ];
        }

        return view('admin.Reports.training_users', compact('training', 'userProgress'));
    }



    // public function downloadReportTraining(Request $request, $trainingId)
    // {
    //     $training = Training::findOrFail($trainingId);
    //     $trainingTitle = $training->title;
    //     $courses = $training->training_courses;

    //     $totalMinimumMark = 0;
    //     $totalTestCount = 0;
    //     $totalObtainMarks = 0;
    //     $totalCount = 0;
    //     foreach ($courses as $course) {
    //         $test = Test::find($course->test_id);
    //         // Check if the course has a test associated with it
    //         if ($test) {
    //             $totalMinimumMark += $test->minimum_marks;
    //             $totalTestCount++;
    //             $averageMarks = TrainingTestResult::where('training_id', $trainingId)
    //                 ->where('course_id', $course->id)
    //                 ->avg('obtain_marks');
    //             $userDetails = TrainingTestResult::where('training_id', $trainingId)->where('course_id', $course->id)->orWhere('test_id', $test->id)->first();
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
    //         // dd('here');
    //         Session::flash('error', trans("This training is not completed by any user."));
    //         return redirect()->back();
    //     } else {
    //         $export = new TrainingResultsExport($trainingTitle, $userDetails, $status, $averageMinimumMark, $averageObtainMarks);
    //         $fileName = 'training-results-report' . $training->name . '.xlsx';
    //         Session::flash('success', trans("Training report downloaded successfully"));
    //         return Excel::download($export, $fileName);
    //     }
    // }
}// end ReportsController
