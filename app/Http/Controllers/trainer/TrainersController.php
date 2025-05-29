<?php

namespace App\Http\Controllers\trainer;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Imports\importTrainers;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Region;
use App\Models\Lob;
use App\Models\Circle;
use App\Models\EmailTemplate;
use App\Models\EmailAction;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, Redirect, Request, Response, Session, URL, View, Validator;

/**
 * TrainersController Controller
 *
 * Add your methods in the class below
 *
 */
class TrainersController extends BaseController
{

	public $model		=	'Trainers';
	public $sectionName	=	'Trainer';
	public $sectionNameSingular	=	'Trainer';

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
					if ($fieldName == "first_name") {
						$DB->where("users.first_name", 'like', '%' . $fieldValue . '%');
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
		$DB->where("is_deleted", 0)->where("user_role_id", TRAINER_ROLE_ID);
		//$DB->where("user_role_id",Config::get('constants.user.CUSTOMER_ROLE_ID'));
		$sortBy = (Request::get('sortBy')) ? Request::get('sortBy') : 'created_at';
		$order  = (Request::get('order')) ? Request::get('order')   : 'DESC';
		$results = $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		$complete_string		=	Request::query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string			=	http_build_query($complete_string);
		$results->appends(Request::all())->render();
		return  View::make("trainer.$this->model.index", compact('results', 'searchVariable', 'sortBy', 'order', 'query_string'));
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

		$region = 	Region::pluck('region', 'id')->toArray();
		$lob = Lob::pluck('lob', 'id')->toArray();
		$circle = Circle::pluck('circle', 'id')->toArray();

		//echo '<pre>'; print_r($region); die;
		return  View::make("trainer.$this->model.add", compact('region', 'lob', 'circle'));
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
					'first_name'				=> 'required|max:55',
					'last_name'					=> 'required|max:55',
					'lob'				=> 'required',
					'employee_id'				=> 'required',
					'region'				=> 'required',
					'circle'				=> 'required',
					'email' 					=> 'required|max:255|email|unique:users|regex:/(.+)@(.+)\.(.+)/i',
					'mobile_number' 			=> 'required|min:10|regex:/^([0-9\s\-\+\(\)]*)$/|unique:users',
					'password'					=> 'required|min:8',
					'confirm_password'  		=> 'required|same:password',
				),
				array(
					"first_name.required"					=>	trans("The first name field is required."),
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
				$obj->first_name 						=  Request::get('first_name');
				$first_name = Request::get("first_name");
				$last_name = Request::get("last_name");
				$fullname = $first_name . " " . $last_name;
				$obj->fullname							=  $fullname;

				$obj->last_name 						=  Request::get('last_name');
				$obj->email 							=  Request::get('email');

				$obj->employee_id 						=  Request::get('employee_id');
				$obj->region 							=  Request::get('region');
				$obj->circle 							=  Request::get('circle');
				$obj->lob 								=  Request::get('lob');
				$obj->mobile_number 					=  Request::get('mobile_number');
				//$obj->password	 					= 	md5(Request::get('password'));
				$obj->password	 						= 	Hash::make(Request::get('password'));
				$obj->user_role_id						=  TRAINER_ROLE_ID;
				$obj->is_active							=  1;
				$obj->is_mobile_verified						=  1;
				$obj->is_email_verified						=  1;
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


		$region = 	Region::pluck('region', 'id')->toArray();
		$lob = Lob::pluck('lob', 'id')->toArray();
		$circle = Circle::pluck('circle', 'id')->toArray();
		return  View::make("trainer.$this->model.edit", compact('model', 'region', 'lob', 'circle'));
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
					'first_name'				=> 'required|max:55',
					'last_name'					=> 'required|max:55',
					'lob'				=> 'required',
					'employee_id'				=> 'required',
					'region'				=> 'required',
					'circle'				=> 'required',
					'email' 					=> "required|email|max:255|unique:users,email,$modelId|regex:/(.+)@(.+)\.(.+)/i",
					'mobile_number' 			=> "required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users,mobile_number,$modelId",
				),
				array(
					"name.required"				=>	trans("The name field is required."),
				)
			);
			if ($validator->fails()) {
				return Redirect::back()->withErrors($validator)->withInput();
			} else {
				$obj 									=  $model;
				$obj->first_name 						=  Request::get('first_name');
				$first_name 							= Request::get("first_name");
				$last_name 								= Request::get("last_name");
				$fullname 								= $first_name . " " . $last_name;
				$obj->fullname							=  $fullname;
				$obj->employee_id 					=  Request::get('employee_id');
				$obj->region 					=  Request::get('region');
				$obj->circle 					=  Request::get('circle');
				$obj->lob 					=  Request::get('lob');
				//	$obj->username 							=  Request::get('username');
				$obj->last_name 						=  Request::get('last_name');
				$obj->email 							=  Request::get('email');
				$obj->mobile_number 					=  Request::get('mobile_number');
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
		// $UserInvestAmount = DB::table('streek_participant')->where('user_id',$model->id)->sum('amount');
		// $won_amount = DB::table('streek_participant')->where('user_id',$model->id)->sum('won_amount');

		if (empty($model)) {
			return Redirect::route($this->model . ".index");
		}


		//	echo '<pre>'; print_r($Historystreeks); die;
		return  View::make("trainer.$this->model.view", compact('model'));
	} // end view()


	public function importTrainers()
	{
		Excel::import(new importTrainers, request()->file('file'));


		//    echo '<pre>'; print_r( $trainers); die;
		return redirect()->back()->with('success', 'Users imported successfully!');
	}
}// end BrandsController