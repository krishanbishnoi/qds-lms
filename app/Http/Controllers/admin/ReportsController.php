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
use App\Exports\TrainingResultsExport;
use Maatwebsite\Excel\Facades\Excel;


use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Request, Mail, Redirect, Response, Session, URL, View, Validator;

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

    /**
     * Function for display all State
     *
     * @param null
     *
     * @return view page.
     */

    public function index()
    {

        $allTest = Test::with([
            'test_participants',
            'test_results'
        ])->get();

        $allTraining = Training::with([
            'training_participants',
            'training_courses',
            'training_courses.test',
            'training_results'
        ])->get();
        $countCoursesWithTestId = $allTraining->pluck('training_courses')->flatten(1)->whereNotNull('test_id')->count();

        return  View::make("admin.$this->model.index", compact('allTest', 'allTraining'));
    }
    public function downloadReport($test_id)
    {
        $test = Test::find($test_id);
        $testResults = TestResult::where('test_id', $test_id)->get();
        if ($testResults->isEmpty()) {
            Session::flash('error', trans("This Test is not completed by any user."));
            return redirect()->back();
        } else {
            $export = new TestResultsExport($test, $testResults);

            Session::flash('success', trans("Training report downloaded successfully"));
            $fileName = 'test-results' . $test_id . '.xlsx';

            return Excel::download($export, $fileName);
        }
    }
    public function downloadReportTraining(Request $request, $trainingId)
    {
        $training = Training::findOrFail($trainingId);
        $trainingTitle = $training->title;
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

        $averageMinimumMark = ($totalTestCount > 0) ? ($totalMinimumMark / $totalTestCount) : 0;
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
}// end ReportsController
