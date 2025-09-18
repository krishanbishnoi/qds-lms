<?php

namespace App\Http\Controllers\admin;

use App\Exports\exportParticipants;
use App\Http\Controllers\BaseController;
use App\Imports\importParticipants;
use App\Model\BatchReport;
use App\Model\Course;
use App\Model\ManagerTrainings;
use App\Model\TrainerTrainings;
use App\Model\Training;
use App\Model\TrainingDocument;
use App\Model\TrainingParticipants;
use App\Model\TrainingType;
use App\Model\User;
use Auth;
use Config;
use DB;
use File;
use Maatwebsite\Excel\Facades\Excel;
use Redirect;
use Request;
use Session;
use Validator;
use View;

/**
 * TrainingController Controller
 *
 * Add your methods in the class below
 */
class BatchReportController extends BaseController
{
    public $model = 'AdminBatchReports';

    public $sectionName = 'Training Report';

    public $sectionNameSingular = 'Training Report';

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
        $DB = BatchReport::query();

        // $searchVariable = [];
        // $inputGet = Request::all();

        // if (Request::filled('is_active')) {
        //     $DB->where('is_active', Request::input('is_active'));
        // }

        // if (Request::filled('title')) {
        //     $DB->where('report_name', 'like', '%' . Request::input('title') . '%');
        // }

        // // Ordering
        // $sortBy = Request::input('sortBy', 'updated_at');
        // $order = Request::input('order', 'DESC');
        // $DB->orderBy($sortBy, $order);

        // // Pagination
        // $results = $DB->paginate(Config::get('Reading.records_per_page'));
        // $results->appends(Request::except('page'));

        // // Prepare search variables and query string for pagination links
        // $searchVariable = Request::except(['page', 'sortBy', 'order']);
        // $query_string = http_build_query($searchVariable);

        // // Store results in session for export functionality
        // session(['exportTrainees' => $results]);

        // // Return view with results, search variables, sorting parameters, and query string
        // return View::make("trainer.$this->model.index", compact('results', 'searchVariable', 'sortBy', 'order', 'query_string'));
        if (Auth::user()->user_role_id == 1) {
            $created_by_self_ids = $DB->pluck('id')->toArray();

            $my_assign_training_ids = BatchReport::where('id', '!=', '')->pluck('id')->toArray();
            // $users = $DB->whereIn('batch_reports.id', $created_by_self_ids)
            // ->orWhereIn('trainings.id', $my_assign_training_ids);
        } else {
            $DB = $DB;
        }
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
                    if ($fieldName == 'is_active') {
                        $DB->where('batch_reports.is_active', $fieldValue);
                    }
                    if ($fieldName == 'report_name') {
                        $DB->where('batch_reports.report_name', 'like', '%' . $fieldValue . '%');
                    }
                }
                $searchVariable = array_merge($searchVariable, [$fieldName => $fieldValue]);
            }
        }
        // $DB->leftJoin('training_types', 'training_types.id', '=', 'trainings.type')->leftJoin('users', 'users.id', '=', 'trainings.user_id')->select('trainings.*', 'training_types.type as type', 'users.fullname as created_by');
        $DB->leftJoin('users', 'users.id', '=', 'batch_reports.created_by')->select('batch_reports.*', 'users.fullname as created_by')->first();
        $sortBy = (Request::get('sortBy')) ? Request::get('sortBy') : 'updated_at';
        $order = (Request::get('order')) ? Request::get('order') : 'DESC';
        $results = $DB->orderBy($sortBy, $order)->paginate(Config::get('Reading.records_per_page'));
        $complete_string = Request::query();
        unset($complete_string['sortBy']);
        unset($complete_string['order']);
        $query_string = http_build_query($complete_string);
        $results->appends(Request::all())->render();
        session(['exportTrainees' => $results]);


        //echo '<pre>'; print_r($results); die;
        return View::make("admin.$this->model.index", compact('results', 'searchVariable', 'sortBy', 'order', 'query_string'));
    }
    /**
     * Function for add new State
     *
     * @param null
     * @return view page.
     */
    public function add()
    {

        $trainees = User::where('is_deleted', 0)->where('user_role_id', 3)->pluck('fullname', 'id')->toArray();
        return View::make("admin.$this->model.add", compact('trainees'));
    }// end add()

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

        $validator = Validator::make(
            $thisData,
            [
                'report_name' => 'required',
                'report_type' => 'required',
                'report_from' => 'required',
                // 'report_to' => 'required',

            ]
        );

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)->withInput();
        } else {
            $obj = new BatchReport();
            $obj->report_name = Request::get('report_name');
            $obj->report_type = Request::get('report_type');
            $obj->created_by = Auth::user()->id;

            $obj->report_from = Request::get('report_from');
            $obj->report_to = Request::get('report_to');

            if (isset($thisData['data']) && is_array($thisData['data'])) {
                foreach ($thisData['data'] as $training_document) {
                    if (isset($training_document['document']) && !empty($training_document['document'])) {
                        $extension = $training_document['document']->getClientOriginalExtension();
                        $fileName = time() . '-document.' . $extension;

                        $folderName = "";
                        $folderPath = config('BATCH_REPORT_FILES') . $folderName;

                        if (!File::exists($folderPath)) {
                            File::makeDirectory($folderPath, $mode = 0777, true);
                        }

                        if ($training_document['document']->move($folderPath, $fileName)) {
                            $obj->document_type = $extension;
                            $obj->document = $folderName . $fileName;

                            // Determine file type
                            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'ico'];
                            $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'mkv', 'flv', 'mpeg', 'mpg'];
                            $fileExtensions = ['doc', 'pdf', 'txt', 'xls', 'xlsx', 'ppt', 'csv', 'odt'];

                            if (in_array($extension, $imageExtensions)) {
                                $obj->type = 'image';
                            } elseif (in_array($extension, $videoExtensions)) {
                                $obj->type = 'video';
                            } elseif (in_array($extension, $fileExtensions)) {
                                $obj->type = 'doc';
                            }

                            $obj->save();
                        }
                    }
                }
            }

            if (!$obj->save()) {

                Session::flash('error', trans('Something went wrong.'));

                return Redirect::route($this->model . '.index');
            } else {
                Session::flash('success', trans($this->sectionNameSingular . ' has been added successfully'));

                return Redirect::route($this->model . '.index');
            }
        }
    }//end save()

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
    }// end changeStatus()

    /**
     * Function for display page for edit area
     *
     * @param  $modelId  id  of area
     * @return view page.
     */
    public function edit($modelId = 0)
    {
        $model = BatchReport::find($modelId);

        if (empty($model)) {
            return Redirect::route($this->model . '.index');
        }
        $documents = DB::table('batch_reports')->where('id', $modelId)->get();
        return View::make("admin.$this->model.edit", compact('model', 'documents'));
    } // end edit()

    /**
     * Function for update area
     *
     * @param  $modelId  as id of area
     * @return redirect page.
     */
    public function update($modelId)
    {
        $model = BatchReport::findorFail($modelId);
        if (empty($model)) {
            return Redirect::back();
        }

        Request::replace($this->arrayStripTags(Request::all()));
        $thisData = Request::all();
        //echo '<pre>'; print_r($thisData); die;

        $validator = Validator::make(
            $thisData,
            [
                'report_name' => 'required',
                'report_type' => 'required',
                'report_from' => 'required',
                // 'report_to' => 'required',
            ]
        );

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)->withInput();
        } else {
            $obj = $model;
            $obj->report_name = Request::get('report_name');
            $obj->report_type = Request::get('report_type');
            $obj->created_by = Auth::user()->id;
            $obj->report_from = Request::get('report_from');
            $obj->report_to = Request::get('report_to');
            $obj->save();
            if (isset($thisData['data']) && is_array($thisData['data'])) {
                foreach ($thisData['data'] as $training_documents) {
                    $obj = BatchReport::updateOrCreate(
                        [
                            'id' => $modelId,
                        ]
                    );
                    if (isset($training_documents['document']) && !empty($training_documents['document'])) {
                        $extension = $training_documents['document']->getClientOriginalExtension();
                        $fileName = time() . '-document.' . $extension;

                        $folderName = "";
                        $folderPath = config('BATCH_REPORT_FILES') . $folderName;

                        if (!File::exists($folderPath)) {
                            File::makeDirectory($folderPath, $mode = 0777, true);
                        }

                        if ($training_documents['document']->move($folderPath, $fileName)) {
                            $obj->document_type = $extension;
                            $obj->document = $folderName . $fileName;

                            // Determine file type
                            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'ico'];
                            $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'mkv', 'flv', 'mpeg', 'mpg'];
                            $fileExtensions = ['doc', 'pdf', 'txt', 'xls', 'xlsx', 'ppt', 'csv', 'odt'];

                            if (in_array($extension, $imageExtensions)) {
                                $obj->type = 'image';
                            } elseif (in_array($extension, $videoExtensions)) {
                                $obj->type = 'video';
                            } elseif (in_array($extension, $fileExtensions)) {
                                $obj->type = 'doc';
                            }
                            $obj->save();
                        }
                    }
                }
            }
            if (!$obj->save()) {

                Session::flash('error', trans('Something went wrong.'));

                return Redirect::route($this->model . '.index');
            } else {
                Session::flash('success', trans($this->sectionNameSingular . ' has been Updated successfully'));

                return Redirect::route($this->model . '.index');
            }
        }
    }// end update()

    /**
     * Function for mark a couse as deleted
     *
     * @param  $userId  as id of couse
     * @return redirect page.
     */
    public function delete($id = 0)
    {
        $model = BatchReport::find($id);
        if (empty($model)) {
            return Redirect::back();
        }
        if ($id) {
            BatchReport::where('id', $id)->delete();
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
            $projectDetailModel = BatchReport::where('id', '=', $id)->delete();
            $output = 1;
            //Session::flash('flash_notice',trans("Detail removed successfully"));
        }
        echo $output;
        exit;

    } // end updateProjectStatus()

    public function view($modelId = 0)
    {
        $model = BatchReport::find($modelId);
        // return $model;
        if (empty($model)) {
            return Redirect::route($this->model . '.index');
        }

        $model = $model->where('id', $modelId)->first();

        $createdById = $model->created_by;
        $createdByName = User::where('id',$createdById)->value('fullname');

        return View::make("admin.$this->model.view", compact('model', 'createdByName'));
    }

    /**
     * Function for display page for edit area
     *
     * @param  $modelId  id  of area
     * @return view page.
     */
    public function userTrainings()
    {
        $myTrainingsIds = TrainingParticipants::where('trainee_id', Auth::user()->id)->pluck('training_id')->toArray();

        //  echo '<pre>'; print_r($myTrainingsIds); die;
        if (!empty($myTrainingsIds)) {
            $myTrainings = Training::whereIn('trainings.id', $myTrainingsIds)->leftJoin('training_types', 'training_types.id', '=', 'trainings.type')->leftJoin('users', 'users.id', '=', 'trainings.user_id')->select('trainings.*', 'training_types.type as type', 'users.fullname as created_by')->get();

        } else {
            $myTrainings = '';
        }

        // echo '<pre>'; print_r($myTrainings); die;

        return View::make("admin.$this->model.userTraining", compact('myTrainings'));

    }

    /**
     * Function for mark a couse as deleted
     *
     * @param  $userId  as id of couse
     * @return redirect page.
     */
    public function userTrainingDetails($training_id = 0)
    {

        $trainingDetails = Training::where('trainings.id', $training_id)->leftJoin('training_types', 'training_types.id', '=', 'trainings.type')->leftJoin('users', 'users.id', '=', 'trainings.user_id')->select('trainings.*', 'training_types.type as type', 'users.fullname as created_by')->first();
        //  echo '<pre>'; print_r($trainingDetails); die;

        return View::make("admin.$this->model.userTrainingDetails", compact('trainingDetails'));
    } // end delete()

    public function importTrainingParticipants($training_id = 0)
    {

        return View::make("admin.$this->model.uploadTrainingParticipants", compact('training_id'));
    }// end add()

    public function importTraining($training_id = 0)
    {
        $import = new importParticipants($training_id);
        Excel::import($import, request()->file('file'));
        $errors = $import->getErrors();

        if (count($errors) > 0) {
            $errorMessages = implode('<BR>', $errors);

            return redirect()->back()->with('error', $errorMessages);
        }

        return redirect()->back()->with('success', 'Training Participants Added Successfully!');
    }

    public function exportTrainees(Request $request)
    {
        $filteredResult = Session::get('exportTrainees');

        $filteredResult = $filteredResult->map(function ($item) {
            $selectedFields = $item->only([

            ]);
            // $selectedFields['description'] = strip_tags($selectedFields['description']);

            if ($selectedFields['is_active'] == '1') {
                $selectedFields['is_active'] = 'Activated';
            } else {
                $selectedFields['is_active'] = 'Inactive';
            }

            return $selectedFields;
        });

        $export = new TraineesExport($filteredResult);

        return Excel::download($export, 'users.xlsx');
    }

    public function fileDownload($modelId)
    {
        if (!$modelId) {
            return response('File not found', 404);        }

        $model = BatchReport::find($modelId);

        if (!$model) {
            return response('File not found', 404);
        }

        $document = $model->document;
        $filePath = public_path('batch_report/' . $document);
        if (!$document) {
            return redirect()->back()->with('error', 'File not found for this report!');
        }
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found');
        }

        return response()->download($filePath);
    }


}// end TrainingController
