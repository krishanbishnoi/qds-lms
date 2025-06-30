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
use App\Models\ManagerTrainings;
use App\Models\Question;
use App\Models\QuestionAttribute;
use App\Models\TrainingParticipants;
use App\Models\Test;
use App\Models\StateDescription;
use App\Models\TestCategory;
use App\Models\TrainerTrainings;
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
            // $test_id = '376';
            // $DB                    =    Question::query();
            // $results = $DB->orderBy('created_at', 'DESC')->paginate(Config::get("Reading.records_per_page"));
            // $model                =    Test::find($test_id);
            $test = Test::where('type', 'training_test')->pluck('title', 'id')->toArray();
            // $CourseType = CourseType::pluck('type','id')->toArray();
            $trainees = User::where("is_deleted", 0)->pluck('first_name', 'id')->toArray();
            return  view("admin.Course.add", compact('trainees', 'test',  'training_id'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'somthing went wrong');;
        }
    }

    public function save(Request $request, $training_id = 0)
    {
        // dd($request->all());
        $step = $request->input('current_step', 1);
        $trainingId = $request->input('training_id');
        $request->replace($this->arrayStripTags($request->all()));

        try {
            if ($step == 1) {
                $validated = $request->validate([
                    'title' => 'required',
                    'description' => 'required',
                ]);

                $courseData = [
                    'title' => $request->title,
                    'skip' => $request->skip,
                    'test_id' => $request->filled('test_id') ? $request->test_id : null,
                    'training_id' => $request->training_id,
                    'start_date_time' => $request->start_date_time,
                    'end_date_time' => $request->end_date_time,
                    'description' => $request->description,
                ];

                $course = Course::updateOrCreate(
                    ['id' => $request->id],
                    $courseData
                );

                if (!$course) {
                    return redirect()->route("Course.index", $trainingId)
                        ->with('error', trans("Something went wrong."));
                }

                if ($request->filled('data')) {
                    // First get existing documents for this course
                    $existingDocuments = TrainingDocument::where('course_id', $course->id)->get();

                    foreach ($request->data as $documentData) {
                        $validator = Validator::make($documentData, [
                            'title' => 'required|string|max:255',
                            'length' => 'required|numeric|min:1',
                            // 'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,ppt,pptx,mp4,mov,avi|max:20480',
                        ]);

                        if ($validator->fails()) {
                            return redirect()->back()
                                ->withErrors($validator)
                                ->withInput();
                        }
                        $existingDocument = null;

                        if (isset($documentData['entryID'])) {
                            $existingDocument = $existingDocuments->where('id', $documentData['entryID'])->first();
                        }

                        $document = [
                            'course_id' => $course->id,
                            'title' => $documentData['title'] ?? null,
                            'length' => isset($documentData['length']) ? ((int) $documentData['length']) * 60 : 0,
                        ];

                        if (isset($documentData['document']) && $documentData['document']) {
                            // File uploaded – process new file
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
                            $imageExtensions = [
                                'jpg',
                                'jpeg',
                                'png',
                                'gif',
                                'bmp',
                                'svg',
                                'ico',
                                'webp',
                                'tif',
                                'tiff',
                                'jfif',
                                'avif',
                                'heic',
                                'raw',
                                'psd',
                                'ai',
                                'eps'
                            ];

                            $videoExtensions = [
                                'mp4',
                                'm4v',
                                'mov',
                                'avi',
                                'wmv',
                                'flv',
                                'mkv',
                                'webm',
                                'mpeg',
                                'mpg',
                                '3gp',
                                '3g2',
                                'ts',
                                'mts',
                                'm2ts'
                            ];

                            $fileExtensions = [
                                'pdf',
                                'doc',
                                'docx',
                                'xls',
                                'xlsx',
                                'ppt',
                                'pptx',
                                'txt',
                                'csv',
                                'rtf',
                                'odt',
                                'ods',
                                'odp',
                                'pages',
                                'key',
                                'numbers',
                                'epub',
                                'md',
                                'log',
                                'tex',
                                'json',
                                'xml',
                                'yml',
                                'yaml'
                            ];

                            if (in_array($extension, $imageExtensions)) {
                                $document['type'] = 'image';
                            } elseif (in_array($extension, $videoExtensions)) {
                                $document['type'] = 'video';
                            } elseif (in_array($extension, $fileExtensions)) {
                                $document['type'] = 'doc';
                            } else {
                                $document['type'] = 'other';
                            }
                        } elseif ($existingDocument) {
                            // ✅ No new file uploaded — keep existing file info
                            $document['document'] = $existingDocument->document;
                            $document['document_type'] = $existingDocument->document_type;
                            $document['type'] = $existingDocument->type;
                        }

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

                Session::put('course_id', $course->id);


                if ($request->add_test_option == 'existing') {
                    return redirect()->route('Course.index', $trainingId);
                }
                return response()->json(['success' => true]);
            } elseif ($step == 2) {
                $validated = $request->validate([
                    // 'category_id' => 'required',
                    'title' => 'required',
                    // 'type' => 'required',
                    'minimum_marks' => 'required',
                    'number_of_attempts' => 'required',
                    // 'time_of_test' => 'required',
                    'start_date_time' => 'required',
                    'end_date_time' => 'required',
                    'thumbnail' => $request->id ? 'nullable' : 'required',
                    'publish_result' => 'required',
                ]);

                $data = [
                    'category_id' => $request->category_id,
                    'title' => $request->title,
                    'type' => 'training_test',
                    'minimum_marks' => $request->minimum_marks,
                    'user_id' => Auth::id(),
                    'number_of_attempts' => $request->number_of_attempts,
                    'time_of_test' => $request->time_of_test,
                    'number_of_questions' => $request->number_of_questions ?? '0',
                    'region' => $request->region,
                    'circle' => $request->circle,
                    'lob' => $request->lob,
                    'start_date_time' => $request->start_date_time,
                    'end_date_time' => $request->end_date_time,
                    'publish_result' => $request->publish_result,
                    'description' => $request->description,
                ];

                // Handle file upload if exists
                if ($request->hasFile('thumbnail')) {
                    $extension = $request->file('thumbnail')->getClientOriginalExtension();
                    $fileName = time() . '-thumbnail.' . $extension;

                    $folderName = strtoupper(date('M') . date('Y')) . "/";
                    $folderPath = TRAINING_DOCUMENT_ROOT_PATH . $folderName;
                    if (!File::exists($folderPath)) {
                        File::makeDirectory($folderPath, 0777, true);
                    }
                    if ($request->file('thumbnail')->move($folderPath, $fileName)) {
                        $data['thumbnail'] = $folderName . $fileName;
                    }
                }

                $test = Test::updateOrCreate(
                    ['id' => $request->id],
                    $data
                );

                $courseId = Session::get('course_id');
                $course = Course::where('id', $courseId)->first();

                if ($course) {
                    $course->test_id = $test->id;
                    $course->save();
                }

                $test_id = $test->id;
                Session::put('current_test_id', $test_id);

                if ($test_id) {
                    if (isset($thisData['training_manager']) && !empty($thisData['training_manager'])) {
                        ManagerTrainings::where('test_id', $test_id)->delete();
                        foreach ($thisData['training_manager'] as $user_id) {
                            $object = new ManagerTrainings;
                            $object->test_id = $test_id;
                            $object->user_id = $user_id;
                            $object->save();
                        }
                    } else {
                        ManagerTrainings::where('test_id', $test_id)->delete();
                    }

                    if (isset($thisData['training_trainer']) && !empty($thisData['training_trainer'])) {
                        TrainerTrainings::where('test_id', $test_id)->delete();
                        foreach ($thisData['training_trainer'] as $user_id) {
                            $object = new TrainerTrainings;
                            $object->test_id = $test_id;
                            $object->user_id = $user_id;
                            $object->save();
                        }
                    } else {
                        TrainerTrainings::where('test_id', $test_id)->delete();
                    }
                }


                return response()->json([
                    'success' => true,
                    'test_id' => $test->id
                ]);
            } elseif ($step == 3) {
                $test_id = $request->input('test_id') ?? Session::get('current_test_id');

                if (!$test_id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Test ID not provided'
                    ], 404);
                }

                $test = Test::find($test_id);

                if (empty($test)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Test not found'
                    ], 404);
                }

                // For AJAX requests (when saving a question)
                if ($request->wantsJson()) {
                    $request->replace($this->arrayStripTags($request->all()));
                    $data = $request->all();

                    $validator = Validator::make($data, [
                        'question' => 'required',
                        'question_type' => 'required',
                        'marks' => 'required',
                    ]);

                    if ($validator->fails()) {
                        return response()->json([
                            'success' => false,
                            'errors' => $validator->errors()
                        ], 422);
                    }

                    $question = Question::updateOrCreate(
                        ['id' => $request->id],
                        [
                            'test_id' => $test_id,
                            'question' => $request->question,
                            'question_type' => $request->question_type,
                            'count' => $request->count,
                            'marks' => $request->marks,
                            'description' => $request->description,
                        ]
                    );

                    // Handle question attributes (keep your existing code)
                    if (!empty($data['data'])) {
                        QuestionAttribute::where('question_id', $question->id)->delete();

                        if (in_array($request->question_type, ['SCQ', 'T/F'])) {
                            $selectedIndex = $request->input('data_right_answer');
                            foreach ($data['data'] as $index => $option) {
                                if (!empty($option['option'])) {
                                    QuestionAttribute::create([
                                        'question_id' => $question->id,
                                        'option' => $option['option'],
                                        'is_correct' => ($selectedIndex == $index) ? 1 : 0,
                                    ]);
                                }
                            }
                        } else {
                            foreach ($data['data'] as $option) {
                                if (!empty($option['option'])) {
                                    QuestionAttribute::create([
                                        'question_id' => $question->id,
                                        'option' => $option['option'],
                                        'is_correct' => (isset($option['right_answer']) && $option['right_answer']) ? 1 : 0,
                                    ]);
                                }
                            }
                        }
                    }

                    return response()->json(['success' => true]);
                }

                // For page reload (non-AJAX)
                // Session::put('current_test_id', $test_id);
                // $questions = Question::where('test_id', $test_id)->get();

                // return view('your.main.view', [
                //     'current_step' => 3,
                //     'test_id' => $test_id,
                //     'questions' => $questions
                // ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
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

            return  view("admin.Course.edit", compact('model', 'documents', 'test', 'training_id'));
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
