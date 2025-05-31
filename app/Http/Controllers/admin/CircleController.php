<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Models\Circle;
use App\Models\Region;
use App\Models\StateDescription;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Mail, Redirect, Response, Session, URL, View, Validator;
use Illuminate\Http\Request;

class CircleController extends BaseController
{

	public $model		=	'Circle';
	public $sectionName	=	'Circle';
	public $sectionNameSingular	=	'Circle';

	public function __construct()
	{
		parent::__construct();
		View::share('modelName', $this->model);
		View::share('sectionName', $this->sectionName);
		View::share('sectionNameSingular', $this->sectionNameSingular);
	}


	public function index(Request $request)
	{
		$DB							=	Circle::query();
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

					if ($fieldName == "circle") {
						$DB->where("circles.circle", 'like', '%' . $fieldValue . '%');
					}
				}
				$searchVariable	=	array_merge($searchVariable, array($fieldName => $fieldValue));
			}
		}
		$DB->leftJoin('regions', 'regions.id', '=', 'circles.region_id')->select('circles.*', 'regions.region as region_id');
		$sortBy 					= 	($request->get('sortBy')) ? $request->get('sortBy') : 'updated_at';
		$order  					= 	($request->get('order')) ? $request->get('order')   : 'DESC';
		$results 					= 	$DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		$complete_string			=	$request->query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string				=	http_build_query($complete_string);
		$results->appends($request->all())->render();
		//echo '<pre>'; print_r($results); die;
		return  View::make("admin.$this->model.index", compact('results', 'searchVariable', 'sortBy', 'order', 'query_string'));
	}

	public function add()
	{
		$region  = Region::pluck('region', 'id')->toArray();
		return view("admin.Circle.add", compact('region'));
	}

	public function save(Request $request)
	{
		$request->replace($this->arrayStripTags($request->all()));
		$thisData = $request->all();

		$rules = [
			'circle'    => "required|unique:circles,circle," . $request->id,
			'region_id' => 'required',
			'is_active' => 'required',
		];

		$validator = Validator::make($thisData, $rules);
		
		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput();
		}

		// Create or update record
		$circle = Circle::updateOrCreate(
			['id' => $request->id],
			[
				'circle'    => $request->circle,
				'region_id' => $request->region_id,
				'is_active' => $request->is_active,
			]
		);

		if (!$circle) {
			Session::flash('error', __(config('constants.REC_ADD_FAILED')));
			return redirect()->route('Circle.index');
		} else {
			$message = $request->id ? __(config('constants.REC_UPDATE_SUCCESS'), ['section' => $this->sectionNameSingular])
				: __(config('constants.REC_ADD_SUCCESS'), ['section' => $this->sectionNameSingular]);
			Session::flash('success', $message);
		}
		return redirect()->route('Circle.index');
	}


	public function changeStatus($modelId = 0, $status = 0)
	{
		if ($status == 0) {
			$statusMessage	=	trans($this->sectionNameSingular . " has been deactivated successfully");
		} else {
			$statusMessage	=	trans($this->sectionNameSingular . " has been activated successfully");
		}

		Circle::where('id', $modelId)->update(array('is_active' => $status));
		Session::flash('flash_notice', $statusMessage);
		return Redirect::back();
	} // end changeStatus()


	public function edit($modelId = 0)
	{
		$model				=	Circle::find($modelId);
		if (empty($model)) {
			return redirect()->route('Circle.index');
		}
		$region  = Region::pluck('region', 'id')->toArray();
		return  view("admin.Circle.add", compact('model', 'region'));
	}

	public function delete($id = 0)
	{
		$model	=	Circle::find($id);
		if (empty($model)) {
			return Redirect::back();
		}
		if ($id) {
			Circle::where('id', $id)->delete();
			Session::flash('flash_notice', trans($this->sectionNameSingular . " has been removed successfully"));
		}
		return Redirect::back();
	}
}
