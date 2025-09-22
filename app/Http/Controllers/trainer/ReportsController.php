<?php

namespace App\Http\Controllers\trainer;

use App\Exports\TestReportExport;
use App\Exports\TrainingDocumentResultsExport;
use App\Exports\TrainingResultsExport;
use App\Http\Controllers\BaseController;
use App\Model\Question;
use App\Model\Test;
use App\Model\TestResult;
use App\Model\TrainerAssignBatch;
use App\Model\Training;
use App\Model\TrainingParticipants;
use App\Model\TrainingTestResult;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Session;
use View;

/**
 * ReportsController Controller
 *
 * Add your methods in the class below
 */
class ReportsController extends BaseController
{
    public $model = 'Reports';

    public $sectionName = 'Report';

    public $sectionNameSingular = 'Report';

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
     * @return view page.
     */
    public function index(Request $request)
    {
        $userTrainerOlms = Auth::user()->olms_id;

        // Fetch batch IDs assigned to the authenticated trainer
        $assignedBatchIds = TrainerAssignBatch::where('trainer_id', Auth::id())->pluck('batch_id')->toArray();
        $testType         = $request->input('test_type', 'regular_test');
        if ($testType == 'regular_test') {
            if (Auth::user()->user_role_id == 2) {
                $allTest = Test::where('type', 'regular_test')
                    ->whereHas('test_participants.user', function ($userQuery) use ($userTrainerOlms, $assignedBatchIds) {
                        $userQuery->where('trainer_olms', $userTrainerOlms)
                            ->whereIn('batch_id', $assignedBatchIds);
                    })
                    ->with([
                        'test_participants' => function ($query) use ($userTrainerOlms, $assignedBatchIds) {
                            $query->whereHas('user', function ($userQuery) use ($userTrainerOlms, $assignedBatchIds) {
                                $userQuery->where('trainer_olms', $userTrainerOlms)
                                    ->whereIn('batch_id', $assignedBatchIds);
                            });
                        },
                        'test_results'      => function ($query) use ($userTrainerOlms, $assignedBatchIds) {
                            $query->whereHas('user', function ($userQuery) use ($userTrainerOlms, $assignedBatchIds) {
                                $userQuery->where('trainer_olms', $userTrainerOlms)
                                    ->whereIn('batch_id', $assignedBatchIds);
                            });
                        },
                    ])
                    ->orderBy('id', 'desc')
                    ->get();
            }
        } elseif ($testType == 'training_test') {
            $query = Test::where('type', 'training_test')
                ->whereHas('training_test_participants.user', function ($userQuery) use ($userTrainerOlms, $assignedBatchIds) {
                    $userQuery->where('trainer_olms', $userTrainerOlms)
                        ->whereIn('batch_id', $assignedBatchIds);
                })
                ->with([
                    'training_test_participants' => function ($query) use ($userTrainerOlms, $assignedBatchIds) {
                        $query->whereHas('user', function ($userQuery) use ($userTrainerOlms, $assignedBatchIds) {
                            $userQuery->where('trainer_olms', $userTrainerOlms)
                                ->whereIn('batch_id', $assignedBatchIds);
                        });
                    },
                    'training_test_results'      => function ($query) use ($userTrainerOlms, $assignedBatchIds) {
                        $query->whereHas('user', function ($userQuery) use ($userTrainerOlms, $assignedBatchIds) {
                            $userQuery->where('trainer_olms', $userTrainerOlms)
                                ->whereIn('batch_id', $assignedBatchIds);
                        });
                    },
                ])
                ->orderBy('id', 'desc');

            if ($request->filled('search')) {
                $query->where('title', 'LIKE', '%' . $request->input('search') . '%');
            }

            if (Auth::user()->user_role_id == 4) {
                $usercenter = Auth::user()->center;
                if ($usercenter) {
                    $query->whereHas('training_test_participants.user', function ($query) use ($usercenter) {
                        $query->where('center', $usercenter);
                    });
                }
            }

            $allTest = $query->get();
        } else {
            $allTest = [];
        }
        // Fetch all training data with filters for participants and results based on batch_id

        $allTraining = Training::with([
            'training_participants' => function ($query) use ($userTrainerOlms, $assignedBatchIds) {
                $query->whereHas('user', function ($userQuery) use ($userTrainerOlms, $assignedBatchIds) {
                    $userQuery->where('trainer_olms', $userTrainerOlms)
                        ->whereIn('batch_id', $assignedBatchIds);
                });
            },
            'training_courses',
            'training_courses.test',
            'training_results' => function ($query) {
                $query->where('result', 'Passed');
            },
        ])
            ->whereHas('training_participants.user', function ($query) use ($userTrainerOlms, $assignedBatchIds) {
                $query->where('trainer_olms', $userTrainerOlms)
                    ->whereIn('batch_id', $assignedBatchIds);
            })
            ->orderBy('id', 'desc')
            ->get();
        // Get all trainings

        // Count courses with a test_id
        $countCoursesWithTestId = $allTraining->pluck('training_courses')->flatten(1)->whereNotNull('test_id')->count();

        // Render the view with data
        return view("trainer.$this->model.index", compact('allTest', 'allTraining', 'countCoursesWithTestId', 'testType'));
    }

    public function downloadTestReport($test_id)
    {
        // Check if any test results exist for the given test
        $checkTestResults = TestResult::where('test_id', $test_id)->get();

        if ($checkTestResults->isEmpty()) {
            Session::flash('error', trans('This Test is not completed by any user.'));
            return redirect()->back();
        } else {
            // Fetch test details
            $test = Test::findOrFail($test_id);

            // Fetch participants for the test
            $testParticipants = $test->test_participants;

            // Initialize empty $userDetails
            $userDetails = collect();

            // Check if the user is a trainer (role_id == 2)
            if (Auth::user()->user_role_id == 2) {
                $usercenter = Auth::user()->center;

                // Get batch IDs assigned to the authenticated trainer
                $assignedBatchIds = TrainerAssignBatch::where('trainer_id', Auth::id())->pluck('batch_id')->toArray();

                // Filter participants based on batch_id and center
                $userDetails = User::whereIn('batch_id', $assignedBatchIds) // Filter by assigned batch IDs
                    ->where(function ($query) use ($testParticipants, $usercenter) {
                        $query->whereIn('id', $testParticipants->pluck('trainee_id')) // Include test participants
                            ->orWhere('center', $usercenter);                             // Include users from assigned batches
                    })
                    ->get();
            } else {
                // If the user is not a trainer, fetch all participants without filtering by batch
                $userDetails = User::whereIn('id', $testParticipants->pluck('trainee_id'))->get();
            }

            // Participants filtered by center and batch
            $participants = $userDetails;

            // Fetch the questions related to the test
            $questions = Question::where('test_id', $test_id)->get();

            // Fetch the test results including user details
            $testResults = TestResult::where('test_id', $test_id)
                ->with('user_details')
                ->get();

            // Generate and download the Excel report
            return Excel::download(new TestReportExport($participants, $questions, $testResults), "{$test->title}_test_report.xlsx");
        }
    }

    // public function downloadReport($test_id)
    // {
    //     $test = Test::find($test_id);
    //     $testResults = TestResult::where('test_id', $test_id)->get();
    //     if ($testResults->isEmpty()) {
    //         // dd('here');
    //         Session::flash('error', trans('This Test is not completed by any user.'));

    //         return redirect()->back();
    //     } else {
    //         return $testResults;
    //         $export = new TestResultsExport($test, $testResults);

    //         Session::flash('success', trans('Training report downloaded successfully'));

    //         return Excel::download($export, 'test-result-report.xlsx');
    //     }
    // }
    public function downloadReportTraining(Request $request, $trainingId)
    {
        $training      = Training::findOrFail($trainingId);
        $trainingTitle = $training->title;
        $courses       = $training->training_courses;

        $usercenter     = Auth::user()->center;
        $authUserRoleId = Auth::user()->user_role_id;

        $allUserDetails = [];
        $allUserAssigned = [];
        $noTestsFound    = true;

        // If trainer, get assigned batch IDs
        $assignedBatchIds = [];
        if ($authUserRoleId == 2) {
            $assignedBatchIds = TrainerAssignBatch::where('trainer_id', Auth::id())->pluck('batch_id')->toArray();
        }

        foreach ($courses as $course) {
            $test = Test::find($course->test_id);

            if (!$test) {
                continue;
            }

            $noTestsFound = false;

            // Get all user test results
            $userDetailsQuery = TrainingTestResult::where('training_id', $trainingId)
                ->where('course_id', $course->id)
                ->orWhere('test_id', $test->id);

            if ($authUserRoleId == 2) {
                // Trainer: filter by assigned center and batch
                $userDetailsQuery->whereHas('user', function ($query) use ($usercenter, $assignedBatchIds) {
                    $query->where('center', $usercenter)
                        ->orWhereIn('batch_id', $assignedBatchIds);
                });
            }

            $userDetails = $userDetailsQuery->get();

            if ($userDetails->isEmpty()) {
                Session::flash('error', trans('This training is not completed by any user.'));
                return redirect()->back();
            }

            $allUserDetails = array_merge($allUserDetails, $userDetails->toArray());
        }

        // If no tests found in any course — fallback to participant-based export
        if ($noTestsFound) {
            $userAssignedQuery = TrainingParticipants::where('training_id', $trainingId);

            if ($authUserRoleId == 2) {
                $userAssignedQuery->whereHas('user', function ($query) use ($usercenter, $assignedBatchIds) {
                    $query->where('center', $usercenter)
                        ->orWhereIn('batch_id', $assignedBatchIds);
                });
            }

            $userAssigned = $userAssignedQuery->get();

            if ($userAssigned->isEmpty()) {
                Session::flash('error', trans('This training is not completed by any user.'));
                return redirect()->back();
            }

            $allUserAssigned = array_merge($allUserAssigned, $userAssigned->toArray());

            $export = new TrainingDocumentResultsExport($trainingTitle, $allUserAssigned, $courses);
            $fileName = 'training-documents-report-' . $trainingTitle . '.xlsx';

            Session::flash('success', trans('Training report downloaded successfully'));
            return Excel::download($export, $fileName);
        }

        // If tests were found, export test result report
        $export   = new TrainingResultsExport($trainingTitle, $allUserDetails);
        $fileName = 'training-results-report-' . $trainingTitle . '.xlsx';

        Session::flash('success', trans('Training report downloaded successfully'));
        return Excel::download($export, $fileName);
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
    //     $usercenter = Auth::user()->center;
    //     $authUserRoleId = Auth::user()->user_role_id;

    //     $userDetails = collect(); // Initialize the $userDetails collection

    //     foreach ($courses as $course) {
    //         $test = Test::find($course->test_id);
    //         if ($test == null) {
    //             Session::flash('error', trans('This training does not have any tests, so the report is not available.'));
    //             return redirect()->back();
    //         }

    //         if ($test) {
    //             $totalMinimumMark += $test->minimum_marks;
    //             $totalTestCount++;

    //             // Calculate average marks for the test
    //             $averageMarksQuery = TrainingTestResult::where('training_id', $trainingId)
    //                 ->where('course_id', $course->id);

    //             // Apply filters based on user role
    //             if ($authUserRoleId == 2) {
    //                 $averageMarksQuery->whereHas('user', function ($query) use ($usercenter) {
    //                     $query->where('center', $usercenter);
    //                 });
    //             }

    //             $averageMarks = $averageMarksQuery->avg('percentage');

    //             // Filter the users based on trainer assignment and center if the user is a trainer (role_id == 2)
    //             $userDetailsQuery = TrainingTestResult::where('training_id', $trainingId)
    //                 ->where('course_id', $course->id)
    //                 ->orWhere('test_id', $test->id);

    //             if ($authUserRoleId == 2) {
    //                 // Get the batch IDs assigned to the trainer
    //                 $assignedBatchIds = TrainerAssignBatch::where('trainer_id', Auth::id())->pluck('batch_id')->toArray();

    //                 $userDetailsQuery->whereHas('user', function ($query) use ($usercenter, $assignedBatchIds) {
    //                     $query->where('center', $usercenter)  // Filter by center
    //                         ->orWhereIn('batch_id', $assignedBatchIds);  // Also include users from the assigned batches
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
    //         $fileName = 'training-results-report-' . $training->name . '.xlsx';
    //         Session::flash('success', trans('Training report downloaded successfully'));

    //         return Excel::download($export, $fileName);
    //     }
    // }

} // end ReportsController
