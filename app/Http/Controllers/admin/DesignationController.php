<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Models\Designation;
use App\Models\StateDescription;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Mail, Redirect, Response, Session, URL, View, Validator;
use Illuminate\Http\Request;

/**
 * DesignationController Controller
 *
 * Add your methods in the class below
 *
 */
class DesignationController extends BaseController
{

	public $model		=	'Designation';
	public $sectionName	=	'Designation';
	public $sectionNameSingular	=	'Designation';

	public function __construct()
	{
		parent::__construct();
		View::share('modelName', $this->model);
		View::share('sectionName', $this->sectionName);
		View::share('sectionNameSingular', $this->sectionNameSingular);
	}

	public function index(Request $request)
	{
		$DB							=	Designation::query();
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
						$DB->where("cities.is_active", $fieldValue);
					}
					if ($fieldName == "designations") {
						$DB->where("designations.designation", 'like', '%' . $fieldValue . '%');
					}
				}
				$searchVariable	=	array_merge($searchVariable, array($fieldName => $fieldValue));
			}
		}
		$sortBy 					= 	($request->get('sortBy')) ? $request->get('sortBy') : 'updated_at';
		$order  					= 	($request->get('order')) ? $request->get('order')   : 'DESC';
		$results 					= 	$DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		$complete_string			=	$request->query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string				=	http_build_query($complete_string);
		$results->appends($request->all())->render();
		return  View::make("admin.Designation.index", compact('results', 'searchVariable', 'sortBy', 'order', 'query_string'));
	}


	public function add()
	{
		return view("admin.Designation.add");
	}


	public function save(Request $request)
	{
		$request->replace($this->arrayStripTags($request->all()));
		$data = $request->all();

		$rules = [
			'designation' => "required|unique:designations,designation,{$request->id}",
		];

		$validator = Validator::make($data, $rules);

		if ($validator->fails()) {
			return Redirect::back()
				->withErrors($validator)
				->withInput();
		}

		$obj = Designation::updateOrCreate(
			['id' => $request->id],
			[
				'designation' => $request->designation,
				'is_active' => $request->status,
			]
		);

		if (!$obj) {
			Session::flash('error', __(config('constants.REC_ADD_FAILED')));
			return redirect()->route('Designation.add');
		} else {
			$message = $request->id ? __(config('constants.REC_UPDATE_SUCCESS'), ['section' => $this->sectionNameSingular])
				: __(config('constants.REC_ADD_SUCCESS'), ['section' => $this->sectionNameSingular]);
			Session::flash('success', $message);
		}
		return redirect()->route('Designation.index');
	}



	public function changeStatus($modelId = 0, $status = 0)
	{
		if ($status == 0) {
			$statusMessage	=	trans($this->sectionNameSingular . " has been deactivated successfully");
		} else {
			$statusMessage	=	trans($this->sectionNameSingular . " has been activated successfully");
		}

		Designation::where('id', $modelId)->update(array('is_active' => $status));
		Session::flash('flash_notice', $statusMessage);
		return Redirect::back();
	}

	public function edit($modelId = 0)
	{
		$model				=	Designation::find($modelId);
		if (empty($model)) {
			return Redirect::route($this->model . ".index");
		}
		return view("admin.Designation.add", compact('model'));
	}

	public function delete($id = 0)
	{
		$model	=	Lob::find($id);
		if (empty($model)) {
			return Redirect::back();
		}
		if ($id) {
			Designation::where('id', $id)->delete();
			Session::flash('flash_notice', trans($this->sectionNameSingular . " has been removed successfully"));
		}
		return Redirect::back();
	}
}
