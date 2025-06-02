<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Facades\View;

use App\Http\Controllers\BaseController;
use App\Models\Course;
use App\Models\Training;
use App\Models\User;
use App\Imports\importParticipants;
use App\Exports\exportParticipants;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\TrainingDocument;
use App\Models\CourseType;
use App\Models\TrainingParticipants;
use App\Models\Test;
use App\Models\StateDescription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

/**
 * CoursesController Controller
 *
 * Add your methods in the class below
 *
 */
class CoursesController extends BaseController
{

    public $model        =    'Course';
    public $sectionName    =    'Courses';
    public $sectionNameSingular    =    'Course';

    public function __construct()
    {
        parent::__construct();
        View::share('modelName', $this->model);
        View::share('sectionName', $this->sectionName);
        View::share('sectionNameSingular', $this->sectionNameSingular);
    }

    public function index(Request $request, $training_id = 0)
    {
        try {
            $DB = Course::query()->where('training_id', $training_id);
            $trainingName = Training::where('id', $training_id)->value('title');
            if (Auth::user()->user_role_id == TRAINER_ROLE_ID) {
                $DB->where('user_id', Auth::user()->id);
            }

            $searchVariable = [];
            $inputGet = $request->all();

            if ($inputGet) {
                $searchData = $inputGet;

                unset(
                    $searchData['display'],
                    $searchData['_token'],
                    $searchData['order'],
                    $searchData['sortBy'],
                    $searchData['page']
                );

                foreach ($searchData as $fieldName => $fieldValue) {
                    if ($fieldValue !== "") {
                        if ($fieldName === "is_active") {
                            $DB->where("courses.is_active", $fieldValue);
                        } elseif ($fieldName === "title") {
                            $DB->where("courses.title", 'like', '%' . $fieldValue . '%');
                        }
                        $searchVariable[$fieldName] = $fieldValue;
                    }
                }
            }

            $DB->leftJoin('trainings', 'trainings.id', '=', 'courses.training_id')
                ->select('courses.*', 'trainings.title as training_id');

            $sortBy = $request->get('sortBy', 'updated_at');
            $order = $request->get('order', 'DESC');

            $results = $DB->orderBy($sortBy, $order)
                ->paginate(Config::get("Reading.records_per_page"));

            $complete_string = $request->query();
            unset($complete_string["sortBy"], $complete_string["order"]);
            $query_string = http_build_query($complete_string);

            $results->appends($inputGet)->render();
            //	 echo '<pre>'; print_r($results); die;

            session(['filteredResult' => $results]);

            return view("admin.Course.index", compact(
                'results',
                'searchVariable',
                'sortBy',
                'order',
                'query_string',
                'training_id',
                'trainingName'
            ));
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'somthing went wrong');;
        }
    }
    public function add($training_id = 0)
    {
        try {
            $test = Test::where('type', 'training_test')->pluck('title', 'id')->toArray();
            // $CourseType = CourseType::pluck('type','id')->toArray();
            $trainees = User::where("is_deleted", 0)->pluck('first_name', 'id')->toArray();
            return  view("admin.Course.add", compact('trainees', 'test', 'training_id'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'somthing went wrong');;
        }
    }

    public function save(Request $request, $training_id = 0)
    {
        try {
            // Sanitize input data
            $request->replace($this->arrayStripTags($request->all()));

            // Validation rules
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'skip' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Handle thumbnail upload
            $fileName = null;
            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail if exists
                if ($request->id) {  // Changed from $modelId to $request->id
                    $oldCourse = Course::find($request->id);
                    if ($oldCourse && $oldCourse->thumbnail) {
                        $oldThumbnailPath = TRAINING_DOCUMENT_ROOT_PATH . $oldCourse->thumbnail;
                        if (File::exists($oldThumbnailPath)) {
                            File::delete($oldThumbnailPath);
                        }
                    }
                }

                $extension = $request->file('thumbnail')->getClientOriginalExtension();
                $fileName = time() . '-thumbnail.' . $extension;
                $folderName = strtoupper(date('M') . date('Y')) . "/";
                $folderPath = TRAINING_DOCUMENT_ROOT_PATH . $folderName;

                if (!File::exists($folderPath)) {
                    File::makeDirectory($folderPath, 0777, true);
                }
                $request->file('thumbnail')->move($folderPath, $fileName);
                $fileName = $folderName . $fileName;
            }

            // Create or update course
            $courseData = [
                'title' => $request->title,
                'skip' => $request->skip,
                'test_id' => $request->filled('test_id') ? $request->test_id : null,
                'training_id' => $request->training_id,
                'start_date_time' => $request->start_date_time,
                'end_date_time' => $request->end_date_time,
                'description' => $request->description,
            ];

            if ($fileName) {
                $courseData['thumbnail'] = $fileName;
            }

            $course = Course::updateOrCreate(
                ['id' => $request->id],  // Changed from $modelId to $request->id
                $courseData
            );
            if (!$course) {
                return redirect()->route("Course.index", $training_id)
                    ->with('error', trans("Something went wrong."));
            }

            // Handle training documents
            if ($request->filled('data')) {
                // First get existing documents for this course
                $existingDocuments = TrainingDocument::where('course_id', $course->id)->get();

                foreach ($request->data as $documentData) {
                    $document = [
                        'course_id' => $course->id,
                        'title' => $documentData['title'] ?? null,
                        'length' => $documentData['length'] ?? null,
                    ];

                    // Check if we have an existing document (for edit)
                    $existingDocument = null;
                    if (isset($documentData['entryID'])) {
                        $existingDocument = $existingDocuments->where('id', $documentData['entryID'])->first();
                    }

                    // Handle file upload if new file is provided
                    if (isset($documentData['document']) && $documentData['document']) {
                        // Delete old file if exists
                        if ($existingDocument && $existingDocument->document) {
                            $oldFilePath = TRAINING_DOCUMENT_ROOT_PATH . $existingDocument->document;
                            if (File::exists($oldFilePath)) {
                                File::delete($oldFilePath);
                            }
                        }

                        $extension = $documentData['document']->getClientOriginalExtension();
                        $docFileName = time() . '-' . uniqid() . '-document.' . $extension;
                        $folderName = strtoupper(date('M') . date('Y')) . "/";
                        $folderPath = TRAINING_DOCUMENT_ROOT_PATH . $folderName;

                        if (!File::exists($folderPath)) {
                            File::makeDirectory($folderPath, 0777, true);
                        }

                        $document['document_type'] = $extension;

                        if ($documentData['document']->move($folderPath, $docFileName)) {
                            $document['document'] = $folderName . $docFileName;
                        }

                        // Determine file type
                        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'ico'];
                        $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'mkv', 'flv', 'mpeg', 'mpg'];
                        $fileExtensions = ['doc', 'pdf', 'txt', 'xls', 'xlsx', 'ppt', 'pptx', 'csv', 'odt'];

                        if (in_array($extension, $imageExtensions)) {
                            $document['type'] = 'image';
                        } elseif (in_array($extension, $videoExtensions)) {
                            $document['type'] = 'video';
                        } elseif (in_array($extension, $fileExtensions)) {
                            $document['type'] = 'doc';
                        }
                    } elseif ($existingDocument) {
                        // Keep the existing file if no new file is uploaded
                        $document['document'] = $existingDocument->document;
                        $document['document_type'] = $existingDocument->document_type;
                        $document['type'] = $existingDocument->type;
                    }

                    // Update or create the document
                    if ($existingDocument) {
                        $existingDocument->update($document);
                    } else {
                        TrainingDocument::create($document);
                    }
                }

                // Delete any documents that were removed from the form
                $submittedDocumentIds = collect($request->data)
                    ->filter(function ($item) {
                        return isset($item['entryID']);
                    })
                    ->pluck('entryID')
                    ->toArray();

                $documentsToDelete = $existingDocuments->whereNotIn('id', $submittedDocumentIds);
                foreach ($documentsToDelete as $docToDelete) {
                    if ($docToDelete->document) {
                        $filePath = TRAINING_DOCUMENT_ROOT_PATH . $docToDelete->document;
                        if (File::exists($filePath)) {
                            File::delete($filePath);
                        }
                    }
                    $docToDelete->delete();
                }
            }

            $message = $request->id  // Changed from $modelId to $request->id
                ? trans($this->sectionNameSingular . " has been updated successfully")
                : trans($this->sectionNameSingular . " has been added successfully");

            return redirect()->route("Course.index", $training_id)
                ->with('success', $message);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()
                ->withInput()
                ->with('error', trans('Something went wrong. Please try again.'));
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

            Course::where('id', $modelId)->update(array('is_active' => $status));
            Session::flash('flash_notice', $statusMessage);
            return Redirect::back();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'somthing went wrong');
        }
    }

    public function edit($training_id, $modelId)
    {

        try {
            $training                =    Training::find($training_id);
            if (empty($training)) {
                return Redirect::route("Training.index");
            }
            $model                =    Course::find($modelId);
            if (empty($model)) {
                return Redirect::route(Course . ".index");
            }
            $test = Test::where('type', 'training_test')->pluck('title', 'id')->toArray();


            $documents =  DB::table('training_documents')->where('course_id', $modelId)->get();

            return  view("admin.Course.add", compact('model', 'documents', 'test', 'training_id'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'somthing went wrong');
        }
    } // end edit()


    /**
     * Function for update area
     *
     * @param $modelId as id of area
     *
     * @return redirect page.
     */
    function update($training_id, $modelId)
    {

        $model                    =    Course::findorFail($modelId);
        if (empty($model)) {
            return Redirect::back();
        }

        Request::replace($this->arrayStripTags(Request::all()));
        $thisData                    =    Request::all();
        // echo '<pre>'; print_r($thisData); die;

        $validator = Validator::make(
            $thisData,
            array(

                'title'             => 'required',
                // 'trainees' 			=> 'required',
                'skip'             => 'required',
                // 'document' 			=> 'required',
                'start_date_time'     => 'required',
                'end_date_time'             => 'required',
                //	'thumbnail' 			=> 'required',

                //'description' 		=> 'required',
            )
        );

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)->withInput();
        } else {
            $obj = $model;
            // $obj->type   				= Request::get('type');
            // $obj->user_id   				= Auth::user()->id;
            $obj->title                 = Request::get('title');
            $obj->skip                   = Request::get('skip');
            $obj->test_id               = Request::filled('test_id') ? Request::get('test_id') : null;
            $obj->training_id             = Request::get('training_id');
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
            $modelId                    =    $obj->id;

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
            if ($modelId) {
                if (isset($thisData['data']) && !empty($thisData['data'])) {
                    foreach ($thisData['data'] as $training_documents) {
                        $obj = TrainingDocument::updateOrCreate(
                            ['course_id' => $modelId, 'title' => $training_documents['title']],
                            [
                                'course_id' => $modelId,
                                'title' => $training_documents['title'],
                                'length' => $training_documents['length'],
                            ]
                        );
                        if (isset($training_documents['document']) && !empty($training_documents['document'])) {
                            // Handle document upload and update the relevant fields
                            $extension  = $training_documents['document']->getClientOriginalExtension();
                            $fileName   = time() . '-document.' . $extension;
                            $folderName     = strtoupper(date('M') . date('Y')) . "/";
                            $folderPath         = TRAINING_DOCUMENT_ROOT_PATH . $folderName;

                            if (!File::exists($folderPath)) {
                                File::makeDirectory($folderPath, $mode = 0777, true);
                            }

                            if (!empty($extension)) {
                                $obj->document_type = $extension;
                            }

                            if ($training_documents['document']->move($folderPath, $fileName)) {
                                $obj->document = $folderName . $fileName;
                            }

                            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'ico'];
                            $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'mkv', 'flv', 'mpeg', 'mpg'];
                            $fileExtensions = ['doc', 'pdf', 'txt', 'xls', 'xlsx', 'ppt', 'pptx', 'csv', 'odt'];

                            if (in_array($extension, $imageExtensions)) {
                                $obj->type = 'image';
                            } elseif (in_array($extension, $videoExtensions)) {
                                $obj->type = 'video';
                            } elseif (in_array($extension, $fileExtensions)) {
                                $obj->type = 'doc';
                            } else {
                                $obj->type = '';
                            }
                        }

                        $obj->save();
                    }
                }
            }
            if (!$obj->save()) {

                Session::flash('error', trans("Something went wrong."));
                return Redirect::route(Course . ".index", $training_id);
            } else {
                Session::flash('success', trans($this->sectionNameSingular . " has been added successfully"));
                return Redirect::route(Course . ".index", $training_id);
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
        $model    =    Course::find($id);
        if (empty($model)) {
            return Redirect::back();
        }
        if ($id) {
            Course::where('id', $id)->delete();
            Session::flash('flash_notice', trans($this->sectionNameSingular . " has been removed successfully"));
        }
        return Redirect::back();
    } // end delete()


    public function addMoreDocument()
    {
        $offset = $_POST['offset'];
        return  view("admin.Course.addMoreDetails", compact('offset', 'offset'));
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

    public function view($training_id, $modelId)
    {

        $training                =    Training::find($training_id);
        if (empty($training)) {
            return Redirect::route("Training.index");
        }

        $model                =    Course::find($modelId);
        if (empty($model)) {
            return Redirect::route(Course . ".index", $training_id);
        }

        $course_documents                =    TrainingDocument::where('course_id', $modelId)->get();

        //  echo '<pre>'; print_r($TrainingDocument); die;
        return  view("admin.Course.view", compact('model', 'training_id', 'course_documents', 'training'));
    } // end edit()

    public function exportCourse(Request $request)
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
        return Excel::download($export, 'Course.xlsx');
    }

    public function importTrainingParticipants($training_id = 0)
    {

        return  view("admin.Course.uploadTrainingParticipants", compact('training_id'));
    } // end add()


    public function importCourse($training_id = 0)
    {
        $import = new importParticipants($training_id);
        Excel::import($import, request()->file('file'));
        $errors = $import->getErrors();

        if (count($errors) > 0) {
            $errorMessages = implode('<BR>', $errors);
            return redirect()->back()->with('error', $errorMessages);
        }

        return redirect()->back()->with('success', 'Course Participants Added Successfully!');
    }
}// end CoursesController
