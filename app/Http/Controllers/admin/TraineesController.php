<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Imports\importTrainers;
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
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, Redirect, Request, Response, Session, URL, View, Validator;

/**
 * TraineesController Controller
 *
 * Add your methods in the class below
 *
 */
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

    /**
     * Function for display all Users
     *
     * @param null
     *
     * @return view page.
     */
    public function index()
    {

        $DB                  =    User::query();
        $searchVariable      =    array();
        $inputGet            =    Request::all();
        if ((Request::all())) {
            $searchData            =    Request::all();
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
        $sortBy = (Request::get('sortBy')) ? Request::get('sortBy') : 'created_at';
        $order  = (Request::get('order')) ? Request::get('order')   : 'DESC';
        $results = $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
        $complete_string        =    Request::query();
        unset($complete_string["sortBy"]);
        unset($complete_string["order"]);
        $query_string            =    http_build_query($complete_string);
        $results->appends(Request::all())->render();
        session(['exportTrainees' => $results]);

        $designation = DB::table('designations')->pluck('designation', 'designation')->toArray();
        // return $results; die;

        return  View::make("admin.$this->model.index", compact('results', 'searchVariable', 'sortBy', 'order', 'query_string', 'designation'));
    } // end index()


    /**
     * Function for add new customer
     *
     * @param null
     *
     * @return view page.
     */
    public function add()
    {

        $region =     Region::pluck('region', 'region')->toArray();
        $lob = Lob::pluck('lob', 'lob')->toArray();
        $circle = Circle::pluck('circle', 'circle')->toArray();
        $designation = Designation::pluck('designation', 'designation')->toArray();
        // $language = User::where("is_deleted",0)->pluck('language','id')->toArray();
        // $language = [
        // 	'en' => 'English',
        // 	'es' => 'Spanish',
        // ];
        //echo '<pre>'; print_r($language); die;
        return  View::make("admin.$this->model.add", compact('region', 'lob', 'circle', 'designation'));
    } // end add()

    /**
     * Function for save new customer
     *
     * @param null
     *
     * @return redirect page.
     */
    function save()
    {
        Request::replace($this->arrayStripTags(Request::all()));
        $formData                            =    Request::all();
        if (!empty($formData)) {
            $validator                       =    Validator::make(
                Request::all(),
                array(
                    'first_name'             => 'required|max:55',
                    'last_name'              => 'required|max:55',
                    'lob'                    => 'required',
                    'designation'            => 'required',
                    'employee_id'            => 'required|unique:users',

                    // 'language' 			  => 'required',
                    'region'                 => 'required',
                    'circle'                 => 'required',
                    'gender'                 => 'required|in:male,female',
                    'email'                  => 'required|max:255|email|unique:users|regex:/(.+)@(.+)\.(.+)/i',
                    'mobile_number'          => 'required|min:10|regex:/^([0-9\s\-\+\(\)]*)$/|unique:users',
                    // 'password'		     => 'required|min:8',
                    // 'confirm_password'    => 'required|same:password',
                ),
                array(
                    "first_name.required"   =>    trans("The first name field is required."),
                )
            );
            // $password 					= 	Request::get('password');
            // if (preg_match('#[0-9]#', $password) && preg_match('#[a-zA-Z]#', $password) && preg_match('#[\W]#', $password)) {
            // 	$correctPassword		=	md5($password);
            // }else{
            // 	$errors 				=	$validator->messages();
            // 	$errors->add('password', trans("Password must have be a combination of numeric, alphabet and special characters."));
            // 	return Redirect::back()->withErrors($errors)->withInput();
            // }
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            } else {
                $obj                          =  new User;
                $validateString               =  md5(time() . Request::get('email'));
                $obj->validate_string         =  $validateString;
                $obj->first_name              =  Request::get('first_name');
                $first_name                   =  Request::get("first_name");
                $last_name                    =  Request::get("last_name");
                $fullname                     =  $first_name . " " . $last_name;
                $obj->fullname                =  $fullname;

                $obj->last_name               =  Request::get('last_name');
                $obj->email                   =  Request::get('email');
                $obj->employee_id             =  Request::get('employee_id');
                $obj->parent_id               =  Auth::user()->id;
                $obj->olms_id                 =  Request::get('employee_id');
                $obj->region                  =  Request::get('region');
                $obj->circle                  =  Request::get('circle');
                $obj->lob                     =  Request::get('lob');
                $obj->designation             =  Request::get('designation');
                $obj->gender                  =  Request::get('gender');
                $obj->mobile_number           =  Request::get('mobile_number');
                $obj->password                   =  Hash::make('Lms@1234');
                $designation = Request::get('designation');
                if (strcasecmp($designation, 'Training Manager') === 0) {
                    $obj->user_role_id = MANAGER_ROLE_ID;
                } elseif (strcasecmp($designation, 'Trainer') === 0) {
                    $obj->user_role_id = TRAINER_ROLE_ID;
                } elseif (strcasecmp($designation, 'Trainee') === 0) {
                    $obj->user_role_id = TRAINEE_ROLE_ID;
                } else {
                    $obj->user_role_id = TRAINEE_ROLE_ID;
                }
                $obj->is_active               =  1;
                $obj->is_mobile_verified      =  1;
                $obj->is_email_verified       =  1;
                $obj->save();

                $userId  =    $obj->id;
                if (!$userId) {
                    Session::flash('error', trans("Something went wrong."));
                    return Redirect::back()->withInput();
                }
                //mail email and password to new registered user
                $settingsEmail             =    Config::get('Site.email');
                $full_name                =     $obj->name;
                $email                    =     $obj->email;
                $password                =     'Lms@1234';
                // $route_url                 =     URL::to('/login');
                if ($obj->user_role_id == MANAGER_ROLE_ID) {
                    $route_url                 =     URL::to('/admin');
                } elseif ($obj->user_role_id == TRAINER_ROLE_ID) {
                    $route_url                 =     URL::to('/trainer');
                } else {
                    $route_url                 =     URL::to('/login');
                }
                $click_link               =   $route_url;
                $emailActions            =     EmailAction::where('action', '=', 'user_registration_information')->get()->toArray();
                $emailTemplates            =     EmailTemplate::where('action', '=', 'user_registration_information')->get(array('name', 'subject', 'action', 'body'))->toArray();
                $cons                     =     explode(',', $emailActions[0]['options']);
                $constants                 =     array();
                foreach ($cons as $key => $val) {
                    $constants[]         =     '{' . $val . '}';
                }
                $subject                 =     $emailTemplates[0]['subject'];
                $rep_Array                 =     array($full_name, $email, $obj->mobile_number, $email, $password, $route_url);
                $messageBody            =     str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
                $mail                    =     $this->sendMail($email, $full_name, $subject, $messageBody, $settingsEmail);
                Session::flash('success', trans($this->sectionNameSingular . " has been added successfully"));
                return Redirect::route($this->model . ".index");
            }
        }
    } //end save()

    /**
     * Function for update status
     *
     * @param $modelId as id of customer
     * @param $status as status of customer
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
        User::where('id', $modelId)->update(array('is_active' => $status));
        Session::flash('flash_notice', $statusMessage);
        return Redirect::back();
    } // end changeStatus()

    /**
     * Function for display page for edit customer
     *
     * @param $modelId id  of customer
     *
     * @return view page.
     */
    public function edit($modelId = 0)
    {
        $model                    =    User::findorFail($modelId);
        // return $model;
        if (empty($model)) {
            return Redirect::back();
        }


        $region =     Region::pluck('region', 'region')->toArray();
        $lob = Lob::pluck('lob', 'lob')->toArray();
        $circle = Circle::pluck('circle', 'circle')->toArray();
        $designation = Designation::pluck('designation', 'designation')->toArray();
        return  View::make("admin.$this->model.edit", compact('model', 'region', 'lob', 'circle', 'designation'));
    } // end edit()


    /**
     * Function for update customer
     *
     * @param $modelId as id of customer
     *
     * @return redirect page.
     */
    function update($modelId)
    {
        // return $modelId;
        $model                     =     User::findorFail($modelId);
        if (empty($model)) {
            return Redirect::back();
        }
        Request::replace($this->arrayStripTags(Request::all()));
        $formData                         =     Request::all();
        if (!empty($formData)) {
            $validator                      =     Validator::make(
                Request::all(),
                array(
                    'first_name'             => 'required|max:55',
                    'last_name'              => 'required|max:55',
                    'lob'                    => 'required',
                    'designation'            => 'required',
                    'employee_id'            => "required|unique:users,employee_id,$modelId",

                    // 'language' 			  => 'required',
                    'region'                 => 'required',
                    'circle'                 => 'required',
                    'gender'                 => 'required|in:male,female',
                    'email'                  => "required|email|max:255|unique:users,email,$modelId|regex:/(.+)@(.+)\.(.+)/i",
                    'mobile_number'          => "required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users,mobile_number,$modelId",
                    // 'password'		     => 'required|min:8',
                    // 'confirm_password'    => 'required|same:password',
                ),
                array(
                    'first_name.required'   =>    trans('The first name field is required.'),
                )
            );
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            } else {
                $obj                         =  $model;
                $obj->first_name             =  Request::get('first_name');
                $first_name                 = Request::get('first_name');
                $last_name                     = Request::get('last_name');
                $fullname                     = $first_name . ' ' . $last_name;
                $obj->fullname                =  $fullname;
                $obj->employee_id             =  Request::get('employee_id');
                $obj->olms_id               =  Request::get('employee_id');
                $obj->region                 =  Request::get('region');
                $obj->circle                 =  Request::get('circle');
                $obj->lob                     =  Request::get('lob');
                //	$obj->username 		    =  Request::get( 'username' );
                $obj->last_name             =  Request::get('last_name');
                $obj->email                 =  Request::get('email');
                $obj->mobile_number         =  Request::get('mobile_number');
                $obj->gender                         =  Request::get('gender');
                $designation = Request::get('designation');
                if (strcasecmp($designation, 'Training Manager') === 0) {
                    $obj->user_role_id = MANAGER_ROLE_ID;
                } elseif (strcasecmp($designation, 'Trainer') === 0) {
                    $obj->user_role_id = TRAINER_ROLE_ID;
                } elseif (strcasecmp($designation, 'Trainee') === 0) {
                    $obj->user_role_id = TRAINEE_ROLE_ID;
                } else {
                    $obj->user_role_id = TRAINEE_ROLE_ID;
                }
                $obj->save();
                $userId                     =     $obj->id;
                if (!$userId) {
                    Session::flash('error', trans('Something went wrong.'));
                    return Redirect::back()->withInput();
                }
                Session::flash('success', trans($this->sectionNameSingular . ' has been updated successfully'));
                return Redirect::route($this->model . '.index');
            }
        }
    } // end update()

    /**
     * Function for update Currency  status
     *
     * @param $modelId as id of Currency
     * @param $modelStatus as status of Currency
     *
     * @return redirect page.
     */
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
    } // end view()

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



    public function ChangeDesignation()
    {

        $thisData                    =    Request::all();
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
    } // end delete()


    public function DeleteMultiple()
    {
        $formData = Request::all();

        //echo '<pre>'; print_r( $formData ); die;

        //	print_r($status); die;
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
            // return $testResults;
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
        // return $userAnswers;
        return view("admin.$this->model.test-report", compact('userData', 'testData', 'testQuestions', 'userAnswers', 'testResult'));
    }


    // public function traineeTestWiseReport(Request $request, $user_id, $test_id)
    // {
    //     $userData = User::where('id', $user_id)->first();

    //     $testData = TestParticipants::where('trainee_id', $user_id)->where('test_id', $test_id)->with([
    //         'test_details',
    //         'user_test_results'
    //     ])->first();

    //     $questionsAlreadyAssigned = UserAssignedTestQuestion::where('test_id', $test_id)
    //         ->where('trainee_id', $user_id)
    //         ->pluck('questions_id')
    //         ->toArray();

    //     $testQuestions = Question::whereIn('id', $questionsAlreadyAssigned)
    //         ->where('test_id', $test_id)
    //         ->with('questionAttributes')
    //         ->get();

    //     $userAnswers = Answer::where('test_id', $test_id)
    //         ->where('user_id', $user_id)
    //         ->pluck('valid_answer', 'question_id')
    //         ->toArray();

    //     // Now you can loop through $testQuestions to display the details
    //     foreach ($testQuestions as $question) {
    //         echo "Question: " . $question->question . "<br>";

    //         // Check if questionAttributes is not null before iterating
    //         if (!is_null($question->questionAttributes)) {
    //             // Displaying question options...
    //             foreach ($question->questionAttributes as $option) {
    //                 echo $option->option;
    //                 echo "<br>";
    //             }
    //         } else {
    //             echo "No options available for this question.<br>";
    //         }

    //         // Displaying user's answer
    //         echo "User's Answer: ";
    //         if (isset($userAnswers[$question->id])) {
    //             $userAnswerOptionId = $userAnswers[$question->id];

    //             // Check if the user's answer is correct
    //             $isCorrect = $question->questionAttributes->where('id', $userAnswerOptionId)->first()->is_correct ?? false;

    //             if ($isCorrect) {
    //                 echo 'Correct';
    //             } else {
    //                 echo 'Incorrect';
    //             }
    //         } else {
    //             echo 'Not answered';
    //         }

    //         // Displaying correct answer
    //         $correctAnswerOptions = $question->questionAttributes->filter(function ($option) {
    //             return $option->is_correct == 1;
    //         })->pluck('option')->toArray();

    //         echo "Correct Answer: " . implode(', ', $correctAnswerOptions);

    //         echo "<hr>";
    //     }

    //     return  View::make("admin.$this->model.test-report", compact('userData', 'testData'));
    // }
}// end BrandsController
