<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Models\TrainingType;
use App\Models\StateDescription;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Mail, Redirect, Response, Session, URL, View, Validator;
use Illuminate\Http\Request;

/**
 * TrainingTypeController Controller
 *
 * Add your methods in the class below
 *
 */
class TrainingTypeController extends BaseController
{

	public $model		=	'TrainingType';
	public $sectionName	=	'Training Type';
	public $sectionNameSingular	=	'Training Type';

	public function __construct(Request $request)
	{
		parent::__construct();
		View::share('modelName', $this->model);
		View::share('sectionName', $this->sectionName);
		View::share('sectionNameSingular', $this->sectionNameSingular);
	}

	public function index(Request $request)
	{
		$DB							=	TrainingType::query();
		$searchVariable				=	array();
		$inputGet					=	$request->all();
		if (($request->all())) {
			$searchData				=	$request->all();
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
						$DB->where("training_types.is_active", $fieldValue);
					}
					if ($fieldName == "type") {
						$DB->where("training_types.type", 'like', '%' . $fieldValue . '%');
					}
				}
				$searchVariable	=	array_merge($searchVariable, array($fieldName => $fieldValue));
			}
		}
		//$DB->where("areas.is_deleted",0);
		$sortBy 					= 	($request->get('sortBy')) ? $request->get('sortBy') : 'updated_at';
		$order  					= 	($request->get('order')) ? $request->get('order')   : 'DESC';
		$results 					= 	$DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		$complete_string			=	$request->query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string				=	http_build_query($complete_string);
		$results->appends($request->all())->render();
		return view("admin.TrainingType.index", compact('results', 'searchVariable', 'sortBy', 'order', 'query_string'));
	}


	public function add()
	{
		return view("admin.TrainingType.add");
	} // end add()

	function save(Request $request)
	{
		$request->replace($this->arrayStripTags($request->all()));

		$rules = [
			'type' => "required|unique:training_types,type,{$request->id}",
			'is_active' => 'required',
		];

		$validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput();
		}
		$trainingType = TrainingType::updateOrCreate(
			['id' => $request->id],
			[
				'type' => $request->type,
				'is_active' => $request->is_active
			]
		);

		if (!$trainingType) {
			Session::flash('error', __(config('constants.REC_ADD_FAILED')));
			return redirect()->route('TrainingType.index');
		} else {
			$message = $request->id ? __(config('constants.REC_UPDATE_SUCCESS'), ['section' => $this->sectionNameSingular])
				: __(config('constants.REC_ADD_SUCCESS'), ['section' => $this->sectionNameSingular]);
			Session::flash('success', $message);
		}
		return redirect()->route('TrainingType.index');
	}

	public function changeStatus($modelId = 0, $status = 0)
	{
		if ($status == 0) {
			$statusMessage	=	trans($this->sectionNameSingular . " has been deactivated successfully");
		} else {
			$statusMessage	=	trans($this->sectionNameSingular . " has been activated successfully");
		}

		TrainingType::where('id', $modelId)->update(array('is_active' => $status));
		Session::flash('flash_notice', $statusMessage);
		return Redirect::back();
	}

	public function edit($modelId = 0)
	{
		$model				=	TrainingType::find($modelId);
		if (empty($model)) {
			Session::flash('error', __(config('constants.REC_NOT_FOUND')));
			return redirect()->route($this->model . ".index");
		}
		return  View::make("admin.TrainingType.add", compact('model'));
	}

	public function delete($id = 0)
	{
		$model	=	TrainingType::find($id);
		if (empty($model)) {
			return Redirect::back();
		}
		if ($id) {
			TrainingType::where('id', $id)->delete();
			Session::flash('flash_notice', trans($this->sectionNameSingular . " has been removed successfully"));
		}
		return Redirect::back();
	}
}
