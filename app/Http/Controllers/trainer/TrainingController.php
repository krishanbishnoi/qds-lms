<?php

namespace App\Http\Controllers\trainer;

use App\Http\Controllers\BaseController;
use App\Models\Training;
use App\Models\User;
use App\Models\TrainingParticipants;
use App\Imports\importParticipants;
use App\Exports\exportParticipants;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\TrainingDocument;
use App\Models\TrainingType;
use App\Models\ManagerTrainings;
use App\Models\TrainerTrainings;
use App\Models\TrainingCategory;
use App\Models\Course;
use App\Models\Test;
use App\Models\StateDescription;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Request, Mail, Redirect, Response, Session, URL, View, Validator;



/**
 * TrainingController Controller
 *
 * Add your methods in the class below
 *
 */
class TrainingController extends BaseController
{

    public $model        =    'TrainerTraining';
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
    public function index()
    {
        $DB                            =    Training::query();

        if (Auth::user()->user_role_id == TRAINER_ROLE_ID) {
            $created_by_self_ids = $DB->where('user_id', Auth::user()->id)->pluck('id')->toArray();

            $my_assign_training_ids  = TrainerTrainings::where("user_id", Auth::user()->id)->where('training_id', '!=', '')->pluck('training_id')->toArray();
            $users = $DB->whereIn('trainings.id', $created_by_self_ids)
                ->orWhereIn('trainings.id', $my_assign_training_ids);
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
                        $DB->where("trainings.is_active", $fieldValue);
                    }
                    if ($fieldName == "title") {
                        $DB->where("trainings.title", 'like', '%' . $fieldValue . '%');
                    }
                }
                $searchVariable    =    array_merge($searchVariable, array($fieldName => $fieldValue));
            }
        }
        $DB->leftJoin('training_types', 'training_types.id', '=', 'trainings.type')->leftJoin('users', 'users.id', '=', 'trainings.user_id')->join('training_categories', 'training_categories.id', '=', 'trainings.category_id')->select('trainings.*', 'training_types.type as type', 'users.first_name as created_by', 'training_categories.name as category_name');
        $sortBy                     =     (Request::get('sortBy')) ? Request::get('sortBy') : 'updated_at';
        $order                      =     (Request::get('order')) ? Request::get('order')   : 'DESC';
        $results                     =     $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
        $complete_string            =    Request::query();
        unset($complete_string["sortBy"]);
        unset($complete_string["order"]);
        $query_string                =    http_build_query($complete_string);
        $results->appends(Request::all())->render();
        //echo '<pre>'; print_r($results); die;
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

        $trainingCategory = TrainingCategory::pluck('name', 'id')->toArray();
        $test = Test::pluck('title', 'id')->toArray();
        $TrainingType = TrainingType::pluck('type', 'id')->toArray();
        $trainees = User::where("is_deleted", 0)->where("user_role_id", TRAINEE_ROLE_ID)->pluck('first_name', 'id')->toArray();
        return  View::make("trainer.$this->model.add", compact('trainingCategory', 'TrainingType', 'trainees', 'test'));
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
                'category_id'             => 'required',
                'title'             => 'required',
                'type'             => 'required',
                'start_date_time'     => 'required',
                'end_date_time'             => 'required',
                'thumbnail'             => 'required',
            )
        );

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)->withInput();
        } else {
            $obj = new Training;
            $obj->category_id              = Request::get('category_id');
            $obj->title                 = Request::get('title');
            $obj->type                  = Request::get('type');
            $obj->user_id               = Auth::user()->id;
            $obj->start_date_time       = Request::get('start_date_time');
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
            $training_id                    =    $obj->id;

            // if($training_id){
            // 	if(isset($thisData['trainees']) && !empty($thisData['trainees'])) {
            // 		//ContestStocks::where('contest_id',$contest_id)->delete();
            // 		foreach($thisData['trainees'] as $trainee_id) {
            // 		//	print_r($trainee_id); die;
            // 			$object 				= new TrainingParticipants ;
            // 			$object->training_id	= $training_id;
            // 			$object->trainee_id	= $trainee_id;
            // 			$object->save();
            // 		}
            // 	}
            // }


            // if($training_id){
            // 	if(isset($thisData['data']) && !empty($thisData['data'])) {
            // 		foreach($thisData['data'] as $training_documents) {

            // 			$obj 					= new TrainingDocument;
            // 			$obj->training_id		= $training_id;
            // 			if(isset($training_documents['title']) && !empty($training_documents['title'])){
            // 				$title	=	$training_documents["title"];
            // 				$obj->title		=	$title;
            // 			}
            // 			if(isset($training_documents['document'])  && !empty($training_documents['document'])){


            // 				$extension 	=	 $training_documents['document']->getClientOriginalExtension();
            // 				$fileName	=	time().'-document.'.$extension;

            // 				$folderName     	= 	strtoupper(date('M'). date('Y'))."/";
            // 				$folderPath			=	TRAINING_DOCUMENT_ROOT_PATH.$folderName;
            // 				if(!File::exists($folderPath)) {
            // 					File::makeDirectory($folderPath, $mode = 0777,true);
            // 				}
            // 				if(!empty($extension)){
            // 					$obj->document_type	=	$extension;
            // 				}
            // 				if($training_documents['document']->move($folderPath, $fileName)){
            // 					$obj->document	=	$folderName.$fileName;
            // 				}



            // 				$imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'ico'];
            // 				$videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'mkv', 'flv','mpeg','mpg'];
            // 				$fileExtensions = ['doc', 'pdf', 'txt', 'xls', 'xlsx', 'ppt', 'csv', 'odt'];
            // 				if (in_array($extension, $imageExtensions)) {
            // 						$obj->type	=	'image';
            // 				} elseif (in_array($extension, $videoExtensions)) {
            // 					$obj->type	=	'video';
            // 				} elseif (in_array($extension, $fileExtensions)) {
            // 					$obj->type	=	'doc';
            // 				}

            // 			}
            // 			$obj->save();
            // 		}
            // 	}
            // }
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

        Training::where('id', $modelId)->update(array('is_active' => $status));
        Session::flash('flash_notice', $statusMessage);
        return Redirect::back();
    } // end changeStatus()

    /**
     * Function for display page for edit area
     *
     * @param $modelId id  of area
     *
     * @return view page.
     */
    public function edit($modelId = 0)
    {
        $model                =    Training::find($modelId);

        if (empty($model)) {
            return Redirect::route($this->model . ".index");
        }
        $trainingCategory = TrainingCategory::pluck('name', 'id')->toArray();
        $TrainingType = TrainingType::pluck('type', 'id')->toArray();
        $trainees = User::where("is_deleted", 0)->where("user_role_id", TRAINEE_ROLE_ID)->pluck('first_name', 'id')->toArray();

        $selected_trainees = TrainingParticipants::where('training_id', $modelId)->pluck('trainee_id');

        // $documents =  DB::table('training_documents')->where('training_id',$modelId)->get();
        return  View::make("trainer.$this->model.edit", compact('model', 'trainingCategory', 'TrainingType', 'trainees', 'selected_trainees'));
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
        $model                    =    Training::findorFail($modelId);
        if (empty($model)) {
            return Redirect::back();
        }

        Request::replace($this->arrayStripTags(Request::all()));
        $thisData                    =    Request::all();
        //echo '<pre>'; print_r($thisData); die;

        $validator = Validator::make(
            $thisData,
            array(
                'category_id'             => 'required',
                'title'             => 'required',
                'type'             => 'required',
                'start_date_time'     => 'required',
                'end_date_time'             => 'required',
            )
        );

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)->withInput();
        } else {
            $obj = $model;
            $obj->category_id            = Request::get('category_id');
            $obj->type                   = Request::get('type');
            $obj->title                   = Request::get('title');
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
            $training_id                    =    $obj->id;

            // if($training_id){
            // 	if(isset($thisData['trainees']) && !empty($thisData['trainees'])) {
            // 		TrainingParticipants::where('training_id',$training_id)->delete();
            // 		foreach($thisData['trainees'] as $trainee_id) {
            // 		//	print_r($trainee_id); die;
            // 			$object 				= new TrainingParticipants ;
            // 			$object->training_id	= $training_id;
            // 			$object->trainee_id	= $trainee_id;
            // 			$object->save();
            // 		}
            // 	}
            // }
            // if($training_id){
            // 	if(isset($thisData['data']) && !empty($thisData['data'])) {
            // 		TrainingDocument::where('training_id',$training_id)->delete();
            // 		foreach($thisData['data'] as $training_documents) {

            // 			$obj 					= new TrainingDocument;
            // 			$obj->training_id		= $training_id;
            // 			if(isset($training_documents['title']) && !empty($training_documents['title'])){
            // 				$title	=	$training_documents["title"];
            // 				$obj->title		=	$title;
            // 			}
            // 			if(isset($training_documents['document'])  && !empty($training_documents['document'])){


            // 				$extension 	=	 $training_documents['document']->getClientOriginalExtension();
            // 				$fileName	=	time().'-document.'.$extension;

            // 				$folderName     	= 	strtoupper(date('M'). date('Y'))."/";
            // 				$folderPath			=	TRAINING_DOCUMENT_ROOT_PATH.$folderName;
            // 				if(!File::exists($folderPath)) {
            // 					File::makeDirectory($folderPath, $mode = 0777,true);
            // 				}
            // 				if(!empty($extension)){
            // 					$obj->document_type	=	$extension;
            // 				}
            // 				if($training_documents['document']->move($folderPath, $fileName)){
            // 					$obj->document	=	$folderName.$fileName;
            // 				}



            // 				$imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'ico'];
            // 				$videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'mkv', 'flv','mpeg','mpg'];
            // 				$fileExtensions = ['doc', 'pdf', 'txt', 'xls', 'xlsx', 'ppt', 'csv', 'odt'];
            // 				if (in_array($extension, $imageExtensions)) {
            // 						$obj->type	=	'image';
            // 				} elseif (in_array($extension, $videoExtensions)) {
            // 					$obj->type	=	'video';
            // 				} elseif (in_array($extension, $fileExtensions)) {
            // 					$obj->type	=	'doc';
            // 				}else{
            // 					$obj->type	=	'';
            // 				}
            // 			}
            // 			$obj->save();
            // 		}
            // 	}
            // }
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
        $model    =    Training::find($id);
        if (empty($model)) {
            return Redirect::back();
        }
        if ($id) {
            Training::where('id', $id)->delete();
            Session::flash('flash_notice', trans($this->sectionNameSingular . " has been removed successfully"));
        }
        return Redirect::back();
    } // end delete()


    public function addMoreDocument()
    {
        $offset = $_POST['offset'];
        return  View::make("trainer.$this->model.addMoreDetails", compact('offset', 'offset'));
    } // end updateProjectStatus()

    public function deleteMoreDocument()
    {
        $id = $_POST['id'];
        $output = 0;
        if ($id) {
            $projectDetailModel                    =    TrainingDocument::where('id', '=', $id)->delete();
            $output = 1;
            //Session::flash('flash_notice',trans("Detail removed successfully"));
        }
        echo $output;
        die;
    } // end updateProjectStatus()


    public function view($modelId = 0)
    {
        $model                =    Training::find($modelId);
        // return $model;

        if (empty($model)) {
            return Redirect::route($this->model . ".index");
        }

        $model = $model->leftJoin('training_types', 'training_types.id', '=', 'trainings.type')->leftJoin('users', 'users.id', '=', 'trainings.user_id')->select('trainings.*', 'training_types.type as type', 'users.first_name as created_by')->first();

        $createdBy = $model->first_name;
        // echo '<pre>'; print_r($createdBy); die;

        $manager_ids         = ManagerTrainings::where("training_id", $modelId)->pluck('user_id')->toArray();
        $manager_details     = User::whereIn("id", $manager_ids)->get();

        $trainer_ids         = TrainerTrainings::where("training_id", $modelId)->pluck('user_id')->toArray();
        $trainer_details     = User::whereIn("id", $trainer_ids)->get();

        $trainee_ids         = TrainingParticipants::where("training_id", $modelId)->pluck('trainee_id')->toArray();
        $trainee_details     = User::whereIn("id", $trainee_ids)->get();


        $courses     = Course::where("training_id", $modelId)->get();


        return  View::make("trainer.$this->model.view", compact('model', 'trainee_details', 'trainer_details', 'manager_details', 'courses'));
    }


    /**
     * Function for display page for edit area
     *
     * @param $modelId id  of area
     *
     * @return view page.
     */
    public function userTrainings()
    {
        $myTrainingsIds = TrainingParticipants::where('trainee_id', Auth::user()->id)->pluck('training_id')->toArray();

        //  echo '<pre>'; print_r($myTrainingsIds); die;
        if (!empty($myTrainingsIds)) {
            $myTrainings = Training::whereIn('trainings.id', $myTrainingsIds)->leftJoin('training_types', 'training_types.id', '=', 'trainings.type')->leftJoin('users', 'users.id', '=', 'trainings.user_id')->select('trainings.*', 'training_types.type as type', 'users.first_name as created_by')->get();
        } else {
            $myTrainings = '';
        }

        // echo '<pre>'; print_r($myTrainings); die;

        return  View::make("trainer.$this->model.userTraining", compact('myTrainings'));
    }

    /**
     * Function for mark a couse as deleted
     *
     * @param $userId as id of couse
     *
     * @return redirect page.
     */

    public function userTrainingDetails($training_id = 0)
    {


        $trainingDetails = Training::where('trainings.id', $training_id)->leftJoin('training_types', 'training_types.id', '=', 'trainings.type')->leftJoin('users', 'users.id', '=', 'trainings.user_id')->select('trainings.*', 'training_types.type as type', 'users.first_name as created_by')->first();
        //  echo '<pre>'; print_r($trainingDetails); die;

        return  View::make("trainer.$this->model.userTrainingDetails", compact('trainingDetails'));
    } // end delete()


    public function importTrainingParticipants($training_id = 0)
    {

        return  View::make("trainer.$this->model.uploadTrainingParticipants", compact('training_id'));
    } // end add()

    public function importTraining($training_id = 0)
    {
        $import = new importParticipants($training_id);
        Excel::import($import, request()->file('file'));
        $errors = $import->getErrors();
        if (count($errors) > 0) {
            return view('errors.importTraineeError')->with('errors', $errors);
        }
        return redirect()->back()->with('success', 'Training Participants Added Successfully!');
    }

    public function exportTraining(Request $request)
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

        $export = new exportParticipants($filteredResult);
        return Excel::download($export, 'Training.xlsx');
    }
}// end TrainingController
