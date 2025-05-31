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
use App\Models\TestCategory;
use App\Models\Course;
use App\Models\Test;
use App\Models\StateDescription;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\ApiService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\Http\JsonResponse;

use \PDF;

use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Mail, Redirect, Session, URL, Validator;

class TestCategoryController extends BaseController
{

    public $model        =    'TestCategory';
    public $sectionName    =    'Test Categories';
    public $sectionNameSingular    =    'Test Category';

    public function __construct()
    {
        parent::__construct();
        View::share('modelName', $this->model);
        View::share('sectionName', $this->sectionName);
        View::share('sectionNameSingular', $this->sectionNameSingular);
    }

    public function index(Request $request)
    {
        $DB                            =    TestCategory::query();

        $searchVariable                =    array();
        $inputGet                    =    $request->all();
        if (($request->all())) {
            $searchData                =    $request->all();
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
        $sortBy                     =     ($request->get('sortBy')) ? $request->get('sortBy') : 'updated_at';
        $order                      =     ($request->get('order')) ? $request->get('order')   : 'DESC';
        $results                     =     $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
        $complete_string            =    $request->query();
        unset($complete_string["sortBy"]);
        unset($complete_string["order"]);
        $query_string                =    http_build_query($complete_string);
        $results->appends($request->all())->render();
        session(['filteredResult' => $results]);

        $training_manager = User::where("is_deleted", 0)->where("user_role_id", MANAGER_ROLE_ID)->pluck('first_name', 'id')->toArray();

        $trainers = User::where("is_deleted", 0)->where("user_role_id", TRAINER_ROLE_ID)->pluck('first_name', 'id')->toArray();

        return view("admin.TestCategory.index", compact('results', 'searchVariable', 'sortBy', 'order', 'query_string', 'training_manager', 'trainers'));
    }

    public function add()
    {
        dd('1');
        return view("admin.TestCategory.add");
    }

    public function save(Request $request)
    {
        $request->replace($this->arrayStripTags($request->all()));
        $data = $request->all();

        $rules = [
            'name' => 'required',
        ];

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $name = strtolower($request->get('name'));
        $keywords = str_replace(' ', '-', $name);

        $categoryData = [
            'name'        => $request->name,
            'parent_id'   => Auth::id(),
            'description' => $request->description,
            'keywords'    => $keywords,
            'is_active'    => $request->is_active,
        ];

        try {
            $category = TestCategory::updateOrCreate(
                ['id' => $request->id],
                $categoryData
            );

            if (!$category) {
                Session::flash('error', __(config('constants.REC_ADD_FAILED')));
            } else {
                $message = $request->id ? __(config('constants.REC_UPDATE_SUCCESS'), ['section' => $this->sectionNameSingular])
                    : __(config('constants.REC_ADD_SUCCESS'), ['section' => $this->sectionNameSingular]);
                Session::flash('success', $message);
            }
            return redirect()->route('TestCategory.index');
        } catch (\Exception $e) {
            Session::flash('error', __(config('constants.FLASH_TRY_CATCH')));
            return redirect()->route('TestCategory.index');
        }
    }


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
    }

    public function edit($modelId = 0)
    {
        $model                =    TestCategory::find($modelId);

        if (empty($model)) {
            Session::flash('error', __(config('constants.REC_NOT_FOUND')));
            return redirect()->route('TestCategory.index');
        }

        return  View::make("admin.$this->model.edit", compact('model'));
    }

    public function delete($id = 0)
    {
        $model    =    TestCategory::find($id);
        if (empty($model)) {
            return Redirect::back();
        }
        if ($id) {
            TestCategory::where('id', $id)->delete();
            Session::flash('flash_notice', trans($this->sectionNameSingular . " has been removed successfully"));
        }
        return Redirect::back();
    }
}
