<?php

namespace App\Http\Controllers\trainer;

use App\Http\Controllers\BaseController;
use App\Models\Test;
use App\Exports\exportTests;
use App\Models\TrainingType;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Models\Training;
use App\Models\TestParticipants;
use App\Models\TrainerTrainings;
use App\Models\TestCategory;
use App\Models\Question;
use App\Models\TestAttendee;
use App\Models\Region;
use App\Models\Lob;
use App\Models\Circle;
use App\Models\TestResult;
use App\Models\UserAssignedTestQuestion;
use App\Models\StateDescription;
use App\Exports\exportParticipants;
use App\Imports\importTestsParticipants;
use App\Models\QuestionAttribute;
use App\Exports\exportTestsReport;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Request, Mail, Redirect, Response, Session, URL, View, Validator;

/**
 * TestController Controller
 *
 * Add your methods in the class below
 *
 */
class TestController extends BaseController
{

    public $model        =    'TrainerTest';
    public $sectionName    =    'Test';
    public $sectionNameSingular    = 'Test';

    public function __construct()
    {
        parent::__construct();
        View::share('modelName', $this->model);
        View::share('sectionName', $this->sectionName);
        View::share('sectionNameSingular', $this->sectionNameSingular);
    }

    public function index()
    {
        $DB                            =    Test::query();

        if (Auth::user()->user_role_id == TRAINER_ROLE_ID) {
            //$DB->where('user_id',Auth::user()->id);
            $created_by_self_ids = $DB->where('user_id', Auth::user()->id)->pluck('id')->toArray();

            $my_assign_test_ids  = TrainerTrainings::where("user_id", Auth::user()->id)->where('test_id', '!=', '')->pluck('test_id')->toArray();
            $users = $DB->whereIn('tests.id', $created_by_self_ids)->orWhereIn('tests.id', $my_assign_test_ids);
        } else {
            $DB    = $DB;
        }
        $searchVariable                =    array();
        $inputGet                    =    Request::all();
        if ((Request::all())) {
            $searchData                =    Request::all();
            unset($searchData['display']);
            unset($searchData['_token']);
            if (isset($searchData['order'])) {
                unset($searchData['order']);
            }
            if (isset($searchData['sortBy'])) {
                unset($searchData['sortBy']);
            }
            if (isset($searchData['page'])) {
                unset($searchData['page']);
            }
            foreach ($searchData as $fieldName => $fieldValue) {
                if ($fieldValue != "") {
                    if ($fieldName == "is_active") {
                        $DB->where("tests.is_active", $fieldValue);
                    }
                    if ($fieldName == "title") {
                        $DB->where("tests.title", 'like', '%' . $fieldValue . '%');
                    }
                }
                $searchVariable    =    array_merge($searchVariable, array($fieldName => $fieldValue));
            }
        }
        $DB->leftJoin('training_types', 'training_types.id', '=', 'tests.type')->leftJoin('users', 'users.id', '=', 'tests.user_id')->select('tests.*', 'training_types.type as type', 'users.first_name as created_by');
        $sortBy                     =     (Request::get('sortBy')) ? Request::get('sortBy') : 'updated_at';
        $order                      =     (Request::get('order')) ? Request::get('order')   : 'DESC';
        $results                     =     $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
        $complete_string            =    Request::query();
        unset($complete_string["sortBy"]);
        unset($complete_string["order"]);
        $query_string                =    http_build_query($complete_string);
        $results->appends(Request::all())->render();
        //	 echo '<pre>'; print_r($results); die;
        session(['filteredResult' => $results]);
        return  View::make("trainer.$this->model.index", compact('results', 'searchVariable', 'sortBy', 'order', 'query_string'));
    }

    /**
     * Function for add new State
     *
     * @param null
     *
     * @return view page.
     */
    public function add()
    {

        $TestCategory = TestCategory::where('parent_id', Auth::user()->id)->pluck('name', 'id')->toArray();
        $region =     Region::pluck('region', 'id')->toArray();
        $lob = Lob::pluck('lob', 'id')->toArray();
        $circle = Circle::pluck('circle', 'id')->toArray();
        $TrainingType = TrainingType::pluck('type', 'id')->toArray();
        $trainees = User::where("is_deleted", 0)->where("user_role_id", TRAINEE_ROLE_ID)->pluck('first_name', 'id')->toArray();

        return  View::make("trainer.$this->model.add", compact('TrainingType', 'trainees', 'circle', 'lob', 'region', 'TestCategory'));
    } // end add()


    /**
     * Function for save new Area
     *
     * @param null
     *
     * @return redirect page.
     */
    function save()
    {
        Request::replace($this->arrayStripTags(Request::all()));
        $thisData                    =    Request::all();
        //  echo '<pre>'; print_r($thisData); die;

        $validator = Validator::make(
            $thisData,
            array(
                'category_id' => 'required',
                'title'             => 'required',
                // 'lob'                => 'required',
                'region'                => 'required',
                'circle'                => 'required',
                'type'             => 'required',
                'minimum_marks'             => 'required',
                'number_of_attempts'             => 'required',
                'start_date_time'     => 'required',
                'end_date_time'             => 'required',
                'thumbnail' => 'mimes:jpeg,jpg,png|required',
                'time_of_test' => 'required',


            )
        );

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)->withInput();
        } else {
            $obj = new Test;
            $obj->category_id             = Request::get('category_id');
            $obj->title                 = Request::get('title');
            $obj->type                   = Request::get('type');
            $obj->minimum_marks           = Request::get('minimum_marks');
            $obj->user_id               = Auth::user()->id;
            $obj->number_of_attempts       = Request::get('number_of_attempts');
            $obj->time_of_test       = Request::get('time_of_test');
            $obj->number_of_questions = Request::get('number_of_questions') ?? '0';
            // $obj->skip   			= Request::get('skip');
            // $obj->test_id 			= Request::get('test_id');
            $obj->region                 =  Request::get('region');
            $obj->circle                 =  Request::get('circle');
            $obj->lob                     =  Request::get('lob');
            $obj->start_date_time         = Request::get('start_date_time');
            $obj->end_date_time         = Request::get('end_date_time');
            $obj->description               = Request::get('description');
            if (Request::hasFile('thumbnail')) {
                $extension     =     Request::file('thumbnail')->getClientOriginalExtension();
                $fileName    =    time() . '-thumbnail.' . $extension;

                $folderName         =     strtoupper(date('M') . date('Y')) . "/";
                $folderPath            =    TRAINING_DOCUMENT_ROOT_PATH . $folderName;
                if (!File::exists($folderPath)) {
                    File::makeDirectory($folderPath, $mode = 0777, true);
                }
                if (Request::file('thumbnail')->move($folderPath, $fileName)) {
                    $obj->thumbnail    =    $folderName . $fileName;
                }
            }
            $obj->save();
            $test_id                    =    $obj->id;

            if ($test_id) {
                if (isset($thisData['trainees']) && !empty($thisData['trainees'])) {
                    foreach ($thisData['trainees'] as $trainee_id) {
                        //	print_r($trainee_id); die;
                        $object                 = new TestParticipants;
                        $object->test_id    = $test_id;
                        $object->trainee_id    = $trainee_id;
                        $object->save();
                    }
                }
            }

            if (!$obj->save()) {

                Session::flash('error', trans("Something went wrong."));
                return Redirect::route($this->model . ".index");
            } else {
                Session::flash('success', trans($this->sectionNameSingular . " has been added successfully"));
                return Redirect::route($this->model . ".index");
            }
        }
    } //end save()

    /**
     * Function for update status
     *
     * @param $modelId as id of area
     * @param $status as status of area
     *
     * @return redirect page.
     */
    public function changeStatus($modelId = 0, $status = 0)
    {
        if ($status == 0) {
            $statusMessage    =    trans($this->sectionNameSingular . " has been deactivated successfully");
        } else {
            $statusMessage    =    trans($this->sectionNameSingular . " has been activated successfully");
        }
        Test::where('id', $modelId)->update(array('is_active' => $status));
        Session::flash('flash_notice', $statusMessage);
        return Redirect::back();
    } // end changeStatus()

    public function view($modelId = 0)
    {
        $model                =    Test::find($modelId);
        if (empty($model)) {
            return Redirect::route($this->model . ".index");
        }


        $model = Test::Join('users', 'users.id', '=', 'tests.user_id')->select('tests.*', 'users.first_name as created_by')->where('tests.id', $modelId)->first();

        $trainee_ids = TestParticipants::where("test_id", $modelId)->pluck('trainee_id')->toArray();

        $user_details = User::whereIn("id", $trainee_ids)->get();

        $test_attendee_details = TestAttendee::whereIn('link_id', $trainee_ids)->get();

        $trainee_details = $user_details->merge($test_attendee_details);

        $questions = Question::where("test_id", $modelId)->get();

        return  View::make("trainer.$this->model.view", compact('model', 'trainee_details', 'questions'));
    } // end edit()

    /**
     * Function for display page for edit area
     *
     * @param $modelId id  of area
     *
     * @return view page.
     */
    public function edit($modelId = 0)
    {
        $model                =    Test::find($modelId);

        if (empty($model)) {
            return Redirect::route($this->model . ".index");
        }
        $TestCategory = TestCategory::pluck('name', 'id')->toArray();
        $TrainingType = TrainingType::pluck('type', 'id')->toArray();
        $trainees = User::where("is_deleted", 0)->where("user_role_id", TRAINEE_ROLE_ID)->pluck('first_name', 'id')->toArray();

        $selected_trainees = TestParticipants::where('test_id', $modelId)->pluck('trainee_id');
        $region =     Region::pluck('region', 'id')->toArray();
        $lob = Lob::pluck('lob', 'id')->toArray();
        $circle = Circle::pluck('circle', 'id')->toArray();
        return  View::make("trainer.$this->model.edit", compact('model', 'TrainingType', 'trainees', 'selected_trainees', 'region', 'lob', 'circle', 'TestCategory'));
    } // end edit()


    /**
     * Function for update area
     *
     * @param $modelId as id of area
     *
     * @return redirect page.
     */
    function update($modelId)
    {
        $model                    =    Test::findorFail($modelId);
        if (empty($model)) {
            return Redirect::back();
        }
        Request::replace($this->arrayStripTags(Request::all()));
        $thisData                    =    Request::all();
        // echo '<pre>'; print_r($thisData); die;

        $validator = Validator::make(
            $thisData,
            array(
                'category_id' => 'required',
                'title' => 'required',
                //'lob' => 'required',
                'region' => 'required',
                'circle' => 'required',
                'type' => 'required',
                'minimum_marks' => 'required',
                'number_of_attempts' => 'required',
                'time_of_test' => 'required',
                // 'document' => 'required',
                'start_date_time' => 'required',
                'end_date_time' => 'required',
                //'thumbnail' => 'required',
            )
        );

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)->withInput();
        } else {
            $obj = $model;
            $obj->category_id           = Request::get('category_id');
            $obj->type                   = Request::get('type');
            //	$obj->user_id   				= Auth::user()->id;
            $obj->title                   = Request::get('title');
            $obj->region                 =  Request::get('region');
            $obj->circle                 =  Request::get('circle');
            $obj->lob                     =  Request::get('lob');
            $obj->minimum_marks           = Request::get('minimum_marks');
            $obj->number_of_attempts       = Request::get('number_of_attempts');
            $obj->time_of_test           = Request::get('time_of_test');
            $obj->number_of_questions   = Request::get('number_of_questions') ?? '0';
            $obj->start_date_time         = Request::get('start_date_time');
            $obj->end_date_time         = Request::get('end_date_time');
            $obj->description           = Request::get('description');

            if (Request::hasFile('thumbnail')) {
                $extension     =     Request::file('thumbnail')->getClientOriginalExtension();
                $fileName    =    time() . '-thumbnail.' . $extension;

                $folderName         =     strtoupper(date('M') . date('Y')) . "/";
                $folderPath            =    TRAINING_DOCUMENT_ROOT_PATH . $folderName;
                if (!File::exists($folderPath)) {
                    File::makeDirectory($folderPath, $mode = 0777, true);
                }
                if (Request::file('thumbnail')->move($folderPath, $fileName)) {
                    $obj->thumbnail    =    $folderName . $fileName;
                }
            }
            $obj->save();
            $test_id                    =    $obj->id;

            if ($test_id) {
                if (isset($thisData['trainees']) && !empty($thisData['trainees'])) {
                    TestParticipants::where('test_id', $test_id)->delete();

                    foreach ($thisData['trainees'] as $trainee_id) {
                        //	print_r($trainee_id); die;
                        $object                 = new TestParticipants;
                        $object->test_id    = $test_id;
                        $object->trainee_id    = $trainee_id;
                        $object->save();
                    }
                }
            }

            if (!$obj->save()) {

                Session::flash('error', trans("Something went wrong."));
                return Redirect::route($this->model . ".index");
            } else {
                Session::flash('success', trans($this->sectionNameSingular . " has been Updated successfully"));
                return Redirect::route($this->model . ".index");
            }
        }
    } // end update()


    /**
     * Function for mark a couse as deleted
     *
     * @param $userId as id of couse
     *
     * @return redirect page.
     */
    public function delete($id = 0)
    {
        $model    =    Test::find($id);
        if (empty($model)) {
            return Redirect::back();
        }
        if ($id) {
            Test::where('id', $id)->delete();
            Session::flash('flash_notice', trans($this->sectionNameSingular . " has been removed successfully"));
        }
        return Redirect::back();
    } // end delete()


    public function exportTests(Request $request)
    {
        $filteredResult = Session::get('filteredResult');

        $filteredResult = $filteredResult->map(function ($item) {
            $selectedFields = $item->only(['id', 'title', 'created_by', 'type', 'start_date_time', 'end_date_time', 'status', 'description']);
            $selectedFields['description'] = strip_tags($selectedFields['description']);

            if ($selectedFields['status'] == '0') {
                $selectedFields['status'] = 'Upcoming';
            } else if ($selectedFields['status'] == '1') {
                $selectedFields['status'] = 'Ongoing';
            } else {
                $selectedFields['status'] = 'Completed';
            }

            return $selectedFields;
        });

        $export = new exportTests($filteredResult);
        return Excel::download($export, 'Test.xlsx');
    }

    public function importTrainerTestsParticipants($test_id = 0)
    {

        return  View::make("trainer.$this->model.uploadTrainerTestsParticipants", compact('test_id'));
    } // end add()

    public function importTrainerTests($test_id = 0)
    {
        $import = new importTestsParticipants($test_id);
        Excel::import($import, request()->file('file'));
        $errors = $import->getErrors();

        if (count($errors) > 0) {
            return view('errors.importTraineeError')->with('errors', $errors);
        }
        return redirect()->back()->with('success', 'Trainer Tests Participants Added Successfully!');
    }


    public function testReport($test_id)
    {
        $checkTestResults = TestResult::where('test_id', $test_id)->get();

        if ($checkTestResults->isEmpty()) {
            // dd('here');
            Session::flash('error', trans("This Test is not completed by any user."));
            return redirect()->back();
        } else {
            // Fetching data
            $test = Test::findOrFail($test_id);
            $testParticipants = $test->test_participants;


            $userDetails = User::whereIn('id', $testParticipants->pluck('trainee_id'))->get();

            $testAttendeeDetails = TestAttendee::whereIn('link_id', $testParticipants->pluck('trainee_id'))->get();
            $traineeDetails = $userDetails->merge($testAttendeeDetails);
            $participants = $traineeDetails->toArray();
            // dd($participants);
            // Creating Excel file
            $excelData = [];
            $excelData[] = ['Sn.', 'User Email', 'Questions', 'Total Marks', 'Obtain Marks', 'Percentage', 'Result'];

            foreach ($participants as $key => $participant) {
                $assignedQuestions = UserAssignedTestQuestion::where('test_id', $test_id)
                    ->where('trainee_id', $participant['id'])
                    ->pluck('questions_id')
                    ->toArray();

                $questions = DB::table('questions')
                    ->whereIn('questions.id', $assignedQuestions)
                    ->select('questions.id', 'questions.question')
                    ->get();

                $rowData = [];
                $rowData[] = $key + 1; // Sn.
                $rowData[] = $participant['email']; // User Email


                foreach ($questions as $question) {
                    $userAnswers = $this->getUserAnswer($test_id, $participant['id'], $question->id);
                    $optionsWithIndicators = [];

                    foreach ($userAnswers as $userAnswer) {
                        $isCorrect = isset($userAnswer['is_correct']) ? $userAnswer['is_correct'] : false;
                        $optionsWithIndicators[] = ' [' . ($isCorrect ? '1' : '0') . '/1]';
                    }

                    $rowData[] = $question->question . ' [' . ($isCorrect ? '1' : '0') . '/1]';
                }




                // Adding test results data
                $testResultData = TestResult::where('test_id', $test_id)
                    ->where('user_id', $participant['id'])
                    ->first();

                // Check if the user has test results
                if ($testResultData) {
                    $rowData[] = $testResultData->total_marks;
                    $rowData[] = $testResultData->obtain_marks;
                    $rowData[] = $testResultData->percentage;
                    $rowData[] = $testResultData->result;
                } else {
                    // If the user has no test results, fill with default values
                    $rowData[] = 0;
                    $rowData[] = 0;
                    $rowData[] = 0;
                    $rowData[] = 'Not Attempted';
                }

                $excelData[] = $rowData;
            }

            $fileName = 'test_results_' . $test_id . '.xlsx';

            return Excel::download(new ExportTestsReport($excelData), $fileName);
        }
    }

    protected function getUserAnswer($test_id, $userId, $questionId)
    {


        $userAnswer = DB::table('answers')
            ->where('user_id', $userId)
            ->where('question_id', $questionId)
            ->first();

        if ($userAnswer) {

            $answerIds = explode(',', $userAnswer->answer_id);
            $validAnswerIds = explode(',', $userAnswer->valid_answer);

            $options = [];

            foreach ($answerIds as $answerId) {
                $option = QuestionAttribute::where('question_id', $questionId)
                    ->where('id', $answerId)
                    ->value('option');

                if ($option !== null) {
                    $options[] = [
                        'option' => $option,
                        'is_correct' => in_array($answerId, $validAnswerIds),
                    ];
                }
            }

            return $options;
        }

        return [];
    }
}// end TestController
