<?php

namespace App\Http\Controllers\admin;

use App\Exports\exportParticipants;
use App\Http\Controllers\BaseController;
use App\Imports\importParticipants;
use App\Model\Batch;
use App\Model\Course;
use App\Model\ManagerTrainings;
use App\Model\Question;
use App\Model\Test;
use App\Model\TrainerTrainings;
use App\Model\Training;
use App\Model\TrainingDocument;
use App\Model\TrainingParticipants;
use App\Model\TrainingTestParticipants;
use App\Model\TrainingType;
use App\Model\User;
use App\Notifications\AssignTrainingNotification;
use Auth;
use Carbon\Carbon;
use Config;
use File;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Notification;
use Redirect;
use Request;
use Session;
use Symfony\Component\HttpFoundation\Response;
use URL;
use Validator;

/**
 * TrainingController Controller
 *
 * Add your methods in the class below
 */
class TrainingController extends BaseController
{
    public $model = 'Training';

    public $sectionName = 'Training';

    public $sectionNameSingular = 'Training';

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
    public function index()
    {
        // Base query for fetching training records
        $DB = Training::query();

        if (Auth::user()->user_role_id == 4) {
            // Fetch trainings created by the authenticated user
            $created_by_self_trainings = Training::where('user_id', Auth::user()->id)
                ->pluck('id')
                ->toArray();

            // Fetch trainings assigned to the authenticated user
            $my_assign_training_ids = ManagerTrainings::where('user_id', Auth::user()->id)
                ->where('training_id', '!=', '')
                ->pluck('training_id')
                ->toArray();

            // Find trainers with user_role_id 2 and the same location as the authenticated user
            $userIdsWithRole2AndSameLocation = User::where('user_role_id', 2)
                ->where('center', Auth::user()->center)
                ->pluck('id')
                ->toArray();

            // Fetch training IDs for those trainers
            $trainingIdsFromRole2AndLocation = Training::whereIn('user_id', $userIdsWithRole2AndSameLocation)
                ->pluck('id')
                ->toArray();

            // Combine all training IDs
            $allTrainingIds = array_merge($created_by_self_trainings, $my_assign_training_ids, $trainingIdsFromRole2AndLocation);

            // Modify the $DB query to include the combined training IDs
            $DB->whereIn('trainings.id', $allTrainingIds);
        }

        // Existing search and filtering logic
        $searchVariable = [];
        $inputGet = Request::all();
        if (Request::all()) {
            $searchData = Request::all();
            unset($searchData['display'], $searchData['_token'], $searchData['order'], $searchData['sortBy'], $searchData['page']);

            foreach ($searchData as $fieldName => $fieldValue) {
                if ($fieldValue != '') {
                    if ($fieldName == 'is_active') {
                        $DB->where('trainings.is_active', $fieldValue);
                    }
                    if ($fieldName == 'title') {
                        $DB->where('trainings.title', 'like', '%' . $fieldValue . '%');
                    }
                    $searchVariable[$fieldName] = $fieldValue;
                }
            }
        }

        // Join and select logic for training results
        $DB->leftJoin('training_types', 'training_types.id', '=', 'trainings.type')
            ->leftJoin('users', 'users.id', '=', 'trainings.user_id')
            ->select('trainings.*', 'training_types.type as type', 'users.fullname as created_by');

        $sortBy = Request::get('sortBy', 'updated_at');
        $order = Request::get('order', 'DESC');
        $results = $DB->orderBy($sortBy, $order)->paginate(Config::get('Reading.records_per_page'));
        $complete_string = Request::query();
        unset($complete_string['sortBy'], $complete_string['order']);
        $query_string = http_build_query($complete_string);

        $results->appends(Request::all())->render();
        // return $results;
        session(['filteredResult' => $results]);

        $tr_managers = User::where('is_deleted', 0)->where('user_role_id', 4)->get(['id', 'fullname', 'olms_id']);
        $training_manager = $tr_managers->mapWithKeys(function ($manager) {
            return [$manager->id => " {$manager->olms_id} : {$manager->fullname}"];
        })->toArray();

        $tr_trainers = User::where('is_deleted', 0)->where('user_role_id', 2)->get(['id', 'fullname', 'olms_id']);
        $trainers = $tr_trainers->mapWithKeys(function ($trainer) {
            return [$trainer->id => " {$trainer->olms_id} : {$trainer->fullname}"];
        })->toArray();

        return View::make("admin.$this->model.index", compact('results', 'searchVariable', 'sortBy', 'order', 'query_string', 'training_manager', 'trainers'));
    }

    /**
     * Function for add new State
     *
     * @param null
     * @return view page.
     */
    public function add()
    {
        $test = Test::pluck('title', 'id')->toArray();
        $TrainingType = TrainingType::pluck('type', 'id')->toArray();
        $training_manager = User::where('is_deleted', 0)->where('user_role_id', 4)->pluck('fullname', 'id')->toArray();

        $trainers = User::where('is_deleted', 0)->where('user_role_id', 2)->pluck('fullname', 'id')->toArray();

        return View::make("admin.$this->model.add", compact('TrainingType', 'training_manager', 'test', 'trainers'));
    } // end add()

    /**
     * Function for save new Area
     *
     * @param null
     * @return redirect page.
     */
    public function save()
    {
        Request::replace($this->arrayStripTags(Request::all()));
        $thisData = Request::all();
        //  echo '<pre>'; print_r($thisData); die;

        $rules = [
            'title' => 'required',
            'type' => 'required',
            // 'minimum_marks'             => 'required',
            // 'number_of_attempts'             => 'required',
            // 'skip'             => 'required',
            // 'document'             => 'required',
            'start_date_time' => 'required',
            'end_date_time' => 'required',
            // 'thumbnail' => 'required',
        ];

        // if (Auth::user()->user_role_id == 4) {
        //     $rules['training_trainer'] = 'required';
        // } else {
        //     $rules['training_manager'] = 'required';
        // }

        $validator = Validator::make($thisData, $rules);
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)->withInput();
        } else {
            $obj = new Training;
            $obj->title = Request::get('title');
            $obj->type = Request::get('type');
            // $obj->minimum_marks           = Request::get('minimum_marks');
            $obj->user_id = Auth::user()->id;
            // $obj->number_of_attempts       = Request::get('number_of_attempts');
            // $obj->skip                   = Request::get('skip');
            // $obj->test_id             = Request::get('test_id');
            $obj->start_date_time = Request::get('start_date_time');
            $obj->end_date_time = Request::get('end_date_time');
            $obj->description = Request::get('description');

            // Calculate test status based on start and end times
            $currentDateTime = Carbon::now();
            $startDateTime = Carbon::parse($obj->start_date_time);
            $endDateTime = Carbon::parse($obj->end_date_time);

            if ($currentDateTime->between($startDateTime, $endDateTime)) {
                // Ongoing
                $obj->status = 0;
            } elseif ($startDateTime->isFuture()) {
                // Upcoming
                $obj->status = 1;
            } elseif ($endDateTime->isPast()) {
                // Expired/Completed
                $obj->status = 3;
            }
            if (Request::hasFile('thumbnail')) {
                $extension = Request::file('thumbnail')->getClientOriginalExtension();
                $fileName = time() . '-thumbnail.' . $extension;

                $folderName = strtoupper(date('M') . date('Y')) . '/';
                $folderPath = config('TRAINING_DOCUMENT_ROOT_PATH') . $folderName;
                if (!File::exists($folderPath)) {
                    File::makeDirectory($folderPath, $mode = 0777, true);
                }
                if (Request::file('thumbnail')->move($folderPath, $fileName)) {
                    $obj->thumbnail = $folderName . $fileName;
                }
            }
            $obj->save();
            $training_id = $obj->id;

            if ($training_id) {
                if (isset($thisData['training_manager']) && !empty($thisData['training_manager'])) {
                    //ContestStocks::where('contest_id',$contest_id)->delete();
                    foreach ($thisData['training_manager'] as $user_id) {
                        //    print_r($user_id); die;
                        $object = new ManagerTrainings;
                        $object->training_id = $training_id;
                        $object->user_id = $user_id;
                        $object->save();
                    }
                }
            }
            if ($training_id) {
                if (isset($thisData['training_trainer']) && !empty($thisData['training_trainer'])) {
                    ManagerTrainings::where('training_id', $training_id)->delete();

                    foreach ($thisData['training_trainer'] as $user_id) {
                        //    print_r($user_id); die;
                        $object = new TrainerTrainings;
                        $object->training_id = $training_id;
                        $object->user_id = $user_id;
                        $object->save();
                    }
                }
            }
            // if($training_id){
            //     if(isset($thisData['data']) && !empty($thisData['data'])) {
            //         foreach($thisData['data'] as $training_documents) {

            //             $obj                     = new TrainingDocument;
            //             $obj->training_id        = $training_id;
            //             if(isset($training_documents['title']) && !empty($training_documents['title'])){
            //                 $title    =    $training_documents["title"];
            //                 $obj->title        =    $title;
            //             }
            //             if(isset($training_documents['document'])  && !empty($training_documents['document'])){

            //                 $extension     =     $training_documents['document']->getClientOriginalExtension();
            //                 $fileName    =    time().'-document.'.$extension;

            //                 $folderName         =     strtoupper(date('M'). date('Y'))."/";
            //                 $folderPath            =    config('TRAINING_DOCUMENT_ROOT_PATH') .$folderName;
            //                 if(!File::exists($folderPath)) {
            //                     File::makeDirectory($folderPath, $mode = 0777,true);
            //                 }
            //                 if(!empty($extension)){
            //                     $obj->document_type    =    $extension;
            //                 }
            //                 if($training_documents['document']->move($folderPath, $fileName)){
            //                     $obj->document    =    $folderName.$fileName;
            //                 }

            //                 $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'ico'];
            //                 $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'mkv', 'flv','mpeg','mpg'];
            //                 $fileExtensions = ['doc', 'pdf', 'txt', 'xls', 'xlsx', 'ppt', 'csv', 'odt'];
            //                 if (in_array($extension, $imageExtensions)) {
            //                         $obj->type    =    'image';
            //                 } elseif (in_array($extension, $videoExtensions)) {
            //                     $obj->type    =    'video';
            //                 } elseif (in_array($extension, $fileExtensions)) {
            //                     $obj->type    =    'doc';
            //                 }

            //             }
            //             $obj->save();
            //         }
            //     }
            // }
            if (!$obj->save()) {

                Session::flash('error', trans('Something went wrong.'));

                return Redirect::route($this->model . '.index');
            } else {
                Session::flash('success', trans($this->sectionNameSingular . ' has been added successfully'));

                return Redirect::route($this->model . '.index');
            }
        }
    } //end save()

    /**
     * Function for update status
     *
     * @param  $modelId  as id of area
     * @param  $status  as status of area
     * @return redirect page.
     */
    public function changeStatus($modelId = 0, $status = 0)
    {
        if ($status == 0) {
            $statusMessage = trans($this->sectionNameSingular . ' has been deactivated successfully');
        } else {
            $statusMessage = trans($this->sectionNameSingular . ' has been activated successfully');
        }

        Training::where('id', $modelId)->update(['is_active' => $status]);
        Session::flash('flash_notice', $statusMessage);

        return Redirect::back();
    } // end changeStatus()

    /**
     * Function for display page for edit area
     *
     * @param  $modelId  id  of area
     * @return view page.
     */
    public function edit($modelId = 0)
    {
        $model = Training::find($modelId);
        if (empty($model)) {
            return Redirect::route($this->model . '.index');
        }

        $TrainingType = TrainingType::pluck('type', 'id')->toArray();
        $trainees = User::where('is_deleted', 0)->pluck('fullname', 'id')->toArray();
        $selected_trainees = TrainingParticipants::where('training_id', $modelId)->pluck('trainee_id');
        $training_manager = User::where('is_deleted', 0)->where('user_role_id', 4)->pluck('fullname', 'id')->toArray();
        $selected_training_manager = ManagerTrainings::where('training_id', $modelId)->pluck('user_id');

        $trainers = User::where('is_deleted', 0)->where('user_role_id', 2)->pluck('fullname', 'id')->toArray();
        $selected_training_trainers = TrainerTrainings::where('training_id', $modelId)->pluck('user_id');

        return View::make("admin.$this->model.edit", compact('model', 'TrainingType', 'trainees', 'selected_trainees', 'training_manager', 'selected_training_manager', 'trainers', 'selected_training_trainers'));
    } // end edit()

    /**
     * Function for update area
     *
     * @param  $modelId  as id of area
     * @return redirect page.
     */
    public function update($modelId)
    {
        $model = Training::findorFail($modelId);
        if (empty($model)) {
            return Redirect::back();
        }

        Request::replace($this->arrayStripTags(Request::all()));
        $thisData = Request::all();
        //echo '<pre>'; print_r($thisData); die;

        $rules = [
            'title' => 'required',
            'type' => 'required',
            // 'minimum_marks'             => 'required',
            // 'number_of_attempts'             => 'required',
            // 'skip'             => 'required',
            // 'document'             => 'required',
            'start_date_time' => 'required',
            'end_date_time' => 'required',
            //'thumbnail'             => 'required',
        ];

        // if (Auth::user()->user_role_id == 4) {
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
            $obj->type = Request::get('type');
            //$obj->user_id                   = Auth::user()->id;
            $obj->title = Request::get('title');
            // $obj->minimum_marks           = Request::get('minimum_marks');
            // $obj->number_of_attempts       = Request::get('number_of_attempts');
            // $obj->skip                   = Request::get('skip');
            $obj->start_date_time = Request::get('start_date_time');
            $obj->end_date_time = Request::get('end_date_time');
            $obj->description = Request::get('description');
            // Calculate test status based on start and end times
            $currentDateTime = Carbon::now();
            $startDateTime = Carbon::parse($obj->start_date_time);
            $endDateTime = Carbon::parse($obj->end_date_time);

            if ($currentDateTime->between($startDateTime, $endDateTime)) {
                // Ongoing
                $obj->status = 0;
            } elseif ($startDateTime->isFuture()) {
                // Upcoming
                $obj->status = 1;
            } elseif ($endDateTime->isPast()) {
                // Expired/Completed
                $obj->status = 3;
            }
            // $obj->test_id         = Request::get('test_id');
            if (Request::hasFile('thumbnail')) {
                $extension = Request::file('thumbnail')->getClientOriginalExtension();
                $fileName = time() . '-thumbnail.' . $extension;

                $folderName = strtoupper(date('M') . date('Y')) . '/';
                $folderPath = config('TRAINING_DOCUMENT_ROOT_PATH') . $folderName;
                if (!File::exists($folderPath)) {
                    File::makeDirectory($folderPath, $mode = 0777, true);
                }
                if (Request::file('thumbnail')->move($folderPath, $fileName)) {
                    $obj->thumbnail = $folderName . $fileName;
                }
            }
            $obj->save();
            $training_id = $obj->id;

            if ($training_id) {
                if (isset($thisData['training_manager']) && !empty($thisData['training_manager'])) {
                    ManagerTrainings::where('training_id', $training_id)->delete();
                    foreach ($thisData['training_manager'] as $user_id) {
                        //    print_r($user_id); die;
                        $object = new ManagerTrainings;
                        $object->training_id = $training_id;
                        $object->user_id = $user_id;
                        $object->save();
                    }
                }
            }
            if ($training_id) {
                if (isset($thisData['training_trainer']) && !empty($thisData['training_trainer'])) {
                    ManagerTrainings::where('training_id', $training_id)->delete();

                    foreach ($thisData['training_trainer'] as $user_id) {
                        //    print_r($user_id); die;
                        $object = new TrainerTrainings;
                        $object->training_id = $training_id;
                        $object->user_id = $user_id;
                        $object->save();
                    }
                }
            }
            // if($training_id){
            //     if(isset($thisData['data']) && !empty($thisData['data'])) {
            //         TrainingDocument::where('training_id',$training_id)->delete();
            //         foreach($thisData['data'] as $training_documents) {

            //             $obj                     = new TrainingDocument;
            //             $obj->training_id        = $training_id;
            //             if(isset($training_documents['title']) && !empty($training_documents['title'])){
            //                 $title    =    $training_documents["title"];
            //                 $obj->title        =    $title;
            //             }
            //             if(isset($training_documents['document'])  && !empty($training_documents['document'])){

            //                 $extension     =     $training_documents['document']->getClientOriginalExtension();
            //                 $fileName    =    time().'-document.'.$extension;

            //                 $folderName         =     strtoupper(date('M'). date('Y'))."/";
            //                 $folderPath            =    config('TRAINING_DOCUMENT_ROOT_PATH') .$folderName;
            //                 if(!File::exists($folderPath)) {
            //                     File::makeDirectory($folderPath, $mode = 0777,true);
            //                 }
            //                 if(!empty($extension)){
            //                     $obj->document_type    =    $extension;
            //                 }
            //                 if($training_documents['document']->move($folderPath, $fileName)){
            //                     $obj->document    =    $folderName.$fileName;
            //                 }

            //                 $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'ico'];
            //                 $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'mkv', 'flv','mpeg','mpg'];
            //                 $fileExtensions = ['doc', 'pdf', 'txt', 'xls', 'xlsx', 'ppt', 'csv', 'odt'];
            //                 if (in_array($extension, $imageExtensions)) {
            //                         $obj->type    =    'image';
            //                 } elseif (in_array($extension, $videoExtensions)) {
            //                     $obj->type    =    'video';
            //                 } elseif (in_array($extension, $fileExtensions)) {
            //                     $obj->type    =    'doc';
            //                 }else{
            //                     $obj->type    =    '';
            //                 }
            //             }
            //             $obj->save();
            //         }
            //     }
            // }
            if (!$obj->save()) {

                Session::flash('error', trans('Something went wrong.'));

                return Redirect::route($this->model . '.index');
            } else {
                Session::flash('success', trans($this->sectionNameSingular . ' has been Updated successfully'));

                return Redirect::route($this->model . '.index');
            }
        }
    } // end update()

    /**
     * Function for mark a couse as deleted
     *
     * @param  $userId  as id of couse
     * @return redirect page.
     */
    public function delete($id = 0)
    {
        $model = Training::find($id);
        if (empty($model)) {
            return Redirect::back();
        }
        if ($id) {
            Training::where('id', $id)->delete();
            Session::flash('flash_notice', trans($this->sectionNameSingular . ' has been removed successfully'));
        }

        return Redirect::back();
    } // end delete()

    public function addMoreDocument()
    {
        $offset = $_POST['offset'];

        return View::make("admin.$this->model.addMoreDetails", compact('offset', 'offset'));
    } // end updateProjectStatus()

    public function deleteMoreDocument()
    {
        $id = $_POST['id'];
        $output = 0;
        if ($id) {
            $projectDetailModel = TrainingDocument::where('id', '=', $id)->delete();
            $output = 1;
            //Session::flash('flash_notice',trans("Detail removed successfully"));
        }
        echo $output;
        exit;
    } // end updateProjectStatus()

    public function view($modelId = 0)
    {
        $model = Training::find($modelId);
        // return $model;

        if (empty($model)) {
            return Redirect::route($this->model . '.index');
        }

        $model = $model->leftJoin('training_types', 'training_types.id', '=', 'trainings.type')->leftJoin('users', 'users.id', '=', 'trainings.user_id')->select('trainings.*', 'training_types.type as type', 'users.fullname as created_by')->first();

        $createdBy = $model->fullname;
        // echo '<pre>'; print_r($createdBy); die;

        $manager_ids = ManagerTrainings::where('training_id', $modelId)->pluck('user_id')->toArray();
        $manager_details = User::whereIn('id', $manager_ids)->get();

        $trainer_ids = TrainerTrainings::where('training_id', $modelId)->pluck('user_id')->toArray();
        $trainer_details = User::whereIn('id', $trainer_ids)->get();

        $trainee_ids = TrainingParticipants::where('training_id', $modelId)->pluck('trainee_id')->toArray();
        $trainee_details = User::whereIn('id', $trainee_ids)->get();
        if (Auth::user()->user_role_id == 4) {
            $trainee_details = User::whereIn('id', $trainee_ids)->where('center', Auth::user()->center)->get();
        }
        $courses = Course::where('training_id', $modelId)->get();

        return View::make("admin.$this->model.view", compact('model', 'trainee_details', 'trainer_details', 'manager_details', 'courses'));
    } // end edit()

    public function exportTraining(Request $request)
    {
        $filteredResult = Session::get('filteredResult');

        $filteredResult = $filteredResult->map(function ($item) {
            $selectedFields = $item->only(['title', 'created_by', 'type', 'start_date_time', 'end_date_time', 'status', 'description']);
            $selectedFields['description'] = strip_tags($selectedFields['description']);

            if ($selectedFields['status'] == '0') {
                $selectedFields['status'] = 'Ongoing';
            } elseif ($selectedFields['status'] == '1') {
                $selectedFields['status'] = 'Upcoming';
            } else {
                $selectedFields['status'] = 'Completed';
            }

            return $selectedFields;
        });

        $export = new exportParticipants($filteredResult);

        return Excel::download($export, 'Training.xlsx');
    }

    public function importTrainingParticipants($training_id = 0)
    {
        // Get the training details
        $trainingDetails = Training::where('id', $training_id)->first();

        if (!$trainingDetails) {
            Session::flash('error', 'Training not found.');
            return Redirect::back();
        }

        // Get current date and time
        $now = Carbon::now();

        // Check if the current time is outside the training's start and end time
        $startTime = Carbon::parse($trainingDetails->start_date_time);
        $endTime = Carbon::parse($trainingDetails->end_date_time);

        if ($now->gt($endTime)) {
            Session::flash('error', 'Training time is over. Please change the training time if you want to assign participants.');
            return Redirect::back();
        }

        if (Auth::user()->user_role_id == 1) {
            $batch = Batch::pluck('name', 'id')->toArray();
            $trainees = User::where('is_deleted', 0)
                ->where('user_role_id', '!=', 1)
                ->where('is_developer', '!=', 1)
                ->get(['id', 'fullname', 'olms_id']);
            $alreadyParticipants = TrainingParticipants::where('training_id', $training_id)->pluck('trainee_id')->toArray();
        } else {
            $batch = Batch::pluck('name', 'id')->toArray();
            $trainees = User::where('is_deleted', 0)
                ->where('user_role_id', '!=', 1)
                ->where('is_developer', '!=', 1)
                ->where('center', Auth::user()->center)
                ->get(['id', 'fullname', 'olms_id']);
            $alreadyParticipants = TrainingParticipants::where('training_id', $training_id)->pluck('trainee_id')->toArray();
        }

        $existCourse = Course::where('training_id', $training_id)->first();

        if ($existCourse) {
            $test_id = $existCourse->test_id; // Assuming the course has a test_id field
            if ($test_id) {
                // Check if the test has valid questions and attributes
                $questionsWithoutAttributes = Question::where('test_id', $test_id)
                    ->whereIn('question_type', ['MCQ', 'SCQ', 'T/F']) // Only check for MCQ, SCQ, and T/F
                    ->whereDoesntHave('questionAttributes') // Check for missing QuestionAttribute
                    ->count();
                if ($questionsWithoutAttributes > 0) {
                    Session::flash('error', "Plase check the question options.");
                    return Redirect::back();
                }
            }
            // Prepare an array for the select input where key is the ID and value is a formatted string
            $traineeOptions = $trainees->mapWithKeys(function ($trainee) {
                return [$trainee->id => " {$trainee->olms_id} : {$trainee->fullname}"];
            })->toArray();

            return View::make("admin.$this->model.uploadTrainingParticipants", compact('training_id', 'batch', 'traineeOptions', 'alreadyParticipants'));
        } else {
            Session::flash('error', 'Please add a course to this training before uploading participants.');
            return Redirect::back();
        }
    }

    public function importTrainingUsers($training_id)
    {
        $selectedTrainees = Request::get('trainees', []);
        $training = Training::find($training_id);

        $courses = Course::where('training_id', $training_id)->get();

        $currentParticipants = TrainingParticipants::where('training_id', $training_id)
            ->pluck('trainee_id')
            ->toArray();

        // Determine participants to remove (those currently in DB but not selected)
        $participantsToRemove = array_diff($currentParticipants, $selectedTrainees);

        if (!empty($participantsToRemove)) {
            TrainingParticipants::where('training_id', $training_id)
                ->whereIn('trainee_id', $participantsToRemove)
                ->delete();

            TrainingTestParticipants::where('training_id', $training_id)
                ->whereIn('trainee_id', $participantsToRemove)
                ->delete();
        }

        // Determine new participants to add (those selected but not currently in DB)
        $participantsToAdd = array_diff($selectedTrainees, $currentParticipants);

        // Add the new participants
        foreach ($participantsToAdd as $trainee_id) {
            $object = new TrainingParticipants();
            $object->training_id = $training_id;
            $object->trainee_id = $trainee_id;
            $object->created_by = Auth::user()->id;
            $object->save();

            $user = User::find($trainee_id);
            $actionUrl = URL::route('userTraining.index');
            $details = [
                'greeting' => 'New Training Available',
                'message' => 'New Training Available',
                'body' => 'You have been assigned a ' . $training->title . ' training.',
                'actionText' => 'View Training',
                'actionURL' => $actionUrl,
                'training_id' => $training_id,
            ];
            Notification::send($user, new AssignTrainingNotification($details));

            foreach ($courses as $course) {
                if ($course->test_id) { // Check if the course has a test_id
                    $testParticipantExists = TrainingTestParticipants::where('training_id', $training_id)
                        ->where('course_id', $course->id)
                        ->where('test_id', $course->test_id)
                        ->where('trainee_id', $trainee_id)
                        ->exists();

                    if (!$testParticipantExists) {
                        $testParticipant = new TrainingTestParticipants();
                        $testParticipant->training_id = $training_id;
                        $testParticipant->course_id = $course->id;
                        $testParticipant->test_id = $course->test_id;
                        $testParticipant->trainee_id = $trainee_id;
                        $testParticipant->is_active = 1;
                        $testParticipant->status = 0;
                        $testParticipant->user_attempts = null;
                        $testParticipant->created_by = Auth::user()->id;
                        $testParticipant->is_deleted = 0;
                        $testParticipant->save();
                    }
                }
            }
        }

        Session::flash('success', 'Training participants updated successfully.');
        return Redirect::back();
    }

    public function importTrainingsBatch($training_id)
    {
        $batch_id = Request::get('batch_id');

        $training = Training::where('id', $training_id)->first();
        if (!$training) {
            Session::flash('error', 'Training not found');
            return Redirect::back();
        }

        $users = User::where('batch_id', $batch_id)->get();
        if ($users->isEmpty()) {
            Session::flash('error', 'No users found for the provided batch');
            return Redirect::back();
        }

        // Retrieve all courses associated with the training
        $courses = Course::where('training_id', $training_id)->get();

        foreach ($users as $user) {
            $participantAlreadyExist = TrainingParticipants::where('training_id', $training_id)
                ->where('trainee_id', $user->id)
                ->first();

            if (!$participantAlreadyExist) {
                $participant = new TrainingParticipants([
                    'training_id' => $training_id,
                    'trainee_id' => $user->id,
                    'created_by' => Auth::user()->id,
                ]);

                if ($participant->save()) {
                    // Send notification to the user
                    $actionUrl = URL::route('userTraining.index');
                    $details = [
                        'greeting' => 'New Training Available',
                        'message' => 'New Training Available',
                        'body' => 'You have been assigned a ' . $training->title . ' training.',
                        'actionText' => 'View Training',
                        'actionURL' => $actionUrl,
                        'training_id' => $training_id,
                    ];
                    Notification::send($user, new AssignTrainingNotification($details));
                } else {
                    Session::flash('error', 'Failed to save participant for user ID: ' . $user->id);
                    return Redirect::back();
                }
            }

            // Check each course associated with the training for tests
            foreach ($courses as $course) {
                if ($course->test_id) { // Check if the course has a test_id
                    $testParticipantExists = TrainingTestParticipants::where('training_id', $training_id)
                        ->where('course_id', $course->id)
                        ->where('test_id', $course->test_id)
                        ->where('trainee_id', $user->id)
                        ->exists();

                    if (!$testParticipantExists) {
                        // Add trainee to TrainingTestParticipants
                        $testParticipant = new TrainingTestParticipants();
                        $testParticipant->training_id = $training_id;
                        $testParticipant->course_id = $course->id;
                        $testParticipant->test_id = $course->test_id;
                        $testParticipant->trainee_id = $user->id;
                        $testParticipant->is_active = 1;
                        $testParticipant->status = 0;
                        $testParticipant->user_attempts = null;
                        $testParticipant->created_by = Auth::user()->id;
                        $testParticipant->is_deleted = 0;
                        $testParticipant->save();
                    }
                }
            }
        }

        Session::flash('success', 'Users from the batch have been added to the training participants successfully.');
        return Redirect::back();
    }
    public function importTraining($training_id = 0)
    {
        $thisData = Request::all();
        $validator = Validator::make(
            $thisData,
            [
                'file' => 'required|file|mimes:xlsx,xls',
            ]
        );

        if ($validator->fails()) {
            Session::flash('error', trans("Only Excel files in .xlsx or .xls format are accepted. Please use the sample file's heading to organize your data and try uploading again."));
            return Redirect::back();

        }

        $import = new importParticipants($training_id);
        Excel::import($import, request()->file('file'));
        $errors = $import->getErrors();
        if (count($errors) > 0) {
            return view('errors.importTraineeError')->with('errors', $errors);
        }

        return redirect()->back()->with('success', 'Training Participants Added Successfully!');
    }

    public function AssignManager()
    {

        $thisData = Request::all();
        $training_id = $thisData['training_id'];

        if ($training_id) {
            if (isset($thisData['training_manager']) && !empty($thisData['training_manager'])) {
                //ContestStocks::where('contest_id',$contest_id)->delete();
                foreach ($thisData['training_manager'] as $user_id) {
                    //    print_r($user_id); die;
                    $object = new ManagerTrainings;
                    $object->training_id = $training_id;
                    $object->user_id = $user_id;
                    $object->save();
                }
            }
        }

        Session::flash('flash_notice', trans(' Manager has been Assign successfully'));

        return Redirect::back();
    } // end delete()

    public function AssignTrainer()
    {

        $thisData = Request::all();
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

        Session::flash('flash_notice', trans(' Trainer has been Assign successfully'));

        return Redirect::back();
    } // end delete()

    // Create training AI Functions
    public function addAi()
    {
        return view('admin.training.add_ai');
    }

    public function saveAi(HttpRequest $request)
    {
        $text = $request->speechText;
        // dd($text);
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoiNzE4MGU2ZWItZjkyNS00NmVlLWJhNTUtOGIzYjIzMGEyZWRhIiwidHlwZSI6ImFwaV90b2tlbiJ9.HxWf4q93fpADe7AlcDTCBt_nJ6HlANsoFZeZz_KZ6RE', // Replace with your API key if needed
        ])->post('https://api.edenai.run/v2/text/generation', [
            'response_as_dict' => true,
            'attributes_as_list' => false,
            'show_original_response' => false,
            'temperature' => 0,
            'max_tokens' => 2048,
            'text' => $text,
            'providers' => 'google,openai',
        ]);
        if ($response->successful()) {
            $responseData = $response->json();
            $html = $responseData['google']['generated_text'];

            // return response()->json(['redirect' => route('admin.show.ai.content', ['jsonData' => $responseData])]);
            return View::make('admin.training.show_ai_content', ['jsonData' => $responseData]);
        } else {
            $errorCode = $response->status();

            return response()->json(['error' => 'API Error'], $errorCode);
        }
    }
} // end TrainingController
