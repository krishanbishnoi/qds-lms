<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Imports\importTrainees;
use App\Model\Batch;
use App\Model\TrainerAssignBatch;
use App\Model\User;
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
    public $model = 'Batch';

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
        $DB->with('users', 'assigned_trainers.trainer')->leftJoin('users', 'users.id', '=', 'batches.created_by')->select('batches.*', 'users.first_name as created_by');
        $sortBy = (Request::get('sortBy')) ? Request::get('sortBy') : 'updated_at';
        $order = (Request::get('order')) ? Request::get('order') : 'DESC';
        $results = $DB->orderBy($sortBy, $order)->paginate(Config::get('Reading.records_per_page'));

        $results = $DB->orderBy($sortBy, $order)->paginate(Config::get('Reading.records_per_page'));

        // Format assigned trainers with their names
        $results->getCollection()->transform(function ($batch) {
            $batch->assigned_trainers = $batch->assigned_trainers->map(function ($trainerAssign) {
                $trainerAssign->trainer_name = $trainerAssign->trainer ? $trainerAssign->trainer->name : null; // Assuming 'name' is the column in the users table
                return $trainerAssign;
            });
            return $batch;
        });

        $complete_string = Request::query();
        unset($complete_string['sortBy']);
        unset($complete_string['order']);
        $query_string = http_build_query($complete_string);
        $results->appends(Request::all())->render();

        if (Auth::user()->user_role_id == 4) {
            $tr_trainers = User::where('is_deleted', 0)->where('user_role_id', 2)->where('is_developer', '!=', 1)->where('center', Auth::user()->center)->get(['id', 'fullname', 'olms_id']);
        } else {
            $tr_trainers = User::where('is_deleted', 0)->where('user_role_id', 2)->where('user_role_id', '!=', 1)->get(['id', 'fullname', 'olms_id']);
        }

        $trainers = $tr_trainers->mapWithKeys(function ($trainer) {
            return [$trainer->id => " {$trainer->olms_id} : {$trainer->fullname}"];
        })->toArray();

        //echo '<pre>'; print_r($results); die;
        return View::make("admin.$this->model.index", compact('results', 'searchVariable', 'sortBy', 'order', 'query_string', 'trainers'));
    }

    /**
     * Function for add new State
     *
     * @param null
     * @return view page.
     */
    public function add()
    {
        return View::make("admin.$this->model.add");
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

        return View::make("admin.$this->model.edit", compact('model'));
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
    }

    // end update()
    public function view($modelId = 0)
    {
        $model = Batch::find($modelId);
        // return $model;
        if (empty($model)) {
            return Redirect::route($this->model . '.index');
        }
        $batchName = $model->name;
        $model = $model->users->where('is_active', 1)->where('is_deleted', 0);

        return View::make("admin.$this->model.view", compact('model', 'batchName'));
    }

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
    }

    // end delete()
    public function importBatchUsers($batch_id = 0)
    {
        $batch = Batch::pluck('name', 'id')->toArray();

        return View::make("admin.$this->model.uploadBatchUsers", compact('batch_id'));
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
    public function AssignTrainer()
    {
        $thisData = Request::all();
        $batch_id = $thisData['batch_id'];

        if ($batch_id) {
            // Get existing trainers assigned to this batch
            $existingAssignments = TrainerAssignBatch::where('batch_id', $batch_id)->pluck('trainer_id')->toArray();

            // Get the new trainer IDs from the request
            $newTrainerIds = isset($thisData['batch_trainer']) ? $thisData['batch_trainer'] : [];

            // Determine trainers to remove
            $trainersToRemove = array_diff($existingAssignments, $newTrainerIds);
            if (!empty($trainersToRemove)) {
                TrainerAssignBatch::where('batch_id', $batch_id)
                    ->whereIn('trainer_id', $trainersToRemove)
                    ->delete();
            }

            // Now handle the new trainer assignments
            foreach ($newTrainerIds as $user_id) {
                // Only assign if the trainer is not already assigned
                if (!in_array($user_id, $existingAssignments)) {
                    $object = new TrainerAssignBatch;
                    $object->batch_id = $batch_id;
                    $object->trainer_id = $user_id;
                    $object->created_by = Auth::user()->id;
                    $object->save();
                }
            }

            Session::flash('flash_notice', trans('Trainer has been assigned successfully'));
        }

        return Redirect::back();
    }
} // end BatchController
