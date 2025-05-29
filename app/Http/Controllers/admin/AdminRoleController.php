<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Models\Roles;
use App\Models\StateDescription;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, Redirect, Request, Response, Session, URL, View, Validator;

/**
 * AdminRoleController Controller
 *
 * Add your methods in the class below
 *
 */
class AdminRoleController extends BaseController
{

	public $model		=	'Roles';
	public $sectionName	=	'Role';
	public $sectionNameSingular	=	'Role';

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
		$id = Auth::user()->id;
		$moduleName = $this->CheckAccess($id);
		if (!in_array("Sub Admin Management", $moduleName) &&  Auth::user()->user_role_id != SUPER_ADMIN_ROLE_ID) {
			Session::flash('error', trans("Sorry, you don't have access to this."));
			return Redirect::back();
		}



		$DB							=	Roles::query();
		//echo 'dsaa'; die;

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
					if ($fieldName == "is_active") {
						$DB->where("admin_roles.is_active", $fieldValue);
					}
					if ($fieldName == "role") {
						$DB->where("admin_roles.role", 'like', '%' . $fieldValue . '%');
					}
				}
				$searchVariable	=	array_merge($searchVariable, array($fieldName => $fieldValue));
			}
		}
		$sortBy 					= 	(Request::get('sortBy')) ? Request::get('sortBy') : 'updated_at';
		$order  					= 	(Request::get('order')) ? Request::get('order')   : 'DESC';
		$results 					= 	$DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		$complete_string			=	Request::query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string				=	http_build_query($complete_string);
		$results->appends(Request::all())->render();

		$modules =   DB::table('modules')->pluck('name', 'id');
		//echo '<pre>'; print_r($results); die;
		return  View::make("admin.$this->model.index", compact('results', 'searchVariable', 'sortBy', 'order', 'query_string', 'modules'));
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
		$modules =   DB::table('modules')->pluck('name', 'id');
		if (!empty($modules)) {
			$modules =  $modules;
		} else {
			$modules =  array();
		}
		return  View::make("admin.$this->model.add", compact('modules'));
	} // end add()

	/**
	 * Function for save new Roles
	 *
	 * @param null
	 *
	 * @return redirect page. 
	 */
	function save()
	{
		Request::replace($this->arrayStripTags(Request::all()));
		$thisData					=	Request::all();

		$validator = Validator::make(
			$thisData,
			array(
				'role' 			=> 'required|max:255|Regex:/\A(?!.*[:;]-\))[ -~]+\z/',
				'modules' 			=> "required"
				//'description' 		=> 'required',
			),
			array(
				"role.Regex"			=>	trans("The role format is invalid."),
			)
		);

		if ($validator->fails()) {
			return Redirect::back()
				->withErrors($validator)->withInput();
		} else {
			$obj = new Roles;
			$obj->role   		= Request::get('role');
			$obj->modules   			=  implode(',', Request::get('modules'));
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
	 * @param $modelId as id of Roles 
	 * @param $status as status of Roles 
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

		Roles::where('id', $modelId)->update(array('is_active' => $status));
		Session::flash('flash_notice', $statusMessage);
		return Redirect::back();
	} // end changeStatus()

	/**
	 * Function for display page for edit Roles
	 *
	 * @param $modelId id  of Roles
	 *
	 * @return view page. 
	 */
	public function edit($modelId = 0)
	{
		$model				=	Roles::find($modelId);
		if (empty($model)) {
			return Redirect::route($this->model . ".index");
		}
		$modules =   DB::table('modules')->pluck('name', 'id');

		return  View::make("admin.$this->model.edit", compact('modules', 'model'));
	} // end edit()


	/**
	 * Function for update Roles 
	 *
	 * @param $modelId as id of Roles 
	 *
	 * @return redirect page. 
	 */
	function update($modelId)
	{
		$model					=	Roles::findorFail($modelId);
		if (empty($model)) {
			return Redirect::back();
		}

		Request::replace($this->arrayStripTags(Request::all()));
		$thisData					=	Request::all();
		//echo '<pre>'; print_r($thisData); die;

		$validator = Validator::make(
			$thisData,
			array(
				'role' 			=> 'required|max:255|Regex:/\A(?!.*[:;]-\))[ -~]+\z/',
				'modules' 			=> "required"
				//'description' 		=> 'required',
			),
			array(
				"role.Regex"			=>	trans("The role format is invalid."),
			)
		);

		if ($validator->fails()) {
			return Redirect::back()
				->withErrors($validator)->withInput();
		} else {
			$obj = $model;
			$obj->role   			= Request::get('role');
			$obj->modules   		=  implode(',', Request::get('modules'));
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
		$model	=	Roles::find($id);
		if (empty($model)) {
			return Redirect::back();
		}
		if ($id) {
			Roles::where('id', $id)->delete();
			Session::flash('flash_notice', trans($this->sectionNameSingular . " has been removed successfully"));
		}
		return Redirect::back();
	} // end delete()



}// end CategoriesController
