<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Models\Question;
use App\Models\QuestionAttribute;
use App\Models\Test;
use App\Imports\importQuestions;
use Maatwebsite\Excel\Facades\Excel;

use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, Redirect, Response, Session, URL, View, Validator;
use Illuminate\Http\Request;

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


    public function index(Request $request, $test_id = 0)
    {
        // $model				=	Question::find($test_id);
        // if(empty($model)) {
        // 	return Redirect::route("Categories.index");
        // }

        $DB                    =    Question::query();
        $searchVariable        =    array();
        $inputGet            =    $request->all();
        if (($request->all())) {
            $searchData            =    $request->all();
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
        $sortBy = ($request->get('sortBy')) ? $request->get('sortBy') : 'created_at';
        $order  = ($request->get('order')) ? $request->get('order')   : 'DESC';
        $results = $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
        $complete_string        =    $request->query();
        unset($complete_string["sortBy"]);
        unset($complete_string["order"]);
        $query_string            =    http_build_query($complete_string);
        $results->appends($request->all())->render();
        return view("admin.Question.index", compact('results', 'searchVariable', 'sortBy', 'order', 'query_string', 'test_id'));
    }

    public function add($test_id)
    {
        $model                =    Test::find($test_id);
        if (empty($model)) {
            return Redirect::route("Test.index");
        }

        return view("admin.Question.add", compact("test_id"));
    }

    function save(Request $request, $test_id)
    {
        $test = Test::find($test_id);
        if (empty($test)) {
            return Redirect::route("Test.index");
        }

        $request->replace($this->arrayStripTags($request->all()));
        $data = $request->all();

        $validator = Validator::make($data, [
            'question' => 'required',
            'question_type' => 'required',
            // 'count' => 'required',
            'marks' => 'required',
            // 'time_limit' => 'required',  // Uncomment if needed
            //'description' => 'required', // Uncomment if needed
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        try {
            $question = Question::updateOrCreate(
                ['id' => $request->id],  
                [
                    'test_id' => $test_id,
                    'question' => $request->question,
                    'question_type' => $request->question_type,
                    'count' => $request->count,
                    'marks' => $request->marks,
                    // 'time_limit' => $request->time_limit, // Uncomment if needed
                    'description' => $request->description,
                ]
            );


            if (!empty($data['data'])) {
                // Handle MCQ checkboxes and SCQ/T-F radios differently
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
                    // For MCQ checkboxes
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
            if (!$question) {
                Session::flash('error', __(config('constants.REC_ADD_FAILED')));
            } else {
                $message = $request->id ? __(config('constants.REC_UPDATE_SUCCESS'), ['section' => $this->sectionNameSingular])
                    : __(config('constants.REC_ADD_SUCCESS'), ['section' => $this->sectionNameSingular]);
                Session::flash('success', $message);
            }
            return Redirect::route($this->model . ".index", $test_id);
        } catch (\Exception $e) {
            Session::flash('error', __(config('constants.FLASH_TRY_CATCH')));
            return Redirect::route($this->model . ".index", $test_id);
        }
    }

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
    }

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

        return view("admin.Question.add", compact('model', 'test_id', 'attribute'));
    } // end edit()


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
    }

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
    }

    public function addMoreOption()
    {
        $offset = $_POST['offset'];
        return  View::make("admin.$this->model.addMoreDetails", compact('offset', 'offset'));
    }

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
    }

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
}
