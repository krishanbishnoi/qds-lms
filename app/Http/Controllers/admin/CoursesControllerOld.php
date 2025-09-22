<?php

namespace App\Http\Controllers\admin;

use App\Exports\exportParticipants;
use App\Http\Controllers\BaseController;
use App\Imports\importParticipants;
use App\Model\Course;
use App\Model\Test;
use App\Model\Training;
use App\Model\TrainingDocument;
use App\Model\User;
use App\Services\OfficeFileConversion;
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

class CoursesController extends BaseController
{
    public $model = 'Course';

    public $sectionName = 'Course';

    public $sectionNameSingular = 'Course';

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
    public function index($training_id = 0)
    {
        $DB = Course::query()->where('training_id', $training_id);

        if (Auth::user()->user_role_id == 2) {
            $DB->where('user_id', Auth::user()->id);
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
                        $DB->where('courses.is_active', $fieldValue);
                    }
                    if ($fieldName == 'title') {
                        $DB->where('courses.title', 'like', '%' . $fieldValue . '%');
                    }
                }
                $searchVariable = array_merge($searchVariable, [$fieldName => $fieldValue]);
            }
        }
        $DB->leftJoin('trainings', 'trainings.id', '=', 'courses.training_id')->select('courses.*', 'trainings.title as training_id');
        $sortBy = (Request::get('sortBy')) ? Request::get('sortBy') : 'updated_at';
        $order = (Request::get('order')) ? Request::get('order') : 'DESC';
        $results = $DB->orderBy($sortBy, $order)->paginate(Config::get('Reading.records_per_page'));
        $complete_string = Request::query();
        unset($complete_string['sortBy']);
        unset($complete_string['order']);
        $query_string = http_build_query($complete_string);
        $results->appends(Request::all())->render();
        //     echo '<pre>'; print_r($results); die;
        session(['filteredResult' => $results]);

        return View::make("admin.$this->model.index", compact('results', 'searchVariable', 'sortBy', 'order', 'query_string', 'training_id'));
    }

    /**
     * Function for add new State
     *
     * @param null
     * @return view page.
     */
    public function add($training_id = 0)
    {
        $test = Test::where('type', 'training_test')->pluck('title', 'id')->toArray();
        // $CourseType = CourseType::pluck('type','id')->toArray();
        $trainees = User::where('is_deleted', 0)->pluck('first_name', 'id')->toArray();

        return View::make("admin.$this->model.add", compact('trainees', 'test', 'training_id'));
    } // end add()

    /**
     * Function for save new Area
     *
     * @param null
     * @return redirect page.
     */
    // public function save()
    // {
    //     $filePath = 'C:\wamp64\www\airtellms\public\training_document\SEP2024\1726891410-document.pptx';

    //     $converter = new OfficeConverter($filePath);
    //     $converter->convertTo('converted-file.pdf'); // Generates 'converted-file.pdf' in the same directory
    // }
    public function uploadPdf(Request $request)
    {
        if ($request->hasFile('pdfFile')) {
            // Get the uploaded PDF file
            $pdfFile = $request->file('pdfFile');

            // Define the storage path
            $fileName = 'presentation-' . time() . '.pdf';
            $destinationPath = public_path('training_document/SEP2024/');

            // Move the file to the public folder
            $pdfFile->move($destinationPath, $fileName);

            return response()->json(['success' => true, 'file' => $fileName]);
        }

        return response()->json(['success' => false, 'message' => 'No file uploaded']);
    }

    public function convertPptToPdf($file)
    {
        $libreofficePath = 'C:\Program Files\LibreOffice\program\soffice.exe';

        // Store the uploaded PPT file in the 'training_document' folder under 'public'
        $filename = time() . '-document.' . $file->getClientOriginalExtension();
        $folderName = strtoupper(date('M') . date('Y')) . '/';
        $folderPath = public_path('training_document/' . $folderName);

        // Ensure the directory exists
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0777, true, true);
        }

        // Move the PPT file to the public folder
        $inputFilePath = $folderPath . $filename;
        $file->move($folderPath, $filename);

        // Define the output PDF file path
        $pdfFilename = pathinfo($filename, PATHINFO_FILENAME) . '.pdf';
        $pdfFilePath = $folderPath . $pdfFilename;

        // Convert PPT to PDF using LibreOffice's soffice command
        $command = "\"$libreofficePath\" --headless --convert-to pdf --outdir \"$folderPath\" \"$inputFilePath\"";

        shell_exec($command);
        // Check if the PDF was generated successfully
        if (File::exists($pdfFilePath)) {
            // Delete the original PPT file after successful PDF conversion
            if (File::exists($inputFilePath)) {
                File::delete($inputFilePath);
            }

            // Return the path of the converted PDF for database storage
            return $folderName . $pdfFilename;
        } else {
            return null; // Return null if conversion fails
        }
    }

    public function save($training_id = 0)
    {
        Request::replace($this->arrayStripTags(Request::all()));
        $thisData = Request::all();
        // echo '<pre>'; print_r($thisData); die;

        $validator = Validator::make(
            $thisData,
            [
                // 'test_id'             => 'required',
                'title' => 'required',
                // 'trainees'             => 'required',
                'skip' => 'required',
                // 'document'             => 'required',
                'start_date_time' => 'required',
                'end_date_time' => 'required',
                // 'thumbnail' => 'required',

            ]
        );

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)->withInput();
        } else {
            $obj = new Course;
            $obj->title = Request::get('title');
            $obj->skip = Request::get('skip');
            $obj->test_id = Request::get('test_id');
            $obj->training_id = Request::get('training_id');
            $obj->start_date_time = Request::get('start_date_time');
            $obj->end_date_time = Request::get('end_date_time');
            $obj->description = Request::get('description');
            $obj->save();
            $course_id = $obj->id;

            if ($course_id) {
                if (isset($thisData['data']) && !empty($thisData['data'])) {

                    foreach ($thisData['data'] as $training_documents) {
                        $obj = new TrainingDocument;
                        $obj->course_id = $course_id;
                        $obj->length = $training_documents['length'];
                        if (isset($training_documents['title']) && !empty($training_documents['title'])) {
                            $title = $training_documents['title'];
                            $obj->title = $title;
                        }
                        if (isset($training_documents['document']) && !empty($training_documents['document'])) {

                            $extension = $training_documents['document']->getClientOriginalExtension();
                            $fileName = time() . '-document.' . $extension;

                            $folderName = strtoupper(date('M') . date('Y')) . '/';
                            $folderPath = config('TRAINING_DOCUMENT_ROOT_PATH') . $folderName;
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
                            $fileExtensions = ['doc', 'docx', 'pdf', 'txt', 'xls', 'xlsx', 'ppt', 'pptx', 'csv', 'odt'];

                            if (in_array($extension, $imageExtensions)) {
                                $obj->type = 'image';
                            } elseif (in_array($extension, $videoExtensions)) {
                                $obj->type = 'video';
                            } elseif (in_array($extension, $fileExtensions)) {
                                $obj->type = 'doc';
                            }
                            if (in_array($extension, ['doc', 'docx', 'xls', 'xlsx'])) {
                                $pdfFileName = time() . '-converted.pdf';
                                $pdfFilePath = $folderPath . $pdfFileName;
                                $filePath = $folderPath . $fileName; // This is the path of the uploaded file

                                // Convert the file to PDF
                                (new OfficeFileConversion())->convertToPdf('C:\wamp64\www\airtellms\public/training_document/SEP2024/1727173735-document.pptx', $pdfFilePath);

                                // Update the database record to point to the new PDF
                                $obj->document = $folderName . $pdfFileName;
                                $obj->document_type = 'pdf';
                            }
                            if (in_array($extension, ['ppt', 'pptx'])) {
                                // Convert PPT to PDF and get the PDF path
                                $pdfPath = $this->convertPptToPdf($training_documents['document']);
                                if ($pdfPath) {
                                    $obj->document = $pdfPath; // Save the PDF path in the database
                                    $obj->document_type = 'pdf'; // Set document type to PDF
                                }
                            }
                        }
                        $obj->save();

                    }
                }
            }
            if (!$obj->save()) {

                Session::flash('error', trans('Something went wrong.'));

                return Redirect::route($this->model . '.index', $training_id);
            } else {
                Session::flash('success', trans($this->sectionNameSingular . ' has been added successfully'));

                return Redirect::route($this->model . '.index', $training_id);
            }
        }
    }
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

        Course::where('id', $modelId)->update(['is_active' => $status]);
        Session::flash('flash_notice', $statusMessage);

        return Redirect::back();
    } // end changeStatus()

    /**
     * Function for display page for edit area
     *
     * @param  $modelId  id  of area
     * @return view page.
     */
    public function edit($training_id, $modelId)
    {

        $training = Training::find($training_id);

        if (empty($training)) {
            return Redirect::route('Training.index');
        }
        $model = Course::find($modelId);
        if (empty($model)) {
            return Redirect::route($this->model . '.index');
        }
        $test = Test::where('type', 'training_test')->pluck('title', 'id')->toArray();

        $documents = DB::table('training_documents')->where('course_id', $modelId)->get();

        return View::make("admin.$this->model.edit", compact('model', 'documents', 'test', 'training_id'));
    } // end edit()

    /**
     * Function for update area
     *
     * @param  $modelId  as id of area
     * @return redirect page.
     */
    public function update($training_id, $modelId)
    {

        $model = Course::findorFail($modelId);
        if (empty($model)) {
            return Redirect::back();
        }

        Request::replace($this->arrayStripTags(Request::all()));
        $thisData = Request::all();
        // echo '<pre>'; print_r($thisData); die;

        $validator = Validator::make(
            $thisData,
            [

                'title' => 'required',
                // 'trainees'             => 'required',
                'skip' => 'required',
                // 'document'             => 'required',
                'start_date_time' => 'required',
                'end_date_time' => 'required',
                //    'thumbnail'             => 'required',

                //'description'         => 'required',
            ]
        );

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)->withInput();
        } else {
            $obj = $model;
            // $obj->type                   = Request::get('type');
            // $obj->user_id                   = Auth::user()->id;
            $obj->title = Request::get('title');
            $obj->skip = Request::get('skip');
            $obj->test_id = Request::get('test_id');
            $obj->training_id = Request::get('training_id');
            $obj->start_date_time = Request::get('start_date_time');
            $obj->end_date_time = Request::get('end_date_time');
            $obj->description = Request::get('description');
            // if (Request::hasFile('thumbnail')) {
            //     $extension = Request::file('thumbnail')->getClientOriginalExtension();
            //     $fileName = time() . '-thumbnail.' . $extension;

            //     $folderName = strtoupper(date('M') . date('Y')) . '/';
            //     $folderPath = config('TRAINING_DOCUMENT_ROOT_PATH') . $folderName;
            //     if (!File::exists($folderPath)) {
            //         File::makeDirectory($folderPath, $mode = 0777, true);
            //     }
            //     if (Request::file('thumbnail')->move($folderPath, $fileName)) {
            //         $obj->thumbnail = $folderName . $fileName;
            //     }
            // }
            $obj->save();
            $modelId = $obj->id;

            // if($training_id){
            //     if(isset($thisData['trainees']) && !empty($thisData['trainees'])) {
            //         TrainingParticipants::where('training_id',$training_id)->delete();
            //         foreach($thisData['trainees'] as $trainee_id) {
            //         //    print_r($trainee_id); die;
            //             $object                 = new TrainingParticipants ;
            //             $object->training_id    = $training_id;
            //             $object->trainee_id    = $trainee_id;
            //             $object->save();
            //         }
            //     }
            // }
            if ($modelId) {
                if (isset($thisData['data']) && !empty($thisData['data'])) {
                    foreach ($thisData['data'] as $training_documents) {
                        $obj = TrainingDocument::updateOrCreate(
                            ['course_id' => $modelId, 'title' => $training_documents['title']],
                            [
                                'course_id' => $modelId,
                                'title' => $training_documents['title'],
                            ]
                        );
                        if (isset($training_documents['document']) && !empty($training_documents['document'])) {
                            // Handle document upload and update the relevant fields
                            $extension = $training_documents['document']->getClientOriginalExtension();
                            $fileName = time() . '-document.' . $extension;
                            $folderName = strtoupper(date('M') . date('Y')) . '/';
                            $folderPath = config('TRAINING_DOCUMENT_ROOT_PATH') . $folderName;

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
                            $fileExtensions = ['doc', 'docx', 'pdf', 'txt', 'xls', 'xlsx', 'ppt', 'pptx', 'csv', 'odt'];

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

                Session::flash('error', trans('Something went wrong.'));

                return Redirect::route($this->model . '.index', $training_id);
            } else {
                Session::flash('success', trans($this->sectionNameSingular . ' has been added successfully'));

                return Redirect::route($this->model . '.index', $training_id);
            }
        }
    } // end update()

    /**
     * Function for mark a couse as deleted
     *
     * @param  $userId  as id of couse
     * @return redirect page.
     */
    public function delete($id = 0)
    {
        $model = Course::find($id);
        if (empty($model)) {
            return Redirect::back();
        }
        if ($id) {
            Course::where('id', $id)->delete();
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
            $projectDetailModel = TrainingDocument::where('id', '=', $id)->delete();
            $output = 1;
            //Session::flash('flash_notice',trans("Detail removed successfully"));
        }
        echo $output;
        exit;
    } // end updateProjectStatus()

    public function view($training_id, $modelId)
    {

        $training = Training::find($training_id);
        if (empty($training)) {
            return Redirect::route('Training.index');
        }

        $model = Course::find($modelId);
        if (empty($model)) {
            return Redirect::route($this->model . '.index', $training_id);
        }

        $course_documents = TrainingDocument::where('course_id', $modelId)->get();

        //  echo '<pre>'; print_r($TrainingDocument); die;
        return View::make("admin.$this->model.view", compact('model', 'training_id', 'course_documents', 'training'));
    } // end edit()

    public function exportCourse(Request $request)
    {
        $filteredResult = Session::get('filteredResult');

        $filteredResult = $filteredResult->map(function ($item) {
            $selectedFields = $item->only(['id', 'title', 'created_by', 'type', 'start_date_time', 'end_date_time', 'status', 'description']);
            $selectedFields['description'] = strip_tags($selectedFields['description']);

            if ($selectedFields['status'] == '0') {
                $selectedFields['status'] = 'Upcoming';
            } elseif ($selectedFields['status'] == '1') {
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

        return View::make("admin.$this->model.uploadTrainingParticipants", compact('training_id'));
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
} // end CoursesController
