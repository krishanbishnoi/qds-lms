<?php

namespace App\Http\Controllers\admin;

use App\Exports\exportTests;
use App\Exports\TestFeedbackReportExport;
use App\Exports\TestReportExport;
use App\Http\Controllers\BaseController;
use App\Imports\importTestsParticipants;
use App\Models\Answer;
use App\Models\Circle;
use App\Models\EmailAction;
use App\Models\EmailTemplate;
use App\Models\Lob;
use App\Models\ManagerTrainings;
use App\Models\Question;
use App\Models\Region;
use App\Models\RetailAssignedTest;
use App\Models\Test;
use App\Models\TestAttendee;
use App\Models\TestCategory;
use App\Models\TestParticipants;
use App\Models\TestResult;
use App\Models\TrainerTrainings;
use App\Models\Training;
use App\Models\TrainingTestParticipants;
use App\Models\TrainingTestResult;
use App\Models\TrainingType;
use App\Models\User;
use App\Models\UserAssignedTestQuestion;
use App\Notifications\AssignTestNotification;
use Auth;
use Config;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Mail;
use Notification;
use Redirect;
use Session;
use URL;
use Validator;
use View;

class TestController extends BaseController
{

    public $model = 'Test';
    public $sectionName = 'Test';
    public $sectionNameSingular = 'Test';

    public function __construct()
    {
        parent::__construct();
        View::share('modelName', $this->model);
        View::share('sectionName', $this->sectionName);
        View::share('sectionNameSingular', $this->sectionNameSingular);
    }

    public function index(Request $request)
    {
        $DB = Test::query()->where('type', '!=', 'feedback_test');

        if (Auth::user()->user_role_id == MANAGER_ROLE_ID) {
            $created_by_self_ids = $DB->where('user_id', Auth::user()->id)->pluck('id')->toArray();

            $my_assign_test_ids = ManagerTrainings::where("user_id", Auth::user()->id)->where('test_id', '!=', '')->pluck('test_id')->toArray();

            $users = $DB->whereIn('tests.id', $created_by_self_ids)
                ->orWhereIn('tests.id', $my_assign_test_ids);
        } else {
            $DB = $DB;
        }
        $searchVariable = array();
        $inputGet = $request->all();
        if (($request->all())) {
            $searchData = $request->all();
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
                $searchVariable = array_merge($searchVariable, array($fieldName => $fieldValue));
            }
        }

        $DB->leftJoin('users', 'users.id', '=', 'tests.user_id')->join('test_categories', 'test_categories.id', '=', 'tests.category_id')->select('tests.*', 'users.first_name as created_by', 'test_categories.name as category_name');
        $sortBy = ($request->get('sortBy')) ? $request->get('sortBy') : 'created_at';
        $order = ($request->get('order')) ? $request->get('order') : 'DESC';
        // dd($order,$sortBy);
        $results = $DB->orderBy($sortBy, $order)->paginate(100);
        $complete_string = $request->query();
        unset($complete_string["sortBy"]);
        unset($complete_string["order"]);
        $query_string = http_build_query($complete_string);
        $results->appends($request->all())->render();
        // echo '<pre>'; print_r($results); die;

        session(['filteredResult' => $results]);

        $training_manager = User::where("is_deleted", 0)->where("user_role_id", MANAGER_ROLE_ID)->pluck('first_name', 'id')->toArray();

        $trainers = User::where("is_deleted", 0)->where("user_role_id", TRAINER_ROLE_ID)->pluck('first_name', 'id')->toArray();

        // $selected_training_trainers = TrainerTrainings::where('test_id', $modelId)->pluck('user_id');
        // echo '<pre>'; print_r($thisData); die;

        return view("admin.Test.index", compact('results', 'searchVariable', 'sortBy', 'order', 'query_string', 'training_manager', 'trainers'));
    }

    public function add()
    {
        $TestCategory = TestCategory::pluck('name', 'id')->toArray();
        $region = Region::pluck('region', 'id')->toArray();
        $lob = Lob::pluck('lob', 'id')->toArray();
        $circle = Circle::pluck('circle', 'id')->toArray();
        $TrainingType = TrainingType::pluck('type', 'id')->toArray();
        $training_manager = User::where("is_deleted", 0)->where("user_role_id", MANAGER_ROLE_ID)->pluck('first_name', 'id')->toArray();
        $trainers = User::where("is_deleted", 0)->where("user_role_id", TRAINER_ROLE_ID)->pluck('first_name', 'id')->toArray();

        return view("admin.Test.add", compact('TestCategory', 'TrainingType', 'training_manager', 'circle', 'lob', 'region', 'trainers'));
    }

    public function save(Request $request)
    {
        dd('1');
        $request->replace($this->arrayStripTags($request->all()));
        $thisData = $request->all();

        $rules = [
            'category_id' => 'required',
            'title' => 'required',
            // 'lob' => 'required',
            // 'region' => 'required',
            // 'circle' => 'required',
            'type' => 'required',
            'minimum_marks' => 'required',
            'number_of_attempts' => 'required',
            'time_of_test' => 'required',
            // 'document' => 'required',
            'start_date_time' => 'required',
            'end_date_time' => 'required',
            'thumbnail' => $request->id ? 'nullable' : 'required',  // thumbnail required only on create
            'publish_result' => 'required',
        ];

        $validator = Validator::make($thisData, $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        // Prepare data array for saving
        $request->publish_result == 0 ? $publishResult = '1' :  $publishResult = '0';
        $data = [
            'category_id' => $request->category_id,
            'title' => $request->title,
            'type' => $request->type,
            'minimum_marks' => $request->minimum_marks,
            'user_id' => Auth::id(),
            'number_of_attempts' => $request->number_of_attempts,
            'time_of_test' => $request->time_of_test,
            'number_of_questions' => $request->number_of_questions ?? '0',
            'region' => $request->region,
            'circle' => $request->circle,
            'lob' => $request->lob,
            'start_date_time' => $request->start_date_time,
            'end_date_time' => $request->end_date_time,
            'publish_result' => $publishResult,
            'description' => $request->description,
        ];

        // Handle file upload if exists
        if ($request->hasFile('thumbnail')) {
            $extension = $request->file('thumbnail')->getClientOriginalExtension();
            $fileName = time() . '-thumbnail.' . $extension;

            $folderName = strtoupper(date('M') . date('Y')) . "/";
            $folderPath = TRAINING_DOCUMENT_ROOT_PATH . $folderName;
            if (!File::exists($folderPath)) {
                File::makeDirectory($folderPath, 0777, true);
            }
            if ($request->file('thumbnail')->move($folderPath, $fileName)) {
                $data['thumbnail'] = $folderName . $fileName;
            }
        }

        try {
            $obj = Test::updateOrCreate(
                ['id' => $request->id],
                $data
            );

            $test_id = $obj->id;

            if ($test_id) {
                if (isset($thisData['training_manager']) && !empty($thisData['training_manager'])) {
                    ManagerTrainings::where('test_id', $test_id)->delete();
                    foreach ($thisData['training_manager'] as $user_id) {
                        $object = new ManagerTrainings;
                        $object->test_id = $test_id;
                        $object->user_id = $user_id;
                        $object->save();
                    }
                } else {
                    ManagerTrainings::where('test_id', $test_id)->delete();
                }

                if (isset($thisData['training_trainer']) && !empty($thisData['training_trainer'])) {
                    TrainerTrainings::where('test_id', $test_id)->delete();
                    foreach ($thisData['training_trainer'] as $user_id) {
                        $object = new TrainerTrainings;
                        $object->test_id = $test_id;
                        $object->user_id = $user_id;
                        $object->save();
                    }
                } else {
                    TrainerTrainings::where('test_id', $test_id)->delete();
                }
            }

            if (!$obj) {
                Session::flash('error', __(config('constants.REC_ADD_FAILED')));
            } else {
                $message = $request->id ? __(config('constants.REC_UPDATE_SUCCESS'), ['section' => $this->sectionNameSingular])
                    : __(config('constants.REC_ADD_SUCCESS'), ['section' => $this->sectionNameSingular]);
                Session::flash('success', $message);
            }
            return redirect()->route('Test.index');
        } catch (\Exception $e) {
            Session::flash('error', __(config('constants.FLASH_TRY_CATCH')));
            return redirect()->route('Test.index');
        }
    }


    public function changeStatus($modelId = 0, $status = 0)
    {
        if ($status == 0) {
            $statusMessage = trans($this->sectionNameSingular . " has been deactivated successfully");
        } else {
            $statusMessage = trans($this->sectionNameSingular . " has been activated successfully");
        }

        Test::where('id', $modelId)->update(array('is_active' => $status));
        Session::flash('flash_notice', $statusMessage);
        return Redirect::back();
    }

    public function view($modelId = 0)
    {

        $model = Test::find($modelId);

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

        $testType = Test::where('id', $modelId)->value('type');
        if ($testType == 'training_test') {
            $trainee_ids = TrainingTestParticipants::where("test_id", $modelId)->pluck('trainee_id')->toArray();
        } else {
            $trainee_ids = TestParticipants::where("test_id", $modelId)->pluck('trainee_id')->toArray();
        }

        $user_details = User::whereIn("id", $trainee_ids)->get();

        $test_attendee_details = TestAttendee::whereIn('link_id', $trainee_ids)->get();

        $trainee_details = $user_details->merge($test_attendee_details);

        $questions = Question::where("test_id", $modelId)->get();

        // $trainee_details    = $trainee_details;
        // $trainer_details    = $trainer_details;
        // $manager_details    = $manager_details;

        return View::make("admin.Test.view", compact('model', 'trainee_details', 'trainer_details', 'manager_details', 'questions'));
        // echo '<pre>'; print_r($createdBy); die;
    }

    public function edit($modelId = 0)
    {
        $model = Test::find($modelId);

        if (empty($model)) {
            Session::flash('error', __(config('constants.REC_NOT_FOUND')));
            return redirect()->route('Test.index');
        }

        $TestCategory = TestCategory::pluck('name', 'id')->toArray();
        $TrainingType = TrainingType::pluck('type', 'id')->toArray();
        $training_manager = User::where("is_deleted", 0)->where("user_role_id", MANAGER_ROLE_ID)->pluck('first_name', 'id')->toArray();
        $selected_training_manager = ManagerTrainings::where('test_id', $modelId)->pluck('user_id');
        $region = Region::pluck('region', 'id')->toArray();
        $lob = Lob::pluck('lob', 'id')->toArray();
        $circle = Circle::pluck('circle', 'id')->toArray();

        $trainers = User::where("is_deleted", 0)->where("user_role_id", TRAINER_ROLE_ID)->pluck('first_name', 'id')->toArray();
        $selected_training_trainers = TrainerTrainings::where('test_id', $modelId)->pluck('user_id');

        return view("admin.Test.add", compact('model', 'TestCategory', 'TrainingType', 'training_manager', 'selected_training_manager', 'region', 'lob', 'circle', 'trainers', 'selected_training_trainers'));
    }

    public function update($modelId)
    {
        $model = Test::findorFail($modelId);
        if (empty($model)) {
            return Redirect::back();
        }

        $request->replace($this->arrayStripTags($request->all()));
        $thisData = $request->all();
        //     echo '<pre>'; print_r($thisData); die;

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
        //     $rules['training_trainer'] = 'required';
        // } else {
        //     $rules['training_manager'] = 'required';
        // }

        $validator = Validator::make($thisData, $rules);
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)->withInput();
        } else {
            $obj = $model;
            $obj->category_id = $request->get('category_id');
            $obj->type = $request->get('type');
            //    $obj->user_id                   = Auth::user()->id;
            $obj->title = $request->get('title');
            $obj->region = $request->get('region');
            $obj->circle = $request->get('circle');
            $obj->lob = $request->get('lob');
            $obj->minimum_marks = $request->get('minimum_marks');
            $obj->number_of_attempts = $request->get('number_of_attempts');
            $obj->time_of_test = $request->get('time_of_test');
            $obj->number_of_questions = $request->get('number_of_questions') ?? '0';
            $obj->start_date_time = $request->get('start_date_time');
            $obj->end_date_time = $request->get('end_date_time');
            $obj->publish_result = $request->get('publish_result');
            $obj->description = $request->get('description');
            if ($request->hasFile('thumbnail')) {
                $extension = $request->file('thumbnail')->getClientOriginalExtension();
                $fileName = time() . '-thumbnail.' . $extension;

                $folderName = strtoupper(date('M') . date('Y')) . "/";
                $folderPath = TRAINING_DOCUMENT_ROOT_PATH . $folderName;
                if (!File::exists($folderPath)) {
                    File::makeDirectory($folderPath, $mode = 0777, true);
                }
                if ($request->file('thumbnail')->move($folderPath, $fileName)) {
                    $obj->thumbnail = $folderName . $fileName;
                }
            }
            $obj->save();
            $test_id = $obj->id;

            if ($test_id) {
                if (isset($thisData['training_manager']) && !empty($thisData['training_manager'])) {
                    ManagerTrainings::where('test_id', $test_id)->delete();

                    foreach ($thisData['training_manager'] as $user_id) {
                        //    print_r($user_id); die;
                        $object = new ManagerTrainings;
                        $object->test_id = $test_id;
                        $object->user_id = $user_id;
                        $object->save();
                    }
                }
            }
            if ($test_id) {
                if (isset($thisData['training_trainer']) && !empty($thisData['training_trainer'])) {
                    TrainerTrainings::where('test_id', $test_id)->delete();

                    foreach ($thisData['training_trainer'] as $user_id) {
                        //    print_r($user_id); die;
                        $object = new TrainerTrainings;
                        $object->test_id = $test_id;
                        $object->user_id = $user_id;
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
    }

    public function delete($id = 0)
    {
        $model = Test::find($id);
        if (empty($model)) {
            return Redirect::back();
        }
        if ($id) {
            Test::where('id', $id)->delete();
            Session::flash('flash_notice', trans($this->sectionNameSingular . " has been removed successfully"));
        }
        return Redirect::back();
    }

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

            $projects = QDS_PROJECT_LIST;
            $methods = ['fromExcel' => 'From Excel',  'fromUser' => 'From Users'];
            $existingUserIds = TestParticipants::with('user')
                ->where('test_id', $test_id)
                ->get()
                ->pluck('user.employee_id')
                ->toArray();
            $users = User::where("is_deleted", 0)->where("user_role_id", TRAINEE_ROLE_ID)->pluck('fullname', 'employee_id')
                ->toArray();
            $assginTo = ['Freelancer' => 'Freelancer', 'In-House' => 'In-House'];
            // API call to RetailIQ

            $clientResponse = Http::withOptions([
                'verify' => false, // Disable SSL cert check
            ])->get('https://retailanalytics.qdegrees.com/api/get_client_list');

            $clients = $clientResponse->successful()
                ? collect($clientResponse['data'])->pluck('company_name', 'id')->toArray()
                : [];

            return View::make("admin.Test.uploadTestsParticipants", compact('test_id', 'assginTo', 'clients', 'existingEmails', 'projects', 'methods', 'existingUserIds', 'users'));
        } else {
            Session::flash('success', 'Please add questions to this test before uploading participants.');
            return Redirect::back();
        }
    }

    public function importTestsUsersDirectly(Request $request, $test_id)
    {
        // Replace request data to remove unwanted tags
        $thisData = $request->all();
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
            $user = User::where('email', $email)->first();
            if ($user) {
                return redirect()->back()->with('error', "This user Already Registered in LMS assign test form above.");
            }

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

    public function importTests($test_id = 0, Request $request)
    {

        $import = new importTestsParticipants($test_id);
        Excel::import($import, $request->file('file')); // Correct way

        $errors = $import->getErrors();

        if (count($errors) > 0) {
            return view('errors.importTraineeError')->with('errors', $errors);
        }

        return redirect()->back()->with('success', 'Tests Participants Added Successfully!');
    }

    public function AssignManager(Request $request)
    {

        $thisData = $request->all();
        $test_id = $thisData['test_id'];
        if ($test_id) {
            if (isset($thisData['training_manager']) && !empty($thisData['training_manager'])) {
                ManagerTrainings::where('test_id', $test_id)->delete();

                foreach ($thisData['training_manager'] as $user_id) {
                    //    print_r($user_id); die;
                    $object = new ManagerTrainings;
                    $object->test_id = $test_id;
                    $object->user_id = $user_id;
                    $object->save();
                }
            }
        }

        Session::flash('flash_notice', trans(" Manager has been Assign successfully"));
        return Redirect::back();
    } // end delete()

    public function AssignTrainer()
    {

        $thisData = $request->all();
        //    echo '<pre>'; print_r($thisData); die;
        $test_id = $thisData['test_id'];
        if ($test_id) {
            if (isset($thisData['training_trainer']) && !empty($thisData['training_trainer'])) {
                TrainerTrainings::where('test_id', $test_id)->delete();

                foreach ($thisData['training_trainer'] as $user_id) {
                    //    print_r($user_id); die;
                    $object = new TrainerTrainings;
                    $object->test_id = $test_id;
                    $object->user_id = $user_id;
                    $object->save();
                }
            }
        }
        Session::flash('flash_notice', trans(" Trainer has been Assign successfully"));
        return Redirect::back();
    }
    public function testReport($test_id)
    {
        $test = Test::findOrFail($test_id);

        if ($test->type == "feedback_test") {
            $testParticipants = $test->test_participants;
            $userDetails = User::whereIn('id', $testParticipants->pluck('trainee_id'))->get();
            $testAttendeeDetails = TestAttendee::whereIn('link_id', $testParticipants->pluck('trainee_id'))->get();
            $traineeDetails = $userDetails->merge($testAttendeeDetails);
            $participants = $traineeDetails;
            $questions = Question::where('test_id', $test_id)
                ->get();
            $testResults = TestResult::where('test_id', $test_id)->with('user_details')
                ->get();
            return Excel::download(new TestFeedbackReportExport($participants, $questions, $testResults), "{$test->title}_test_report.xlsx");
        } else {
            // For regular tests (non-feedback_test)
            $testParticipants = $test->test_participants;
            $userDetails = User::whereIn('id', $testParticipants->pluck('trainee_id'))->get();
            $testAttendeeDetails = TestAttendee::whereIn('link_id', $testParticipants->pluck('trainee_id'))->get();
            $traineeDetails = $userDetails->merge($testAttendeeDetails);
            $participants = $traineeDetails;
            $questions = Question::where('test_id', $test_id)->get();

            foreach ($participants as $participant) {
                $participantId = $participant instanceof \App\Models\User  ? $participant->id : $participant->link_id;
                $this->calculateAndSaveTestResult($test_id, $participantId);
            }

            $testResults = TestResult::where('test_id', $test_id)->with('user_details')->get();

            return Excel::download(new TestReportExport($participants, $questions, $testResults), "{$test->title}_test_report.xlsx");
        }
    }

    public function calculateAndSaveTestResult($testId, $userId)
    {
        $existingResult = TestResult::where('test_id', $testId)->where('user_id', $userId)->first();

        if (!$existingResult) {
            $answers = Answer::where('user_id', $userId)->where('test_id', $testId)->get();
            $totalMarks = 0;
            $obtainedMarks = 0;
            $percentage = 0;
            $hasFreeTextQuestion = false;
            $resultStatus = 'FreeText_Paper';

            $assignedQuestionIds = UserAssignedTestQuestion::where('trainee_id', $userId)
                ->where('test_id', $testId)
                ->pluck('questions_id')
                ->toArray();

            foreach ($answers as $answer) {
                $question = $answer->question;
                if (in_array($question->id, $assignedQuestionIds)) {
                    $validAnswer = explode(',', $answer->valid_answer);
                    $userAnswer = explode(',', $answer->answer_id);
                    sort($validAnswer);
                    sort($userAnswer);
                    $totalMarks += $question->marks;

                    // Calculate obtained marks for correct answers
                    if ($validAnswer === $userAnswer && $answer->free_text_answer === null) {
                        $obtainedMarks += $question->marks;
                    }
                    if ($question->question_type == 'FreeText') {
                        $hasFreeTextQuestion = true;
                    }
                }
            }

            if (!$hasFreeTextQuestion) {
                $percentage = $totalMarks > 0 ? ($obtainedMarks / $totalMarks) * 100 : 0;

                // Determine if the user passed or failed
                $minimumMarks = Test::where('id', $testId)->value('minimum_marks');
                $resultStatus = ($percentage >= $minimumMarks) ? 'Passed' : 'Failed';
            }

            // Save the calculated result in the TestResult table
            TestResult::create([
                'test_id' => $testId,
                'user_id' => $userId,
                'total_questions' => count($answers),
                'total_attemted_questions' => count($assignedQuestionIds),
                'total_marks' => $totalMarks,
                'obtain_marks' => $obtainedMarks,
                'percentage' => $percentage,
                'result' => $resultStatus,
                'user_attempts' => 1,
            ]);
        }
    }



    public function assginTestParticipants(Request $request)
    {
        $test_id = $request->test_id;

        DB::beginTransaction();

        try {
            $test = Test::findOrFail($test_id);

            // Delete existing test participants
            TestParticipants::where('test_id', $test_id)->delete();

            foreach ($request->empIds as $empId) {
                $user = User::where('olms_id', $empId)->first();

                if (!$user) {
                    continue;
                }

                // Create participant
                TestParticipants::create([
                    'test_id' => $test_id,
                    'trainee_id' => $user->id,
                    'number_of_attempts' => $test->number_of_attempts,
                ]);

                // $this->sendNotificationAndMail($user, $test_id);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Test participants assigned and notified successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    public function retailAssignTest(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'client_id'    => 'required|integer',
            'test_id'  => 'required|integer',
            'validity'  => 'required',
            'assginTo'  => 'required',
        ]);

        $campaignId = $request->filled('campaign_id') ? implode(',', $request->campaign_id) : null;
        $storeCodes = is_array($request->store_code) ? implode(',', $request->store_code) : null;

        $query = RetailAssignedTest::where('client_id', $request->client_id)
            ->where('test_id', $request->test_id);

        if (!is_null($campaignId)) {
            $query->where('campaign_id', $campaignId);
        } else {
            $query->whereNull('campaign_id');
        }

        $existing = $query->first();

        if ($existing) {
            $existing->store_code = $storeCodes;
            $existing->save();
        } else {
            RetailAssignedTest::create([
                'test_id' => $request->test_id,
                'client_id'   => $request->client_id,
                'campaign_id' => $campaignId,
                'store_code'  => $storeCodes,
                'assginTo'  => $request->assginTo,
                'validity'  => $request->validity,
            ]);
        }

        return redirect()->back()->with('success', 'Training successfully assigned to the client.');
    }

    public function traineeTestWiseReport($test_id, $user_id)
    {
        $userData = User::find($user_id);
        $test     = Test::find($test_id);
        if ($test->type == 'training_test') {
            $testData = TrainingTestParticipants::where('trainee_id', $user_id)
                ->where('test_id', $test_id)
                ->with('test_details')
                ->first();
        } else {
            $testData = TestParticipants::where('trainee_id', $user_id)
                ->where('test_id', $test_id)
                ->with('test_details')
                ->first();
        }

        if ($test->type == 'training_test') {
            $testResults = TrainingTestResult::where('user_id', $user_id)
                ->where('test_id', $test_id)
                ->orderBy('attempt_number', 'desc')
                ->get();
        } else {
            $testResults = TestResult::where('user_id', $user_id)
                ->where('test_id', $test_id)
                ->orderBy('user_attempts', 'desc')
                ->get();
        }

        if ($testResults->isEmpty()) {
            Session::flash('error', 'This test has not been submitted by this user.');
            return Redirect::back();
        }

        if ($test->type == 'training_test') {
        $attemptNumber = request()->get('attempt_number', $testResults->first()->attempt_number);
        } else {
        $attemptNumber = request()->get('attempt_number', $testResults->first()->user_attempts);
        }

        if ($test->type == 'training_test') {
            $latestAttempt = $testResults->where('attempt_number', $attemptNumber)->first();
        } else {
            $latestAttempt = $testResults->where('user_attempts', $attemptNumber)->first();
        }

        $questionsAlreadyAssigned = UserAssignedTestQuestion::where('test_id', $test_id)
            ->where('trainee_id', $user_id)
            ->pluck('questions_id')
            ->toArray();

        $testQuestions = Question::whereIn('id', $questionsAlreadyAssigned)
            ->where('test_id', $test_id)
            ->with('questionAttributes')
            ->get();

        $userAnswers = Answer::where('test_id', $test_id)
            ->where('user_id', $user_id)
            ->where('attempt_number', $latestAttempt->attempt_number)
            ->pluck('answer_id', 'question_id')
            ->toArray();

        if ($testResults && $userAnswers) {
            if (request()->ajax()) {
                return view('admin.Test.partials-test-report-details', compact('userData', 'test', 'testData', 'testQuestions', 'userAnswers', 'latestAttempt', 'testResults', 'attemptNumber'));
            }
            return view("admin.Test.test-report", compact('userData', 'test', 'testData', 'testQuestions', 'userAnswers', 'testResults', 'latestAttempt', 'attemptNumber'));
        } else {
            Session::flash('error', 'This test has not been submitted by this user.');

            return Redirect::back();
        }
    }
}
