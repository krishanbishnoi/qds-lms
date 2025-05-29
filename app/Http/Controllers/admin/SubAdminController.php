<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Models\EmailTemplate;
use App\Models\EmailAction;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, Redirect, Request, Response, Session, URL, View, Validator;

/**
 * SubAdminController Controller
 *
 * Add your methods in the class below
 *
 */
class SubAdminController extends BaseController
{

	public $model		=	'SubAdmin';
	public $sectionName	=	'Sub Admin';
	public $sectionNameSingular	=	'Sub Admin';

	public function __construct()
	{
		parent::__construct();
		View::share('modelName', $this->model);
		View::share('sectionName', $this->sectionName);
		View::share('sectionNameSingular', $this->sectionNameSingular);
	}

	/**
	 * Function for display all Users 
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

		$DB					=	User::query();
		$searchVariable		=	array();
		$inputGet			=	Request::all();
		if ((Request::all())) {
			$searchData			=	Request::all();
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
			if ((!empty($searchData['date_from'])) && (!empty($searchData['date_to']))) {
				$dateS = $searchData['date_from'];
				$dateE = $searchData['date_to'];
				$DB->whereBetween('users.created_at', [$dateS . " 00:00:00", $dateE . " 23:59:59"]);
			} elseif (!empty($searchData['date_from'])) {
				$dateS = $searchData['date_from'];
				$DB->where('users.created_at', '>=', [$dateS . " 00:00:00"]);
			} elseif (!empty($searchData['date_to'])) {
				$dateE = $searchData['date_to'];
				$DB->where('users.created_at', '<=', [$dateE . " 00:00:00"]);
			}
			foreach ($searchData as $fieldName => $fieldValue) {
				if ($fieldValue != "") {
					if ($fieldName == "name") {
						$DB->where("users.name", 'like', '%' . $fieldValue . '%');
					}
					if ($fieldName == "mobile_number") {
						$DB->where("users.mobile_number", 'like', '%' . $fieldValue . '%');
					}
					if ($fieldName == "email") {
						$DB->where("users.email", 'like', '%' . $fieldValue . '%');
					}
					if ($fieldName == "is_active") {
						$DB->where("users.is_active", $fieldValue);
					}
				}
				$searchVariable	=	array_merge($searchVariable, array($fieldName => $fieldValue));
			}
		}
		$DB->where("is_deleted", 0)->where("is_verified", 1);
		$DB->where("user_role_id", SUB_ADMIN_ROLE_ID)->leftJoin('admin_roles', 'admin_roles.id', '=', 'users.admin_role_id')->select('users.*', 'admin_roles.role as role');
		$sortBy = (Request::get('sortBy')) ? Request::get('sortBy') : 'created_at';
		$order  = (Request::get('order')) ? Request::get('order')   : 'DESC';
		$results = $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		$complete_string		=	Request::query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string			=	http_build_query($complete_string);
		$results->appends(Request::all())->render();

		$admin_roles =   DB::table('admin_roles')->pluck('role', 'id');
		return  View::make("admin.$this->model.index", compact('results', 'searchVariable', 'sortBy', 'order', 'query_string', 'admin_roles'));
	} // end index()


	/**
	 * Function for add new customer
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
	public function add()
	{

		$admin_roles =   DB::table('admin_roles')->pluck('role', 'id');
		return  View::make("admin.$this->model.add", compact('admin_roles'));
	} // end add()

	/**
	 * Function for save new customer
	 *
	 * @param null
	 *
	 * @return redirect page. 
	 */
	function save()
	{
		Request::replace($this->arrayStripTags(Request::all()));
		$formData						=	Request::all();
		if (!empty($formData)) {
			$validator 					=	Validator::make(
				Request::all(),
				array(
					'name'						=> 'required|max:55|Regex:/\A(?!.*[:;]-\))[ -~]+\z/',
					'email' 					=> 'required|max:255|email|unique:users|regex:/(.+)@(.+)\.(.+)/i',
					'mobile_number' 			=> 'required|numeric|unique:users|digits_between:7,15',
					'password'					=> 'required|min:8',
					'confirm_password'  		=> 'required|min:8|same:password',
					'admin_role_id'  		=> 'required',
				),
				array(
					"name.required"					=>	trans("The name field is required."),
				)
			);
			$password 					= 	Request::get('password');
			if (preg_match('#[0-9]#', $password) && preg_match('#[a-zA-Z]#', $password) && preg_match('#[\W]#', $password)) {
				$correctPassword		=	md5($password);
			} else {
				$errors 				=	$validator->messages();
				$errors->add('password', trans("Password must have be a combination of numeric, alphabet and special characters."));
				return Redirect::back()->withErrors($errors)->withInput();
			}
			if ($validator->fails()) {
				return Redirect::back()->withErrors($validator)->withInput();
			} else {
				$obj 									=  new User;
				$validateString							=  md5(time() . Request::get('email'));
				$obj->validate_string					=  $validateString;
				$obj->name 								=  Request::get('name');
				$obj->email 							=  Request::get('email');
				$obj->mobile_number 					=  Request::get('mobile_number');
				//$obj->password	 						= 	md5(Request::get('password'));
				$obj->password	 						= 	Hash::make(Request::get('password'));
				$obj->user_role_id						=  SUB_ADMIN_ROLE_ID;
				$obj->admin_role_id						=   Request::get('admin_role_id');
				$obj->is_mobile_verified				=  1;
				$obj->is_email_verified					=  1;
				$obj->is_active							=  1;
				$obj->is_verified						=  1;
				$obj->save();
				$userId					=	$obj->id;
				if (!$userId) {
					Session::flash('error', trans("Something went wrong."));
					return Redirect::back()->withInput();
				}
				//mail email and password to new registered user
				$settingsEmail 			=	Config::get('Site.email');
				$full_name				= 	$obj->name;
				$email					= 	$obj->email;
				$password				= 	Request::get('password');
				$route_url     			= 	URL::to('/login');
				$click_link   			=   $route_url;
				$emailActions			= 	EmailAction::where('action', '=', 'user_registration_information')->get()->toArray();
				$emailTemplates			= 	EmailTemplate::where('action', '=', 'user_registration_information')->get(array('name', 'subject', 'action', 'body'))->toArray();
				$cons 					= 	explode(',', $emailActions[0]['options']);
				$constants 				= 	array();
				foreach ($cons as $key => $val) {
					$constants[] 		= 	'{' . $val . '}';
				}
				$subject 				= 	$emailTemplates[0]['subject'];
				$rep_Array 				= 	array($full_name, $email, $obj->mobile_number, $email, $password);
				$messageBody			= 	str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
				$mail					= 	$this->sendMail($email, $full_name, $subject, $messageBody, $settingsEmail);

				Session::flash('success', trans($this->sectionNameSingular . " has been added successfully"));
				return Redirect::route($this->model . ".index");
			}
		}
	} //end save()

	/**
	 * Function for update status
	 *
	 * @param $modelId as id of customer 
	 * @param $status as status of customer 
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
		User::where('id', $modelId)->update(array('is_active' => $status));
		Session::flash('flash_notice', $statusMessage);
		return Redirect::back();
	} // end changeStatus()

	/**
	 * Function for display page for edit customer
	 *
	 * @param $modelId id  of customer
	 *
	 * @return view page. 
	 */
	public function edit($modelId = 0)
	{
		$model					=	User::findorFail($modelId);
		if (empty($model)) {
			return Redirect::back();
		}
		$admin_roles =   DB::table('admin_roles')->pluck('role', 'id');
		return  View::make("admin.$this->model.edit", compact('model', 'admin_roles'));
	} // end edit()


	/**
	 * Function for update customer 
	 *
	 * @param $modelId as id of customer 
	 *
	 * @return redirect page. 
	 */
	function update($modelId)
	{
		$model					=	User::findorFail($modelId);
		if (empty($model)) {
			return Redirect::back();
		}

		Request::replace($this->arrayStripTags(Request::all()));
		$formData						=	Request::all();
		if (!empty($formData)) {
			$validator 					=	Validator::make(
				Request::all(),
				array(
					'name'						=> 'required|max:55|Regex:/\A(?!.*[:;]-\))[ -~]+\z/',
					'email' 					=> "required|email|max:255|unique:users,email,$modelId|regex:/(.+)@(.+)\.(.+)/i",
					'mobile_number' 				=> "required|numeric|digits_between:7,15|unique:users,mobile_number,$modelId",
				),
				array(
					"name.required"					=>	trans("The name field is required."),
				)
			);
			if ($validator->fails()) {
				return Redirect::back()->withErrors($validator)->withInput();
			} else {
				$obj 									=  $model;
				$obj->name 								=  Request::get('name');
				$obj->email 							=  Request::get('email');
				$obj->mobile_number 					=  Request::get('mobile_number');
				$obj->admin_role_id						=   Request::get('admin_role_id');
				$obj->save();
				$userId					=	$obj->id;
				if (!$userId) {
					Session::flash('error', trans("Something went wrong."));
					return Redirect::back()->withInput();
				}
				Session::flash('success', trans($this->sectionNameSingular . " has been updated successfully"));
				return Redirect::route($this->model . ".index");
			}
		}
	} // end update()

	/**
	 * Function for update Currency  status
	 *
	 * @param $modelId as id of Currency 
	 * @param $modelStatus as status of Currency 
	 *
	 * @return redirect page. 
	 */
	public function delete($userId = 0)
	{
		$userDetails	=	User::find($userId);
		if (empty($userDetails)) {
			return Redirect::route($this->model . ".index");
		}
		if ($userId) {
			$email 			=	'delete_' . $userId . '_' . $userDetails->email;
			$mobile_number 		=	'delete_' . $userId . '_' . $userDetails->mobile_number;
			User::where('id', $userId)->update(array('is_deleted' => 1, 'email' => $email, 'mobile_number' => $mobile_number));
			Session::flash('flash_notice', trans($this->sectionNameSingular . " has been removed successfully"));
		}
		return Redirect::back();
	} // end delete()

	public function view($modelId = 0)
	{
		$model	=	User::where('id', "$modelId")->select('users.*')->first();
		if (empty($model)) {
			return Redirect::route($this->model . ".index");
		}
		$role	=	DB::table('admin_roles')->where('id', $model->admin_role_id)->pluck('role')->first();
		return  View::make("admin.$this->model.view", compact('model', 'role'));
	} // end view()



}// end BrandsController
