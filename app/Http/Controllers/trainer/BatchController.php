<?php

namespace App\Http\Controllers\trainer;

use App\Http\Controllers\BaseController;
use App\Imports\importTrainees;
use App\Model\Batch;
use App\Model\TrainerAssignBatch;
use Auth;
use Config;
use Maatwebsite\Excel\Facades\Excel;
use Redirect;
use Request;
use Session;
use Validator;
use View;

/**
 * BatchController Controller
 *
 * Add your methods in the class below
 */
class BatchController extends BaseController
{
    public $model = 'TrainerBatch';

    public $sectionName = 'Batch';

    public $sectionNameSingular = 'Batch';

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
        $DB = Batch::query();
        $searchVariable = [];
        $inputGet = Request::all();
        // Get batch IDs assigned to the authenticated trainer
        $assignedBatchIds = TrainerAssignBatch::where('trainer_id', Auth::id())->pluck('batch_id')->toArray();

        // Fetch batches created by the logged-in user or assigned to them as a trainer
        $DB->where(function ($query) use ($assignedBatchIds) {
            $query->where('batches.created_by', Auth::user()->id)
                ->orWhereIn('batches.id', $assignedBatchIds);
        });
        if ((Request::all())) {
            $searchData = Request::all();
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
                if ($fieldValue != '') {

                    if ($fieldName == 'name') {
                        $DB->where('batches.name', 'like', '%' . $fieldValue . '%');
                    }
                }
                $searchVariable = array_merge($searchVariable, [$fieldName => $fieldValue]);
            }
        }
        $DB->with('assigned_trainers.trainer')->leftJoin('users', 'users.id', '=', 'batches.created_by')->select('batches.*', 'users.first_name as created_by');
        $sortBy = (Request::get('sortBy')) ? Request::get('sortBy') : 'updated_at';
        $order = (Request::get('order')) ? Request::get('order') : 'DESC';
        $results = $DB->orderBy($sortBy, $order)->paginate(Config::get('Reading.records_per_page'));
        $complete_string = Request::query();
        unset($complete_string['sortBy']);
        unset($complete_string['order']);
        $query_string = http_build_query($complete_string);
        $results->appends(Request::all())->render();

        //echo '<pre>'; print_r($results); die;
        return View::make("trainer.$this->model.index", compact('results', 'searchVariable', 'sortBy', 'order', 'query_string'));
    }

    /**
     * Function for add new State
     *
     * @param null
     * @return view page.
     */
    public function add()
    {
        return View::make("trainer.$this->model.add");
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
        // echo '<pre>'; print_r($thisData); die;

        $validator = Validator::make(
            $thisData,
            [
                'name' => 'required|unique:batches',
            ]
        );

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)->withInput();
        } else {
            $obj = new Batch;
            $obj->name = Request::get('name');
            $obj->created_by = Auth::user()->id;
            $objSave = $obj->save();
            if (!$objSave) {
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

        Batch::where('id', $modelId)->update(['is_active' => $status]);
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
        $model = Batch::find($modelId);
        if (empty($model)) {
            return Redirect::route($this->model . '.index');
        }
        return View::make("trainer.$this->model.edit", compact('model'));
    } // end edit()

    /**
     * Function for update area
     *
     * @param  $modelId  as id of area
     * @return redirect page.
     */
    public function update($modelId)
    {
        $model = Batch::findorFail($modelId);
        if (empty($model)) {
            return Redirect::back();
        }

        Request::replace($this->arrayStripTags(Request::all()));
        $thisData = Request::all();
        //echo '<pre>'; print_r($thisData); die;

        $validator = Validator::make(
            $thisData,
            [
                'name' => "required|unique:batches,name,$modelId",
            ]
        );

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)->withInput();
        } else {
            $obj = $model;
            $obj->name = Request::get('name');
            $obj->created_by = Auth::user()->id;
            $objSave = $obj->save();
            if (!$objSave) {

                Session::flash('error', trans('Something went wrong.'));

                return Redirect::route($this->model . '.index');
            } else {
                Session::flash('success', trans($this->sectionNameSingular . ' has been Updated successfully'));

                return Redirect::route($this->model . '.index');
            }
        }
    } // end update()

    public function view($modelId = 0)
    {
        $model = Batch::find($modelId);
        // return $model;
        if (empty($model)) {
            return Redirect::route($this->model . '.index');
        }
        $batchName = $model->name;
        $model = $model->users()->where('is_active', 1)->where('is_deleted', 0)->get();
        return View::make("trainer.$this->model.view", compact('model', 'batchName'));
    } // end edit()
    /**
     * Function for mark a couse as deleted
     *
     * @param  $userId  as id of couse
     * @return redirect page.
     */
    public function delete($id = 0)
    {
        $model = Batch::find($id);
        if (empty($model)) {
            return Redirect::back();
        }
        if ($id) {
            Batch::where('id', $id)->delete();
            Session::flash('flash_notice', trans($this->sectionNameSingular . ' has been removed successfully'));
        }

        return Redirect::back();
    } // end delete()
    public function importBatchUsers($batch_id = 0)
    {
        $batch = Batch::pluck('name', 'id')->toArray();

        return View::make("trainer.$this->model.uploadBatchUsers", compact('batch_id'));
    }

    public function importTrainees($batch_id)
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
        $import = new importTrainees($batch_id);
        Excel::import($import, request()->file('file'));
        $errors = $import->getErrors();

        if (count($errors) > 0) {
            return view('errors.importTraineeError')->with('errors', $errors);
        }

        return redirect()->back()->with('success', 'Users imported successfully!');
    }
} // end BatchController
