<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Models\Question;
use App\Models\QuestionAttribute;
use App\Models\Test;
use App\Imports\importQuestions;
use Maatwebsite\Excel\Facades\Excel;

use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, Redirect, Request, Response, Session, URL, View, Validator;

/**
 * QuestionController Controller
 *
 * Add your methods in the class below
 *
 */
class QuestionController extends BaseController
{

    public $model        =    'Question';
    public $sectionName    =    'Question';
    public $sectionNameSingular    =    'Question';

    public function __construct()
    {
        parent::__construct();
        View::share('modelName', $this->model);
        View::share('sectionName', $this->sectionName);
        View::share('sectionNameSingular', $this->sectionNameSingular);
    }

    /**
     * Function for display all course modules
     *
     * @param null
     *
     * @return view page.
     */
    public function index($test_id = 0)
    {
        // $model				=	Question::find($test_id);
        // if(empty($model)) {
        // 	return Redirect::route("Categories.index");
        // }

        $DB                    =    Question::query();
        $searchVariable        =    array();
        $inputGet            =    Request::all();
        if ((Request::all())) {
            $searchData            =    Request::all();
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
                    if ($fieldName == "question") {
                        $DB->where("questions.question", 'like', '%' . $fieldValue . '%');
                    }
                    if ($fieldName == "is_active") {
                        $DB->where("questions.is_active", $fieldValue);
                    }
                }
                $searchVariable    =    array_merge($searchVariable, array($fieldName => $fieldValue));
            }
        }
        $DB->where("questions.test_id", $test_id)->where("questions.is_deleted", 0);
        $sortBy = (Request::get('sortBy')) ? Request::get('sortBy') : 'created_at';
        $order  = (Request::get('order')) ? Request::get('order')   : 'DESC';
        $results = $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
        $complete_string        =    Request::query();
        unset($complete_string["sortBy"]);
        unset($complete_string["order"]);
        $query_string            =    http_build_query($complete_string);
        $results->appends(Request::all())->render();
        return  View::make("admin.$this->model.index", compact('results', 'searchVariable', 'sortBy', 'order', 'query_string', 'test_id'));
    } // end index()


    /**
     * Function for add new course Question
     *
     * @param null
     *
     * @return view page.
     */
    public function add($test_id)
    {
        $model                =    Test::find($test_id);
        if (empty($model)) {
            return Redirect::route("Test.index");
        }


        return  View::make("admin.$this->model.add", compact("test_id"));
    } // end add()

    /**
     * Function for save new Area
     *
     * @param null
     *
     * @return redirect page.
     */
    function save($test_id)
    {
        $model                =    Test::find($test_id);
        if (empty($model)) {
            return Redirect::route("Test.index");
        }

        Request::replace($this->arrayStripTags(Request::all()));
        $thisData                    =    Request::all();
        //  echo '<pre>'; print_r($thisData); die;

        $validator = Validator::make(
            $thisData,
            array(
                'question'             => 'required',
                'question_type'             => 'required',
                'count'             => 'required',
                'marks'             => 'required',
                // 'time_limit' 			=> 'required',
                //'description' 			=> 'required',

            )
        );

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)->withInput();
        } else {
            $obj = new Question;
            $obj->test_id             = $test_id;
            $obj->question             = Request::get('question');
            $obj->question_type      = Request::get('question_type');
            $obj->count               = Request::get('count');
            $obj->marks               = Request::get('marks');
            // $obj->time_limit	   	= Request::get('time_limit');
            $obj->description       = Request::get('description');


            $obj->save();
            $question_id                    =    $obj->id;

            if ($question_id) {
                if (isset($thisData['data']) && !empty($thisData['data'])) {
                    if (isset($thisData['data']) && !empty($thisData['data'])) {
                        foreach ($thisData['data'] as $option) {
                            if (isset($option['option']) && !empty($option['option'])) {
                                $obj = new QuestionAttribute;
                                $obj->question_id = $question_id;
                                $optionValue = $option["option"];
                                $obj->option = $optionValue;

                                $isCorrect = isset($option['right_answer']) && !empty($option['right_answer']) && $option['right_answer'] == 1 ? 1 : 0;
                                $obj->is_correct = $isCorrect;

                                $obj->save();
                            }
                        }
                    }
                }
            }
            if (!$obj->save()) {

                Session::flash('error', trans("Something went wrong."));
                return Redirect::route($this->model . ".index", $test_id);
            } else {
                Session::flash('success', trans($this->sectionNameSingular . " has been added successfully"));
                return Redirect::route($this->model . ".index", $test_id);
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

        Test::where('id', $modelId)->update(array('is_active' => $status));
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
    public function edit($test_id, $modelId)
    {
        $test_id                =    Test::find($test_id);

        if (empty($test_id)) {
            return Redirect::route("Test.index");
        }

        $model                =    Question::find($modelId);
        if (empty($model)) {
            return Redirect::route($this->model . ".index");
        }

        $attribute =  DB::table('question_attributes')->where('question_id', $modelId)->get();

        return  View::make("admin.$this->model.edit", compact('model', 'test_id', 'attribute'));
    } // end edit()


    /**
     * Function for display page for edit area
     *
     * @param $modelId id  of area
     *
     * @return view page.
     */
    public function view($test_id, $modelId)
    {
        $test_id                =    Test::find($test_id);

        if (empty($test_id)) {
            return Redirect::route("Test.index");
        }

        $model                =    Question::find($modelId);
        if (empty($model)) {
            return Redirect::route($this->model . ".index");
        }

        $attribute =  DB::table('question_attributes')->where('question_id', $modelId)->get();

        return  View::make("admin.$this->model.view", compact('model', 'test_id', 'attribute'));
    } // end edit()

    /**
     * Function for update area
     *
     * @param $modelId as id of area
     *
     * @return redirect page.
     */
    function update($test_id, $modelId)
    {

        $test_id                =    Test::find($test_id);
        if (empty($test_id)) {
            return Redirect::route("Test.index");
        }

        $model                    =    Question::findorFail($modelId);
        if (empty($model)) {
            return Redirect::back();
        }


        Request::replace($this->arrayStripTags(Request::all()));
        $thisData                    =    Request::all();
        // dd($thisData) 
        $validator = Validator::make(
            $thisData,
            array(
                'question'             => 'required',
                'question_type'             => 'required',
                'count'             => 'required',
                'marks'             => 'required',
                // 'time_limit' 			=> 'required',
                //'description' 			=> 'required',
            )
        );

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)->withInput();
        } else {

            $obj = $model;
            // $obj->test_id 		=  $test_id;
            $obj->question             = Request::get('question');
            $obj->question_type      = Request::get('question_type');
            $obj->count               = Request::get('count');
            $obj->marks               = Request::get('marks');
            // $obj->time_limit	   	= Request::get('time_limit');
            $obj->description       = Request::get('description');

            $obj->save();
            $question_id                    =    $obj->id;

            if ($question_id) {
                if (isset($thisData['data']) && !empty($thisData['data'])) {
                    QuestionAttribute::where('question_id', $question_id)->delete();
                    foreach ($thisData['data'] as $option) {
                        if (isset($option['option']) && !empty($option['option'])) {
                            $obj = new QuestionAttribute;
                            $obj->question_id = $question_id;
                            $optionValue = $option["option"];
                            $obj->option = $optionValue;

                            $isCorrect = isset($option['right_answer']) && !empty($option['right_answer']) && $option['right_answer'] == 1 ? 1 : 0;
                            $obj->is_correct = $isCorrect;

                            $obj->save();
                        }
                    }
                }
            }
            if (!$obj->save()) {

                Session::flash('error', trans("Something went wrong."));
                return Redirect::route($this->model . ".index", $test_id);
            } else {


                Session::flash('success', trans($this->sectionNameSingular . " has been Updated successfully"));
                return Redirect::route($this->model . ".index", $test_id);
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
    function delete($modelId)
    {

        $model                    =    Question::findorFail($modelId);
        if (empty($model)) {
            return Redirect::back();
        }

        if ($modelId) {
            Question::where('id', $modelId)->delete();
            QuestionAttribute::where('question_id', $modelId)->delete();
            Session::flash('flash_notice', trans($this->sectionNameSingular . " has been removed successfully"));
        }
        return Redirect::back();
    } // end delete()


    public function addMoreOption()
    {
        $offset = $_POST['offset'];
        return  View::make("admin.$this->model.addMoreDetails", compact('offset', 'offset'));
    } // end updateProjectStatus()

    public function deleteMoreOption()
    {
        $id = $_POST['id'];
        $output = 0;
        if ($id) {
            $projectDetailModel                    =    QuestionAttribute::where('id', '=', $id)->delete();
            $output = 1;
            //Session::flash('flash_notice',trans("Detail removed successfully"));
        }
        echo $output;
        die;
    } // end updateProjectStatus()
    public function downloadQuestionSample()
    {
        $filePath = public_path('sample-files/question-upload-format.xlsx');
        if (file_exists($filePath)) {
            return response()->download($filePath, 'questions-sample.xlsx');
        } else {
            abort(404, 'Sample file not found.');
        }
    }

    public function importQuestions($test_id)
    {
        // dd($test_id);
        $import = new importQuestions($test_id);
        Excel::import($import, request()->file('file'));
        $errors = $import->getErrors();

        if (count($errors) > 0) {
            $errorMessages = implode('<BR>', $errors);
            return redirect()->back()->with('error', $errorMessages);
        }

        return redirect()->back()->with('success', 'Questions imported successfully!');
    }
}	// end QuestionController
