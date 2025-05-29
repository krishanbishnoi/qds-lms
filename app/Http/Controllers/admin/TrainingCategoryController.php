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
use App\Models\ManagerTrainings;
use App\Models\TrainerTrainings;
use App\Models\TrainingCategory;
use App\Models\Course;
use App\Models\Test;
use App\Models\StateDescription;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Illuminate\Http\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response;
use App\ApiService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\Http\JsonResponse;

use \PDF;

use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Mail, Redirect, Session, URL, Validator, Request;

/**
 * TrainingCategoryController Controller
 *
 * Add your methods in the class below
 *
 */
class TrainingCategoryController extends BaseController
{

    public $model        =    'TrainingCategory';
    public $sectionName    =    'Training Categories';
    public $sectionNameSingular    =    'Training Category';

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
        $DB                            =    TrainingCategory::query();

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
                        $DB->where("training_categories.is_active", $fieldValue);
                    }
                    if ($fieldName == "title") {
                        $DB->where("training_categories.name", 'like', '%' . $fieldValue . '%');
                    }
                }
                $searchVariable    =    array_merge($searchVariable, array($fieldName => $fieldValue));
            }
        }
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

        $training_manager = User::where("is_deleted", 0)->where("user_role_id", MANAGER_ROLE_ID)->pluck('first_name', 'id')->toArray();

        $trainers = User::where("is_deleted", 0)->where("user_role_id", TRAINER_ROLE_ID)->pluck('first_name', 'id')->toArray();

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
        return  View::make("admin.$this->model.add");
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

        $rules = [
            'name'             => 'required',
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

            $name = strtolower(Request::get('name'));
            $keywords = str_replace(' ', '-', $name);

            $obj = new TrainingCategory;
            $obj->name                 = Request::get('name');

            $obj->parent_id           = Auth::user()->id;
            $obj->description         = Request::get('description');
            $obj->keywords            = $keywords;

            $obj->save();


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
        $model                =    TrainingCategory::find($modelId);

        if (empty($model)) {
            return Redirect::route($this->model . ".index");
        }

        return  View::make("admin.$this->model.edit", compact('model'));
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
        $model                    =    TrainingCategory::findorFail($modelId);
        if (empty($model)) {
            return Redirect::back();
        }

        Request::replace($this->arrayStripTags(Request::all()));
        $thisData                    =    Request::all();
        //echo '<pre>'; print_r($thisData); die;

        $rules = [
            'name'             => 'required',
        ];

        $validator = Validator::make($thisData, $rules);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)->withInput();
        } else {
            $name = strtolower(Request::get('name'));
            $keywords = str_replace(' ', '-', $name);

            $obj = $model;
            $obj->name                   = Request::get('name');
            $obj->parent_id           = Auth::user()->id;
            $obj->description         = Request::get('description');
            $obj->keywords            = $keywords;

            $obj->save();

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
        $model    =    TrainingCategory::find($id);
        if (empty($model)) {
            return Redirect::back();
        }
        if ($id) {
            TrainingCategory::where('id', $id)->delete();
            Session::flash('flash_notice', trans($this->sectionNameSingular . " has been removed successfully"));
        }
        return Redirect::back();
    } // end delete()

}// end TrainingCategoryController
