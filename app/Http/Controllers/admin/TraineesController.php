<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Imports\importTrainees;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Region;
use App\Models\Lob;
use App\Models\Designation;
use App\Models\Circle;
use App\Models\Training;
use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use App\Models\UserAssignedTestQuestion;
use App\Models\EmailTemplate;
use App\Models\EmailAction;
use App\Models\TestResult;
use App\Exports\TraineesExport;
use App\Exports\exportAllTrainee;
use App\Models\TestParticipants;
use Blade, Config, Cache, Cookie, DB, File,  Input, Mail, Redirect, Response, Session, URL, View, Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TraineesController extends BaseController
{

    public $model        =    'Trainees';
    public $sectionName    =    'Users';
    public $sectionNameSingular    =    'User';

    public function __construct()
    {
        parent::__construct();
        View::share('modelName', $this->model);
        View::share('sectionName', $this->sectionName);
        View::share('sectionNameSingular', $this->sectionNameSingular);
    }

    public function index(Request $request)
    {

        $DB                  =    User::query();
        $searchVariable      =    array();
        $inputGet            =    $request->all();
        if (($request->all())) {
            $searchData            =    $request->all();
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
            if ((!empty($searchData['date_from'])) && (!empty($searchData['date_to']))) {
                $dateS = $searchData['date_from'];
                $dateE = $searchData['date_to'];
                $DB->whereBetween('users.created_at', [$dateS . " 00:00:00", $dateE . " 23:59:59"]);
            } elseif (!empty($searchData['date_from'])) {
                $dateS = $searchData['date_from'];
                $DB->where('users.created_at', '>=', [$dateS . " 00:00:00"]);
            } elseif (!empty($searchData['date_to'])) {
                $dateE = $searchData['date_to'];
                $DB->where('users.created_at', '<=', [$dateE . " 00:00:00"]);
            }

            foreach ($searchData as $fieldName => $fieldValue) {
                if ($fieldValue != "") {
                    if ($fieldName == "fullname") {
                        $DB->where("users.fullname", 'like', '%' . $fieldValue . '%');
                    }
                    if ($fieldName == "mobile_number") {
                        $DB->where("users.mobile_number", 'like', '%' . $fieldValue . '%');
                    }
                    if ($fieldName == "email") {
                        $DB->where("users.email", 'like', '%' . $fieldValue . '%');
                    }
                    if ($fieldName == "is_active") {
                        $DB->where("users.is_active", $fieldValue);
                    }
                    if ($fieldName == "is_certified") {
                        $DB->where("users.is_certified", $fieldValue);
                    }
                    if ($fieldName == "olms_id") {
                        $DB->where("users.olms_id", $fieldValue);
                    }
                }
                $searchVariable    =    array_merge($searchVariable, array($fieldName => $fieldValue));
            }
        }
        $DB->where("is_deleted", 0)->where("user_role_id", '!=', SUPER_ADMIN_ROLE_ID)->where("is_developer", '!=', 1);
        //$DB->where("user_role_id",Config::get('constants.user.CUSTOMER_ROLE_ID'));
        $sortBy = ($request->get('sortBy')) ? $request->get('sortBy') : 'created_at';
        $order  = ($request->get('order')) ? $request->get('order')   : 'DESC';
        $results = $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
        $complete_string        =    $request->query();
        unset($complete_string["sortBy"]);
        unset($complete_string["order"]);
        $query_string            =    http_build_query($complete_string);
        $results->appends($request->all())->render();
        session(['exportTrainees' => $results]);

        $designation = DB::table('designations')->pluck('designation', 'designation')->toArray();
        // return $results; die;

        return  View::make("admin.Trainees.index", compact('results', 'searchVariable', 'sortBy', 'order', 'query_string', 'designation'));
    } 

    public function add()
    {
        $region =     Region::pluck('region', 'region')->toArray();
        $lob = Lob::pluck('lob', 'lob')->toArray();
        $circle = Circle::pluck('circle', 'circle')->toArray();
        $designation = Designation::pluck('designation', 'designation')->toArray();
        return view("admin.Trainees.add", compact('region', 'lob', 'circle', 'designation'));
    }

    public function save(Request $request)
    {
        $request->replace($this->arrayStripTags($request->all()));
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:55',
            'last_name' => 'required|max:55',
            'lob' => 'required',
            'designation' => 'required',
            'employee_id' => "required|unique:users,employee_id,{$request->id}",
            'region' => 'required',
            'circle' => 'required',
            'gender' => 'required|in:male,female',
            'email' => "required|email|regex:/(.+)@(.+)\.(.+)/i|unique:users,email,{$request->id}",
            'mobile_number' => "required|min:10|regex:/^([0-9\s\-\+\(\)]*)$/|unique:users,mobile_number,{$request->id}",
        ], [
            "first_name.required" => trans("The first name field is required."),
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $userData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'fullname' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'employee_id' => $request->employee_id,
            'olms_id' => $request->employee_id,
            'region' => $request->region,
            'circle' => $request->circle,
            'lob' => $request->lob,
            'designation' => $request->designation,
            'gender' => $request->gender,
            'mobile_number' => $request->mobile_number,
        ];

        $designation = strtolower($request->designation);
        if (strpos($designation, 'training manager') !== false) {
            $userData['user_role_id'] = MANAGER_ROLE_ID;
        } elseif (strpos($designation, 'trainer') !== false) {
            $userData['user_role_id'] = TRAINER_ROLE_ID;
        } else {
            $userData['user_role_id'] = TRAINEE_ROLE_ID;
        }

        $user = User::updateOrCreate(
            ['id' => $request->id],
            $userData
        );

        if (!$request->id) {
            $user->update([
                'validate_string' => md5(time() . $request->email),
                'password' => Hash::make('Lms@1234'),
                'is_active' => 1,
                'is_mobile_verified' => 1,
                'is_email_verified' => 1,
                'parent_id' => Auth::id(),
            ]);

            // Send welcome email only for new users
            $settingsEmail = Config::get('Site.email');
            $full_name = $user->fullname;
            $email = $user->email;
            $password = 'Lms@1234';

            $route_url = match ($user->user_role_id) {
                MANAGER_ROLE_ID => URL::to('/admin'),
                TRAINER_ROLE_ID => URL::to('/trainer'),
                default => URL::to('/login'),
            };

            $emailActions = EmailAction::where('action', 'user_registration_information')->first();
            $emailTemplate = EmailTemplate::where('action', 'user_registration_information')
                ->first(['name', 'subject', 'action', 'body']);

            if ($emailActions && $emailTemplate) {
                $constants = array_map(fn($val) => '{' . $val . '}', explode(',', $emailActions->options));
                $repArray = [$full_name, $email, $user->mobile_number, $email, $password, $route_url];
                $messageBody = str_replace($constants, $repArray, $emailTemplate->body);

                $this->sendMail($email, $full_name, $emailTemplate->subject, $messageBody, $settingsEmail);
            }
        }

        Session::flash('success', trans($this->sectionNameSingular . " has been " . ($request->id ? "updated" : "added") . " successfully"));
        return redirect()->route($this->model . ".index");
    }

    public function changeStatus($modelId = 0, $status = 0)
    {
        if ($status == 0) {
            $statusMessage    =    trans($this->sectionNameSingular . " has been deactivated successfully");
        } else {
            $statusMessage    =    trans($this->sectionNameSingular . " has been activated successfully");
        }
        User::where('id', $modelId)->update(array('is_active' => $status));
        Session::flash('flash_notice', $statusMessage);
        return Redirect::back();
    }

    public function edit($modelId = 0)
    {
        $model                    =    User::findorFail($modelId);
        if (empty($model)) {
            return Redirect::back();
        }
        $region =     Region::pluck('region', 'region')->toArray();
        $lob = Lob::pluck('lob', 'lob')->toArray();
        $circle = Circle::pluck('circle', 'circle')->toArray();
        $designation = Designation::pluck('designation', 'designation')->toArray();
        return view("admin.Trainees.add", compact('model', 'region', 'lob', 'circle', 'designation'));
    }

    public function delete($userId = 0)
    {
        $userDetails = User::find($userId);
        if (empty($userDetails)) {
            return redirect()->route($this->model . ".index");
        }
        if ($userId) {
            $email = 'delete_' . $userId . '_' . $userDetails->email;
            $mobile_number = 'delete_' . $userId . '_' . $userDetails->mobile_number;
            $employee_id = 'delete_' . $userId . '_' . $userDetails->employee_id;

            User::where('id', $userId)->update([
                'is_deleted' => 1,
                'email' => $email,
                'mobile_number' => $mobile_number,
                'employee_id' => $employee_id,
                'olms_id' => $employee_id,
            ]);
            $userDetails->delete();
            Session::flash('flash_notice', trans($this->sectionNameSingular . " has been removed successfully"));
        }
        return redirect()->back();
    }

    public function view($modelId = 0)
    {
        $model    =    User::where('id', "$modelId")->select('users.*')->first();
        if (empty($model)) {
            return Redirect::route($this->model . ".index");
        }
        return  View::make("admin.$this->model.view", compact('model'));
    }

    public function importTrainees()
    {
        $import = new importTrainees();
        Excel::import($import, request()->file('file'));
        $errors = $import->getErrors();

        if (count($errors) > 0) {
            return view('errors.importTraineeError')->with('errors', $errors);
        }
        return redirect()->back()->with('success', 'Users imported successfully!');
    }

    public function exportTrainees(Request $request)
    {
        $filteredResult = Session::get('exportTrainees');

        $filteredResult = $filteredResult->map(function ($item) {
            $selectedFields = $item->only([
                'id',
                'olms_id',
                'email',
                'password',
                'employee_id',
                'first_name',
                'middle_name',
                'avaya_id_pbx_id',
                'fullname',
                'last_name',
                'lob',
                'circle',
                'gender',
                'poi',
                'mobile_number',
                'password',
                'region',
                'user_role_id',
                'validate_string',
                'olms_id',
                'designation',
                'date_of_birth',
                'date_of_joining',
                'ext_qa',
                'ext_qa_olms',
                'crm_id',
                'qms_id',
                'lms_access',
                'trainer_name',
                'trainer_olms',
                'location',
                'is_active',
                'created_at',
            ]);
            // $selectedFields['description'] = strip_tags($selectedFields['description']);

            if ($selectedFields['is_active'] == '1') {
                $selectedFields['is_active'] = 'Activated';
            } else {
                $selectedFields['is_active'] = 'Deactivated';
            }

            return $selectedFields;
        });

        $export = new TraineesExport($filteredResult);
        return Excel::download($export, 'users.xlsx');
    }

    public function exportTraineesAll()
    {
        $fileName = 'all-users.xlsx';
        return Excel::download(new exportAllTrainee(), $fileName);
    }

    public function ChangeDesignation(Request $request)
    {
        $thisData                    =    $request->all();
        //echo '<pre>'; print_r($thisData); die;
        $user_id   = $thisData['user_id'];

        $userAlreadyExist = User::where('id', $user_id)->first();
        if ($user_id) {
            if (isset($thisData['designation']) && !empty($thisData['designation'])) {
                $designation   = $thisData['designation'];
                if (stripos($designation, 'Manager') !== false) {
                    // Update user_role_id to 4 for Manager
                    $userAlreadyExist->update(['designation' => $designation, 'user_role_id' => 4]);
                } elseif (stripos($designation, 'Trainer') !== false) {
                    // Update user_role_id to 2 for Trainer
                    $userAlreadyExist->update(['designation' => $designation, 'user_role_id' => 2]);
                } else {
                    $userAlreadyExist->update(['designation' => $designation]);
                }
            }
        }

        Session::flash('flash_notice', trans(" Designation has been changed successfully"));
        return Redirect::back();
    }

    public function DeleteMultiple(Request $request)
    {
        $formData = $request->all();
        foreach ($formData['data'] as $userId) {
            $userDetails    =    User::find($userId);
            if (!empty($userDetails)) {
                $email             =    'delete_' . $userId . '_' . $userDetails->email;
                $mobile_number         =    'delete_' . $userId . '_' . $userDetails->mobile_number;
                User::where('id', $userId)->update(array('is_deleted' => 1, 'email' => $email, 'mobile_number' => $mobile_number));
            }
        }
        Session::flash('flash_notice', trans("users has been removed successfully"));
        return Redirect::back();
    }

    public function traineeWiseReport($id)
    {
        $assignedTests = TestParticipants::where('trainee_id', $id)->with('test_details')->get();

        $allTest = TestParticipants::where('trainee_id', $id)->where('type', 1)->with(['test_details', 'user_test_results'])->get();

        $allTraining = Training::with(['training_participants', 'training_courses', 'training_courses.test', 'training_results'])->get();

        $countCoursesWithTestId = $allTraining->pluck('training_courses')->flatten(1)->whereNotNull('test_id')->count();


        $participants = TestParticipants::where('trainee_id', $id)->get();

        $testResults = [];

        foreach ($participants as $participant) {

            $testId = $participant->test_id;

            $testDetails = Test::find($testId);
            $result = TestResult::where('test_id', $testId)->where('user_id', $id)->first();

            if ($testDetails && $result) {

                $testResults[] = [
                    'test_details' => $testDetails,
                    'test_results' => $result,
                ];
            }
        }

        return  View::make("admin.$this->model.reports", compact('allTest', 'allTraining', 'testResults'));
    }

    public function traineeTestWiseReport(Request $request, $user_id, $test_id)
    {
        $userData = User::where('id', $user_id)->first();

        $testData = TestParticipants::where('trainee_id', $user_id)
            ->where('test_id', $test_id)
            ->with(['test_details'])
            ->first();

        $testResult = TestResult::where('user_id', $user_id)
            ->where('test_id', $test_id)->first();

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
            ->pluck('answer_id', 'question_id')
            ->toArray();
        return view("admin.$this->model.test-report", compact('userData', 'testData', 'testQuestions', 'userAnswers', 'testResult'));
    }
}
