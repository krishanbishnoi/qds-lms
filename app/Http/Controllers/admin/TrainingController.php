<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Models\Training;
use App\Models\User;
use App\Models\TrainingParticipants;
use App\Imports\importParticipants;
use App\Exports\exportParticipants;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\TrainingDocument;
use App\Models\TrainingType;
use App\Models\TrainingCategory;
use App\Models\ManagerTrainings;
use App\Models\TrainerTrainings;
use App\Models\Course;
use App\Models\Test;
use App\Models\StateDescription;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;
use App\ApiService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use \PDF;

// use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Mail, Redirect, Session, URL, Validator, Request;

/**
 * TrainingController Controller
 *
 * Add your methods in the class below
 *
 */
class TrainingController extends BaseController
{

    public $model        =    'Training';
    public $sectionName    =    'Training';
    public $sectionNameSingular    =    'Training';

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

    public function index(Request $request)
    {
        try {
            $query = Training::query()
                ->leftJoin('training_types', 'training_types.id', '=', 'trainings.type')
                ->leftJoin('users', 'users.id', '=', 'trainings.user_id')
                ->join('training_categories', 'training_categories.id', '=', 'trainings.category_id')
                ->select('trainings.*', 'training_types.type as type', 'users.first_name as created_by', 'training_categories.name as category_name');

            // Apply manager-specific filters
            if (Auth::user()->user_role_id == MANAGER_ROLE_ID) {
                $createdBySelfIds = $query->clone()->where('user_id', Auth::id())->pluck('id');
                $assignedTrainingIds = ManagerTrainings::where('user_id', Auth::id())
                    ->whereNotNull('training_id')
                    ->pluck('training_id');

                $query->whereIn('trainings.id', $createdBySelfIds)
                    ->orWhereIn('trainings.id', $assignedTrainingIds);
            }

            // Handle search filters
            $searchParams = $request->except(['display', '_token', 'order', 'sortBy', 'page']);
            $searchVariable = [];

            foreach ($searchParams as $field => $value) {
                if ($value !== "") {
                    if ($field === "is_active") {
                        $query->where("trainings.is_active", $value);
                    } elseif ($field === "title") {
                        $query->where("trainings.title", 'like', '%' . $value . '%');
                    }
                    $searchVariable[$field] = $value;
                }
            }

            // Sorting
            $sortBy = $request->get('sortBy', 'updated_at');
            $order = $request->get('order', 'DESC');
            $results = $query->orderBy($sortBy, $order)
                ->paginate(config("Reading.records_per_page"));

            // Build query string for pagination
            $query_string = http_build_query($request->except(['sortBy', 'order']));
            // Store filtered results in session if needed
            session(['filteredResult' => $results]);

            // Get managers and trainers for dropdowns
            $training_manager = User::where("is_deleted", 0)
                ->where("user_role_id", MANAGER_ROLE_ID)
                ->pluck('fullname', 'id');

            $trainers = User::where("is_deleted", 0)
                ->where("user_role_id", TRAINER_ROLE_ID)
                ->pluck('fullname', 'id');

            return view("admin.Training.index", compact(
                'results',
                'searchVariable',
                'sortBy',
                'order',
                'query_string',
                'training_manager',
                'trainers'
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'somthing went wrong');;
        }
    }

    public function add()
    {
        try {
            $trainingCategory = TrainingCategory::pluck('name', 'id')->toArray();
            $test = Test::pluck('title', 'id')->toArray();
            $TrainingType = TrainingType::pluck('type', 'id')->toArray();
            $training_manager = User::where('is_deleted', 0)
                ->where('user_role_id', MANAGER_ROLE_ID)
                ->pluck('first_name', 'id')
                ->toArray();

            $trainers = User::where('is_deleted', 0)
                ->where('user_role_id', TRAINER_ROLE_ID)
                ->pluck('first_name', 'id')
                ->toArray();

            return view('admin.Training.add', compact('trainingCategory', 'TrainingType', 'training_manager', 'test', 'trainers'));
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'somthing went wrong');;
        }
    }


    public function save(Request $request)
    {
        try {

            // Sanitize input by stripping tags
            $input = $this->arrayStripTags($request->all());

            // Validation rules
            $rules = [
                'category_id'      => 'required',
                'title'            => 'required',
                'type'             => 'required',
                // 'minimum_marks'   => 'required',
                // 'number_of_attempts' => 'required',
                // 'skip'            => 'required',
                // 'document'        => 'required',
                'start_date_time'  => 'required',
                'end_date_time'    => 'required',
                // 'thumbnail'        => 'required',
            ];

            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Prepare data array for updateOrCreate
            $trainingData = [
                'category_id'    => $input['category_id'],
                'title'          => $input['title'],
                'type'           => $input['type'],
                // 'minimum_marks' => $input['minimum_marks'] ?? null,
                // 'number_of_attempts' => $input['number_of_attempts'] ?? null,
                // 'skip'          => $input['skip'] ?? null,
                // 'test_id'       => $input['test_id'] ?? null,
                'user_id'        => Auth::user()->id,
                'start_date_time' => $input['start_date_time'],
                'end_date_time'  => $input['end_date_time'],
                'description'    => $input['description'] ?? null,
            ];

            // Check if updating or creating new training
            // Assuming $input has 'id' if updating
            $trainingId = $input['id'] ?? null;

            if ($request->hasFile('thumbnail')) {
                $extension = $request->file('thumbnail')->getClientOriginalExtension();
                $fileName = time() . '-thumbnail.' . $extension;
                $folderName = strtoupper(date('M') . date('Y')) . "/";
                $folderPath = TRAINING_DOCUMENT_ROOT_PATH . $folderName;

                if (!File::exists($folderPath)) {
                    File::makeDirectory($folderPath, 0777, true);
                }

                // If updating, delete old thumbnail
                if ($trainingId) {
                    $oldTraining = Training::find($trainingId);
                    if ($oldTraining && $oldTraining->thumbnail && File::exists(TRAINING_DOCUMENT_ROOT_PATH . $oldTraining->thumbnail)) {
                        File::delete(TRAINING_DOCUMENT_ROOT_PATH . $oldTraining->thumbnail);
                    }
                }

                if ($request->file('thumbnail')->move($folderPath, $fileName)) {
                    $trainingData['thumbnail'] = $folderName . $fileName;
                }
            } else {
                // Keep old thumbnail if exists and no new image uploaded
                if ($trainingId) {
                    $oldTraining = Training::find($trainingId);
                    if ($oldTraining) {
                        $trainingData['thumbnail'] = $oldTraining->thumbnail;
                    }
                }
            }

            // Use updateOrCreate for saving/updating
            $training = Training::updateOrCreate(
                ['id' => $trainingId],
                $trainingData
            );

            $training_id = $training->id;

            if ($training_id) {
                if (isset($input['training_manager']) && !empty($input['training_manager'])) {
                    // Delete old relations if updating (optional)
                    ManagerTrainings::where('training_id', $training_id)->delete();

                    foreach ($input['training_manager'] as $user_id) {
                        $object = new ManagerTrainings;
                        $object->training_id = $training_id;
                        $object->user_id = $user_id;
                        $object->save();
                    }
                }

                if (isset($input['training_trainer']) && !empty($input['training_trainer'])) {
                    TrainerTrainings::where('training_id', $training_id)->delete();

                    foreach ($input['training_trainer'] as $user_id) {
                        $object = new TrainerTrainings;
                        $object->training_id = $training_id;
                        $object->user_id = $user_id;
                        $object->save();
                    }
                }
            }

            /*
    if($training_id){
        if(isset($input['data']) && !empty($input['data'])) {
            foreach($input['data'] as $training_documents) {

                $obj = new TrainingDocument;
                $obj->training_id = $training_id;

                if(isset($training_documents['title']) && !empty($training_documents['title'])){
                    $title = $training_documents["title"];
                    $obj->title = $title;
                }

                if(isset($training_documents['document']) && !empty($training_documents['document'])){

                    $extension = $training_documents['document']->getClientOriginalExtension();
                    $fileName = time() . '-document.' . $extension;

                    $folderName = strtoupper(date('M') . date('Y')) . "/";
                    $folderPath = TRAINING_DOCUMENT_ROOT_PATH . $folderName;

                    if(!File::exists($folderPath)) {
                        File::makeDirectory($folderPath, 0777, true);
                    }

                    if(!empty($extension)){
                        $obj->document_type = $extension;
                    }

                    if($training_documents['document']->move($folderPath, $fileName)){
                        $obj->document = $folderName . $fileName;
                    }

                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'ico'];
                    $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'mkv', 'flv','mpeg','mpg'];
                    $fileExtensions = ['doc', 'pdf', 'txt', 'xls', 'xlsx', 'ppt', 'csv', 'odt'];

                    if (in_array($extension, $imageExtensions)) {
                        $obj->type = 'image';
                    } elseif (in_array($extension, $videoExtensions)) {
                        $obj->type = 'video';
                    } elseif (in_array($extension, $fileExtensions)) {
                        $obj->type = 'doc';
                    }
                }
                $obj->save();
            }
        }
    }
    */

            if (!$training->save()) {
                Session::flash('error', trans("Something went wrong."));
                return redirect()->route('Training.index');
            } else {
                Session::flash('success', trans($this->sectionNameSingular . " has been added successfully"));
                return redirect()->route('Training.index');
            }
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'somthing went wrong');;
        }
    }


    public function changeStatus($modelId = 0, $status = 0)
    {
        try {

            if ($status == 0) {
                $statusMessage    =    trans($this->sectionNameSingular . " has been deactivated successfully");
            } else {
                $statusMessage    =    trans($this->sectionNameSingular . " has been activated successfully");
            }

            Training::where('id', $modelId)->update(array('is_active' => $status));
            Session::flash('flash_notice', $statusMessage);
            return Redirect::back();
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'somthing went wrong');;
        }
    }

    public function edit($modelId = 0)
    {
        try {

            $model = Training::find($modelId);
            if (empty($model)) {
                return Redirect::route('Training' . ".index");
            }

            $trainingCategory = TrainingCategory::pluck('name', 'id')->toArray();
            $TrainingType = TrainingType::pluck('type', 'id')->toArray();
            $trainees = User::where("is_deleted", 0)->where("user_role_id", TRAINEE_ROLE_ID)->pluck('fullname', 'id')->toArray();
            $selected_trainees = TrainingParticipants::where('training_id', $modelId)->pluck('trainee_id');
            $training_manager = User::where("is_deleted", 0)->where("user_role_id", MANAGER_ROLE_ID)->pluck('fullname', 'id')->toArray();
            $selected_training_manager = ManagerTrainings::where('training_id', $modelId)->pluck('user_id');

            $trainers = User::where("is_deleted", 0)->where("user_role_id", TRAINER_ROLE_ID)->pluck('fullname', 'id')->toArray();
            $selected_training_trainers = TrainerTrainings::where('training_id', $modelId)->pluck('user_id');

            return  View::make("admin.Training.add", compact('model', 'trainingCategory', 'TrainingType', 'trainees', 'selected_trainees', 'training_manager', 'selected_training_manager', 'trainers', 'selected_training_trainers'));
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'somthing went wrong');;
        }
    }

    public function delete($id = 0)
    {
        try {

            $model    =    Training::find($id);
            if (empty($model)) {
                return Redirect::back();
            }
            if ($id) {
                Training::where('id', $id)->delete();
                Session::flash('flash_notice', trans($this->sectionNameSingular . " has been removed successfully"));
            }
            return Redirect::back();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'somthing went wrong');;
        }
    }

    public function addMoreDocument()
    {
        $offset = $_POST['offset'];
        return  View::make("admin.Training.addMoreDetails", compact('offset', 'offset'));
    }

    public function deleteMoreDocument()
    {
        try {

            $id = $_POST['id'];
            $output = 0;
            if ($id) {
                $projectDetailModel =  TrainingDocument::where('id', '=', $id)->delete();
                $output = 1;
                //Session::flash('flash_notice',trans("Detail removed successfully"));
            }
            echo $output;
            die;
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'somthing went wrong');;
        }
    }

    public function view($modelId = 0)
    {
        try {
            $model = Training::find($modelId);
            if (empty($model)) {
                return Redirect::route(Training . ".index");
            }
            $model = $model->leftJoin('training_types', 'training_types.id', '=', 'trainings.type')->leftJoin('users', 'users.id', '=', 'trainings.user_id')->select('trainings.*', 'training_types.type as type', 'users.first_name as created_by')->first();
            $createdBy = $model->first_name;
            $manager_ids         = ManagerTrainings::where("training_id", $modelId)->pluck('user_id')->toArray();
            $manager_details     = User::whereIn("id", $manager_ids)->get();
            $trainer_ids         = TrainerTrainings::where("training_id", $modelId)->pluck('user_id')->toArray();
            $trainer_details     = User::whereIn("id", $trainer_ids)->get();
            $trainee_ids         = TrainingParticipants::where("training_id", $modelId)->pluck('trainee_id')->toArray();
            $trainee_details     = User::whereIn("id", $trainee_ids)->get();
            $courses     = Course::where("training_id", $modelId)->get();
            return  View::make("admin.Training.view", compact('model', 'trainee_details', 'trainer_details', 'manager_details', 'courses'));
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'somthing went wrong');;
        }
    } // end edit()

    public function exportTraining(Request $request)
    {
        try {

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

            $export = new exportParticipants($filteredResult);
            return Excel::download($export, 'Training.xlsx');
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'somthing went wrong');;
        }
    }

    public function importTrainingParticipants($training_id = 0)
    {
        try {
            $projects = QDS_PROJECT_LIST;
            $methods = ['fromExcel' => 'From Excel',  'fromUser' => 'From Users'];
            $users = User::where("is_deleted", 0)->where("user_role_id", TRAINEE_ROLE_ID)->pluck('fullname', 'id')->toArray();

            return  View::make("admin.Training.uploadTrainingParticipants", compact('training_id', 'projects', 'methods', 'users'));
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'somthing went wrong');;
        }
    } // end add()


    public function importTraining($training_id = 0)
    {
        try {
            $import = new importParticipants($training_id);
            Excel::import($import, request()->file('file'));
            $errors = $import->getErrors();
            if (count($errors) > 0) {
                return view('errors.importTraineeError')->with('errors', $errors);
            }
            return redirect()->back()->with('success', 'Training Participants Added Successfully!');
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'somthing went wrong');;
        }
    }

    public function AssignManager()
    {
        try {

            $thisData                    =    Request::all();
            $training_id   = $thisData['training_id'];

            if ($training_id) {
                if (isset($thisData['training_manager']) && !empty($thisData['training_manager'])) {
                    //ContestStocks::where('contest_id',$contest_id)->delete();
                    foreach ($thisData['training_manager'] as $user_id) {
                        //	print_r($user_id); die;
                        $object                 = new ManagerTrainings;
                        $object->training_id    = $training_id;
                        $object->user_id    = $user_id;
                        $object->save();
                    }
                }
            }


            Session::flash('flash_notice', trans(" Manager has been Assign successfully"));
            return Redirect::back();
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'somthing went wrong');;
        }
    } // end delete()

    public function AssignTrainer()
    {
        try {

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
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'somthing went wrong');;
        }
    } // end delete()




    // Create training AI Functions
    public function addAi()
    {
        try {

            return view('admin.training.add_ai');
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'somthing went wrong');;
        }
    }

    public function saveAi(HttpRequest $request)
    {
        try {

            $text = $request->speechText;
            // dd($text);
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoiNzE4MGU2ZWItZjkyNS00NmVlLWJhNTUtOGIzYjIzMGEyZWRhIiwidHlwZSI6ImFwaV90b2tlbiJ9.HxWf4q93fpADe7AlcDTCBt_nJ6HlANsoFZeZz_KZ6RE', // Replace with your API key if needed
            ])->post('https://api.edenai.run/v2/text/generation', [
                "response_as_dict" => true,
                "attributes_as_list" => false,
                "show_original_response" => false,
                "temperature" => 0,
                "max_tokens" => 2048,
                "text" => $text,
                "providers" => "google,openai",
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
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'somthing went wrong');;
        }
    }
}// end TrainingController
