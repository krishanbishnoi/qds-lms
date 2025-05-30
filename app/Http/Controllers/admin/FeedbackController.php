<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Models\Test;
use App\Exports\exportTests;
use App\Models\TrainingType;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Models\Training;
use App\Models\ManagerTrainings;
use App\Models\TrainerTrainings;
use App\Models\TestCategory;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Region;
use App\Models\Lob;
use App\Models\Circle;
use App\Models\TestParticipants;
use App\Models\TestAttendee;
use App\Models\TestResult;
use App\Models\UserAssignedTestQuestion;
use App\Models\QuestionAttribute;
use App\Imports\importTestsParticipants;
use App\Exports\exportTestsReport;
use App\Exports\TestReportExport;
use App\Exports\exportParticipants;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Request, Mail, Redirect, Response, Session, URL, View, Validator;
use LDAP\Result;
use App\Models\EmailAction;
use App\Models\EmailTemplate;
use Notification;
use Illuminate\Support\Facades\Crypt;
use App\Notifications\AssignTestNotification;

/**
 * TestController Controller
 *
 * Add your methods in the class below
 *
 */
class FeedbackController extends BaseController
{

    public $model        =    'Feedback';
    public $sectionName    =    'Feedback';
    public $sectionNameSingular    = 'Feedback';

    public function __construct()
    {
        parent::__construct();
        View::share('modelName', $this->model);
        View::share('sectionName', $this->sectionName);
        View::share('sectionNameSingular', $this->sectionNameSingular);
    }

    public function index()
    {

        $DB = Test::query()->where('type', 'feedback_test');

        // $DB                            =    Test::query();

        if (Auth::user()->user_role_id == MANAGER_ROLE_ID) {
            $created_by_self_ids = $DB->where('user_id', Auth::user()->id)->pluck('id')->toArray();

            $my_assign_test_ids  = ManagerTrainings::where("user_id", Auth::user()->id)->where('test_id', '!=', '')->pluck('test_id')->toArray();


            $users = $DB->whereIn('tests.id', $created_by_self_ids)
                ->orWhereIn('tests.id', $my_assign_test_ids);
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

        $DB->leftJoin('users', 'users.id', '=', 'tests.user_id')->join('test_categories', 'test_categories.id', '=', 'tests.category_id')->select('tests.*',  'users.first_name as created_by', 'test_categories.name as category_name');
        $sortBy                     =     (Request::get('sortBy')) ? Request::get('sortBy') : 'updated_at';
        $order                      =     (Request::get('order')) ? Request::get('order')   : 'DESC';
        $results                     =     $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
        $complete_string            =    Request::query();
        unset($complete_string["sortBy"]);
        unset($complete_string["order"]);
        $query_string                =    http_build_query($complete_string);
        $results->appends(Request::all())->render();
        // echo '<pre>'; print_r($results); die;

        session(['filteredResult' => $results]);



        $training_manager = User::where("is_deleted", 0)->where("user_role_id", MANAGER_ROLE_ID)->pluck('first_name', 'id')->toArray();

        $trainers = User::where("is_deleted", 0)->where("user_role_id", TRAINER_ROLE_ID)->pluck('first_name', 'id')->toArray();

        // $selected_training_trainers = TrainerTrainings::where('test_id', $modelId)->pluck('user_id');
        // echo '<pre>'; print_r($thisData); die;


        return  View::make("admin.$this->model.index", compact('results', 'searchVariable', 'sortBy', 'order', 'query_string', 'training_manager', 'trainers'));
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
        $TestCategory = TestCategory::pluck('name', 'id')->toArray();
        $region =     Region::pluck('region', 'id')->toArray();
        $lob = Lob::pluck('lob', 'id')->toArray();
        $circle = Circle::pluck('circle', 'id')->toArray();
        $TrainingType = TrainingType::pluck('type', 'id')->toArray();
        $training_manager = User::where("is_deleted", 0)->where("user_role_id", MANAGER_ROLE_ID)->pluck('first_name', 'id')->toArray();
        $trainers = User::where("is_deleted", 0)->where("user_role_id", TRAINER_ROLE_ID)->pluck('first_name', 'id')->toArray();

        return  View::make("admin.$this->model.add", compact('TestCategory', 'TrainingType', 'training_manager', 'circle', 'lob', 'region', 'trainers'));
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
        // return Request::all();

        Request::replace($this->arrayStripTags(Request::all()));
        $thisData                    =    Request::all();
        //// echo '<pre>'; print_r($thisData); die;

        $rules = [
            'category_id' => 'required',
            'title' => 'required',
            //	'lob' => 'required',
            // 'region' => 'required',
            // 'circle' => 'required',
            'type' => 'required',
            'minimum_marks' => 'required',
            'number_of_attempts' => 'required',
            'time_of_test' => 'required',
            // 'document' => 'required',
            'start_date_time' => 'required',
            'end_date_time' => 'required',
            'thumbnail' => 'required',
            'publish_result' => 'required',
        ];

        // if (Auth::user()->user_role_id == MANAGER_ROLE_ID) {
        // 	$rules['training_trainer'] = 'required';
        // } else {
        // 	$rules['training_manager'] = 'required';
        // }

        $validator = Validator::make($thisData, $rules);

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
            $obj->publish_result         = Request::get('publish_result');
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
                if (isset($thisData['training_manager']) && !empty($thisData['training_manager'])) {
                    //ContestStocks::where('contest_id',$contest_id)->delete();
                    foreach ($thisData['training_manager'] as $user_id) {
                        //	print_r($user_id); die;
                        $object                 = new ManagerTrainings;
                        $object->test_id    = $test_id;
                        $object->user_id    = $user_id;
                        $object->save();
                    }
                }
            }
            if ($test_id) {
                if (isset($thisData['training_trainer']) && !empty($thisData['training_trainer'])) {
                    //ContestStocks::where('contest_id',$contest_id)->delete();
                    foreach ($thisData['training_trainer'] as $user_id) {
                        //	print_r($user_id); die;
                        $object                 = new TrainerTrainings;
                        $object->test_id    = $test_id;
                        $object->user_id    = $user_id;
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

        // $DB->leftJoin('users', 'users.id', '=', 'tests.user_id')->join('test_categories', 'test_categories.id', '=', 'tests.category_id')->select('tests.*',  'users.first_name as created_by', 'test_categories.name as category_name');

        $manager_ids = ManagerTrainings::where("test_id", $modelId)->pluck('user_id')->toArray();
        $manager_details = User::whereIn("id", $manager_ids)->get();

        // echo '<pre>'; print_r($manager_details ); die;

        $trainer_ids = TrainerTrainings::where("test_id", $modelId)->pluck('user_id')->toArray();
        $trainer_details = User::whereIn("id", $trainer_ids)->get();

        $trainee_ids = TestParticipants::where("test_id", $modelId)->pluck('trainee_id')->toArray();

        $user_details = User::whereIn("id", $trainee_ids)->get();

        $test_attendee_details = TestAttendee::whereIn('link_id', $trainee_ids)->get();

        $trainee_details = $user_details->merge($test_attendee_details);

        $questions = Question::where("test_id", $modelId)->get();

        // $trainee_details    = $trainee_details;
        // $trainer_details    = $trainer_details;
        // $manager_details    = $manager_details;


        return  View::make("admin.$this->model.view", compact('model', 'trainee_details', 'trainer_details', 'manager_details', 'questions'));
        // echo '<pre>'; print_r($createdBy); die;
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
        $training_manager = User::where("is_deleted", 0)->where("user_role_id", MANAGER_ROLE_ID)->pluck('first_name', 'id')->toArray();
        $selected_training_manager = ManagerTrainings::where('test_id', $modelId)->pluck('user_id');
        $region =     Region::pluck('region', 'id')->toArray();
        $lob = Lob::pluck('lob', 'id')->toArray();
        $circle = Circle::pluck('circle', 'id')->toArray();

        $trainers = User::where("is_deleted", 0)->where("user_role_id", TRAINER_ROLE_ID)->pluck('first_name', 'id')->toArray();
        $selected_training_trainers = TrainerTrainings::where('test_id', $modelId)->pluck('user_id');

        return  View::make("admin.$this->model.edit", compact('model', 'TestCategory', 'TrainingType', 'training_manager', 'selected_training_manager', 'region', 'lob', 'circle', 'trainers', 'selected_training_trainers'));
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
        //	 echo '<pre>'; print_r($thisData); die;


        $rules = [
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
            'publish_result' => 'required',
        ];

        // if (Auth::user()->user_role_id == MANAGER_ROLE_ID) {
        // 	$rules['training_trainer'] = 'required';
        // } else {
        // 	$rules['training_manager'] = 'required';
        // }

        $validator = Validator::make($thisData, $rules);
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
            $obj->publish_result         = Request::get('publish_result');
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
                if (isset($thisData['training_manager']) && !empty($thisData['training_manager'])) {
                    ManagerTrainings::where('test_id', $test_id)->delete();

                    foreach ($thisData['training_manager'] as $user_id) {
                        //	print_r($user_id); die;
                        $object                 = new ManagerTrainings;
                        $object->test_id    = $test_id;
                        $object->user_id    = $user_id;
                        $object->save();
                    }
                }
            }
            if ($test_id) {
                if (isset($thisData['training_trainer']) && !empty($thisData['training_trainer'])) {
                    TrainerTrainings::where('test_id', $test_id)->delete();

                    foreach ($thisData['training_trainer'] as $user_id) {
                        //	print_r($user_id); die;
                        $object                 = new TrainerTrainings;
                        $object->test_id    = $test_id;
                        $object->user_id    = $user_id;
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
    public function importTestsParticipants($test_id = 0)
    {
        $existQuestions = Question::where('test_id', $test_id)->exists();

        if ($existQuestions) {
            // Fetch any existing emails of participants if needed, otherwise use an empty string
            $attendeeEmails = TestParticipants::where('test_id', $test_id)
                ->whereHas('attendee')
                ->with('attendee')
                ->get()
                ->pluck('attendee.email')
                ->toArray();

            // Fetch emails from Users
            $userEmails = TestParticipants::where('test_id', $test_id)
                ->whereHas('user')
                ->with('user')
                ->get()
                ->pluck('user.email')
                ->toArray();
            $existingEmails = array_merge($attendeeEmails, $userEmails);

            // Convert the array to a comma-separated string
            $existingEmails = implode(', ', $existingEmails);
            return View::make("admin.$this->model.uploadTestsParticipants", compact('test_id', 'existingEmails'));
        } else {
            Session::flash('success', 'Please add questions to this test before uploading participants.');
            return Redirect::back();
        }
    }
    public function importTestsUsersDirectly(Request $request, $test_id)
    {
        // Replace request data to remove unwanted tags
        $thisData = Request::all();
        $rules = [
            'trainees' => 'required|string',
        ];

        // Validate input data
        $validator = Validator::make($thisData, $rules);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        // Split the trainees string into an array of emails
        $emails = array_map('trim', explode(',', $thisData['trainees']));
        $errors = [];
        $TestNumberOfAttempts = Test::find($test_id);

        foreach ($emails as $email) {
            $trimEmail = trim($email);
            $userAlreadyExist = DB::table('users')->where('email', $trimEmail)->first();

            if (empty($userAlreadyExist)) {
                $testAttendeeAlreadyExist = DB::table('test_attendees')
                    ->where('email', $trimEmail)
                    ->where('test_id', $test_id)
                    ->first();

                if (empty($testAttendeeAlreadyExist)) {
                    // Create new TestAttendee
                    $testAttendee = TestAttendee::create([
                        'email' => $trimEmail,
                        'test_id' => $test_id,
                    ]);

                    // Create new TestParticipant
                    TestParticipants::create([
                        'test_id' => $test_id,
                        'trainee_id' => $testAttendee->link_id,
                        'number_of_attempts' => $TestNumberOfAttempts->number_of_attempts,
                        'type' => 2,
                    ]);

                    // Send mail to the trainee for credentials of the new created trainee
                    $this->sendTraineeMail($testAttendee, $trimEmail, $test_id);
                } else {
                    $errors[] = "User with Email {$trimEmail} already exists in this Test.";
                    return redirect()->back()->with('error', "User with Email {$trimEmail} already exists in this Test.");
                }
            } else {
                $participantAlreadyExist = DB::table('test_participants')
                    ->where('test_id', $test_id)
                    ->where('trainee_id', $userAlreadyExist->id)
                    ->first();

                if (!empty($participantAlreadyExist)) {
                    $errors[] = "User with Email {$trimEmail} already exists in this Test.";
                    return redirect()->back()->with('error', "User with Email {$trimEmail} already exists in this Test.");
                } else {
                    // Create new TestParticipant for existing user
                    TestParticipants::create([
                        'test_id' => $test_id,
                        'trainee_id' => $userAlreadyExist->id,
                        'number_of_attempts' => $TestNumberOfAttempts->number_of_attempts,
                        'type' => 1,
                    ]);

                    // Send notification and email to the user
                    $this->sendNotificationAndMail($userAlreadyExist, $test_id);
                }
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->withErrors($errors);
        }

        return redirect()->route('Test.view', $test_id)->with('success', 'Trainees imported successfully!');
    }

    /**
     * Send mail to the trainee for new test attendee.
     */
    protected function sendTraineeMail($testAttendee, $email, $test_id)
    {
        $settingsEmail = Config::get('Site.email');
        $full_name = $testAttendee->email;
        $authEmail = $email;
        $linkId = $testAttendee->link_id;
        $encryptedLinkId = Crypt::encrypt($linkId);
        $route_url = URL::to('test-details/' . $test_id . '/' . $encryptedLinkId);
        $click_link = $route_url;

        $emailActions = EmailAction::where('action', 'product_status')->first();
        $emailTemplate = EmailTemplate::where('action', 'product_status')->first();

        $constants = explode(',', $emailActions->options);
        foreach ($constants as $key => $val) {
            $constants[$key] = '{' . $val . '}';
        }

        $subject = $emailTemplate->subject;
        $rep_Array = [$full_name, $authEmail, $authEmail, $click_link];
        $messageBody = str_replace($constants, $rep_Array, $emailTemplate->body);

        $this->sendMail($authEmail, $full_name, $subject, $messageBody, $settingsEmail);
    }

    /**
     * Send notification and email to the existing user.
     */
    protected function sendNotificationAndMail($user, $test_id)
    {
        // Send notification to the user
        $TestDetail = Test::find($test_id);
        $actionUrl = URL::route('userTest.index');

        $details = [
            'greeting' => 'New Test Available',
            'message' => 'New Test Available',
            'body' => 'You have been assigned a ' . $TestDetail->title . ' test.',
            'actionText' => 'View Test',
            'actionURL' => $actionUrl,
            'training_id' => $test_id,
        ];

        Notification::send($user, new AssignTestNotification($details));

        // Send email
        $settingsEmail = Config::get('Site.email');
        $full_name = $user->email;
        $authEmail = $user->email;
        $route_url = URL::to('/login');
        $click_link = $route_url;

        $emailActions = EmailAction::where('action', 'product_status')->first();
        $emailTemplate = EmailTemplate::where('action', 'product_status')->first();

        $constants = explode(',', $emailActions->options);
        foreach ($constants as $key => $val) {
            $constants[$key] = '{' . $val . '}';
        }

        $subject = $emailTemplate->subject;
        $rep_Array = [$full_name, $authEmail, $authEmail, $click_link];
        $messageBody = str_replace($constants, $rep_Array, $emailTemplate->body);

        $this->sendMail($authEmail, $full_name, $subject, $messageBody, $settingsEmail);
    }

    public function importTests($test_id = 0)
    {
        $import = new importTestsParticipants($test_id);
        Excel::import($import, request()->file('file'));
        $errors = $import->getErrors();

        if (count($errors) > 0) {
            return view('errors.importTraineeError')->with('errors', $errors);
        }
        return redirect()->back()->with('success', 'Tests Participants Added Successfully!');
    }



    public function AssignManager()
    {

        $thisData                    =    Request::all();
        $test_id   = $thisData['test_id'];
        if ($test_id) {
            if (isset($thisData['training_manager']) && !empty($thisData['training_manager'])) {
                ManagerTrainings::where('test_id', $test_id)->delete();

                foreach ($thisData['training_manager'] as $user_id) {
                    //	print_r($user_id); die;
                    $object                 = new ManagerTrainings;
                    $object->test_id    = $test_id;
                    $object->user_id    = $user_id;
                    $object->save();
                }
            }
        }

        Session::flash('flash_notice', trans(" Manager has been Assign successfully"));
        return Redirect::back();
    } // end delete()


    public function AssignTrainer()
    {

        $thisData                    =    Request::all();
        //	echo '<pre>'; print_r($thisData); die;
        $test_id   = $thisData['test_id'];
        if ($test_id) {
            if (isset($thisData['training_trainer']) && !empty($thisData['training_trainer'])) {
                TrainerTrainings::where('test_id', $test_id)->delete();

                foreach ($thisData['training_trainer'] as $user_id) {
                    //	print_r($user_id); die;
                    $object                 = new TrainerTrainings;
                    $object->test_id    = $test_id;
                    $object->user_id    = $user_id;
                    $object->save();
                }
            }
        }

        Session::flash('flash_notice', trans(" Trainer has been Assign successfully"));
        return Redirect::back();
    }

    public function testReport($test_id)
    {
        $checkTestResults = TestResult::where('test_id', $test_id)->get();

        if ($checkTestResults->isEmpty()) {
            // dd('here');
            Session::flash('error', trans("This Test is not completed by any user."));
            return redirect()->back();
        } else {

            $test = Test::findOrFail($test_id);
            $testParticipants = $test->test_participants;

            $userDetails = User::whereIn('id', $testParticipants->pluck('trainee_id'))->get();

            $testAttendeeDetails = TestAttendee::whereIn('link_id', $testParticipants->pluck('trainee_id'))->get();

            $traineeDetails = $userDetails->merge($testAttendeeDetails);
            $participants = $traineeDetails;


            $questions = Question::where('test_id', $test_id)
                ->get();

            $testResults = TestResult::where('test_id', $test_id)->with('user_details')
                ->get();

            return Excel::download(new TestReportExport($participants, $questions, $testResults), 'test_report.xlsx');
        }
    }
}// end TestController
