<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Models\Circle;
use App\Models\Region;
use App\Models\StateDescription;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Request, Mail, Redirect, Response, Session, URL, View, Validator;

/**
 * CircleController Controller
 *
 * Add your methods in the class below
 *
 */
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

	/**
	 * Function for display all State 
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
	public function index()
	{
		$DB							=	Circle::query();
		$searchVariable				=	array();
		$inputGet					=	Request::all();
		if ((Request::all())) {
			$searchData				=	Request::all();
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
		$sortBy 					= 	(Request::get('sortBy')) ? Request::get('sortBy') : 'updated_at';
		$order  					= 	(Request::get('order')) ? Request::get('order')   : 'DESC';
		$results 					= 	$DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		$complete_string			=	Request::query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string				=	http_build_query($complete_string);
		$results->appends(Request::all())->render();
		//echo '<pre>'; print_r($results); die;
		return  View::make("admin.$this->model.index", compact('results', 'searchVariable', 'sortBy', 'order', 'query_string'));
	}

	/**
	 * Function for add new State
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
	public function add()
	{
		$region  = Region::pluck('region', 'id')->toArray();
		return  View::make("admin.$this->model.add", compact('region'));
	} // end add()

	/**
	 * Function for save new Area
	 *
	 * @param null
	 *
	 * @return redirect page. 
	 */
	function save()
	{
		Request::replace($this->arrayStripTags(Request::all()));
		$thisData					=	Request::all();
		//echo '<pre>'; print_r($thisData); die;

		$validator = Validator::make(
			$thisData,
			array(
				'circle' 			=> 'required|unique:circles',
				'region_id' 			=> 'required',
				//'description' 		=> 'required',
			)


		);

		if ($validator->fails()) {
			return Redirect::back()
				->withErrors($validator)->withInput();
		} else {
			$obj = new Circle;
			$obj->circle   			= Request::get('circle');
			$obj->region_id   		= Request::get('region_id');
			$objSave				= $obj->save();
			if (!$objSave) {

				Session::flash('error', trans("Something went wrong."));
				return Redirect::route($this->model . ".index");
			} else {
				Session::flash('success', trans($this->sectionNameSingular . " has been added successfully"));
				return Redirect::route($this->model . ".index");
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
			$statusMessage	=	trans($this->sectionNameSingular . " has been deactivated successfully");
		} else {
			$statusMessage	=	trans($this->sectionNameSingular . " has been activated successfully");
		}

		Circle::where('id', $modelId)->update(array('is_active' => $status));
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
	public function edit($modelId = 0)
	{
		$model				=	Circle::find($modelId);
		if (empty($model)) {
			return Redirect::route($this->model . ".index");
		}
		$region  = Region::pluck('region', 'id')->toArray();
		return  View::make("admin.$this->model.edit", compact('model', 'region'));
	} // end edit()


	/**
	 * Function for update area 
	 *
	 * @param $modelId as id of area 
	 *
	 * @return redirect page. 
	 */
	function update($modelId)
	{
		$model					=	Circle::findorFail($modelId);
		if (empty($model)) {
			return Redirect::back();
		}

		Request::replace($this->arrayStripTags(Request::all()));
		$thisData					=	Request::all();
		//echo '<pre>'; print_r($thisData); die;

		$validator = Validator::make(
			$thisData,
			array(
				'circle' 			=> "required|unique:circles,circle,$modelId",
				'region_id' 			=> 'required',
				//'description' 		=> 'required',
			)
		);

		if ($validator->fails()) {
			return Redirect::back()
				->withErrors($validator)->withInput();
		} else {
			$obj = $model;
			$obj->circle   		= Request::get('circle');
			$obj->region_id   		= Request::get('region_id');
			$objSave				= $obj->save();
			if (!$objSave) {

				Session::flash('error', trans("Something went wrong."));
				return Redirect::route($this->model . ".index");
			} else {
				Session::flash('success', trans($this->sectionNameSingular . " has been Updated successfully"));
				return Redirect::route($this->model . ".index");
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
		$model	=	Circle::find($id);
		if (empty($model)) {
			return Redirect::back();
		}
		if ($id) {
			Circle::where('id', $id)->delete();
			Session::flash('flash_notice', trans($this->sectionNameSingular . " has been removed successfully"));
		}
		return Redirect::back();
	} // end delete()



}// end CircleController
