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
use Symfony\Component\HttpFoundation\Response;
use App\ApiService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class TrainingCategoryController extends BaseController
{

    public $model        =    'TrainingCategory';
    public $sectionName    =    'Training Category';
    public $sectionNameSingular    =    'Training Category';

    public function __construct()
    {
        parent::__construct();
        View::share('modelName', $this->model);
        View::share('sectionName', $this->sectionName);
        View::share('sectionNameSingular', $this->sectionNameSingular);
    }

    public function index(Request $request)
    {
        try {

            $DB = TrainingCategory::query();

            $searchVariable = array();
            $inputGet = $request->all();

            if ($request->hasAny(['is_active', 'title'])) {  // Check if any search parameters exist
                $searchData = $request->except(['display', '_token', 'order', 'sortBy', 'page']);

                foreach ($searchData as $fieldName => $fieldValue) {
                    if ($fieldValue != "") {
                        if ($fieldName == "is_active") {
                            $DB->where("training_categories.is_active", $fieldValue);
                        }
                        if ($fieldName == "title") {
                            $DB->where("training_categories.name", 'like', '%' . $fieldValue . '%');
                        }
                    }
                    $searchVariable = array_merge($searchVariable, array($fieldName => $fieldValue));
                }
            }

            $sortBy = $request->input('sortBy', 'updated_at');
            $order = $request->input('order', 'DESC');

            $results = $DB->orderBy($sortBy, $order)
                ->paginate(config("Reading.records_per_page"))
                ->appends($request->all());

            $complete_string = $request->query();
            unset($complete_string["sortBy"]);
            unset($complete_string["order"]);
            $query_string = http_build_query($complete_string);

            session(['filteredResult' => $results]);

            $training_manager = User::where("is_deleted", 0)
                ->where("user_role_id", MANAGER_ROLE_ID)
                ->pluck('first_name', 'id')
                ->toArray();

            $trainers = User::where("is_deleted", 0)
                ->where("user_role_id", TRAINER_ROLE_ID)
                ->pluck('first_name', 'id')
                ->toArray();

            return View::make("admin.TrainingCategory.index", compact(
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

            return  View::make("admin.TrainingCategory.add");
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'somthing went wrong');;
        }
    }

    function save(Request $request)
    {
        try {
            $request->replace($this->arrayStripTags($request->all()));
            $thisData = $request->all();

            $rules = [
                'name' => 'required',
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

                $name = strtolower($request->name);
                $keywords = str_replace(' ', '-', $name);

                $obj = TrainingCategory::updateOrCreate(
                    ['id' => $request->id], // use id if editing
                    [
                        'name' => $request->name,
                        'parent_id' => Auth::user()->id,
                        'description' => $request->description,
                        'keywords' => $keywords,
                    ]
                );

                if (!$obj) {
                    Session::flash('error', trans("Something went wrong."));
                    return Redirect::route("TrainingCategory.index");
                } else {
                    Session::flash('success', trans($this->sectionNameSingular . " has been added successfully"));
                    return Redirect::route("TrainingCategory.index");
                }
            }
        } catch (\Exception $e) {
            Session::flash('error', trans("Something went wrong."));
            return Redirect::back()->withInput();
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
            $model = TrainingCategory::find($modelId);
            if (empty($model)) {
                return Redirect::route("TrainingCategory.index");
            }
            return  View::make("admin.TrainingCategory.add", compact('model'));
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'somthing went wrong');;
        }
    }

    public function delete($id = 0)
    {
        try {
            $model = TrainingCategory::find($id);
            if (empty($model)) {
                return Redirect::back();
            }
            if ($id) {
                TrainingCategory::where('id', $id)->delete();
                Session::flash('flash_notice', trans($this->sectionNameSingular . " has been removed successfully"));
            }
            return Redirect::back();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'somthing went wrong');;
        }
    }
}
