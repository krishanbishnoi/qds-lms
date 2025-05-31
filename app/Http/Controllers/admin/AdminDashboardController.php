<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Models\BankDetail;

use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, mongoDate, Redirect, Request, Response, Session, URL, View, Validator;

/**
 * AdminDashBoard Controller
 *
 * Add your methods in the class below
 *
 * This file will render views\admin\dashboard
 */
class AdminDashboardController extends BaseController
{
	/**
	 * Function for display admin dashboard
	 *
	 * @param null
	 *

	 * @return view page.
	 */
	public function showdashboard()
	{


		$totalTrainers			=	DB::table("users")->where("is_deleted", 0)->where("user_role_id", TRAINER_ROLE_ID)->get();

		$totalTrainees		=	DB::table("users")->where("is_deleted", 0)->where("user_role_id", TRAINEE_ROLE_ID)->get();

		$totalTrainings = DB::table("trainings")->get();

		$totalTests = DB::table("tests")->get();
		//  $totalVendors				=	DB::table("users")->where("is_deleted",0)->where("user_role_id",VENDOR_ROLE_ID)->count();
		//  $totalDrivers				=	DB::table("users")->where("is_deleted",0)->where("user_role_id",DRIVER_ROLE_ID)->count();

		// // $totalActiveUsers	=	DB::table("users")->where("user_role_id",FRONT_USER_ROLE_ID)->where("is_deleted",0)->where("is_active",1)->count();
		// //User Graph Data
		// $totalGames				=	DB::table("games")->where("is_deleted",0)->where("is_active",1)->count();
		// $totalStreeks				=	DB::table("streeks")->count();

		// $month							=	date('m');


		// $year							=	date('Y');
		// for ($i = 0; $i < 12; $i++) {
		// 	$months[] 					=	date("Y-m", strtotime( date( 'Y-m-01' )." -$i months"));
		// }

		// $months							=	array_reverse($months);
		// //print_r($months); die;
		// $num							=	0;
		// $allUsers						=	array();
		// $thisMothUsers					=	0;
		// for($i = 1; $i <= 12 ; $i++){
		// 	$currentDateMonth = date('Y-'.$i.'-d');
		// 	//echo $currentDateMonth;die;
		// 	$data = ['month'=>date('M',strtotime($currentDateMonth))];
		// 	$data['customer']	=	DB::table('users')->whereMonth('created_at','=',$i)->where("user_role_id",CUSTOMER_ROLE_ID)->where("is_verified",1)->where('is_deleted','!=',1)->count();
		// 	$data['games']	=	DB::table('games')->whereMonth('created_at','=',$i)->where("is_deleted",0)->where("is_active",1)->count();
		// 	$data['streeks']	=	DB::table('streeks')->whereMonth('created_at','=',$i)->count();
		// 	$allUsers[] = $data;

		// }
		// // foreach($months as $month){
		// // 	$month_start_date			=	date('Y-m-01 00:00:00', strtotime($month));
		// // 	$month_end_date				=	date('Y-m-t 23:59:59', strtotime($month));
		// // 	$allUsers[$num]['month']	=	$months;
		// // 	$allUsers[$num]['users']	=	DB::table('users')->where('MONTH(created_at)','=',$month_start_date)->where('created_at','<=',$month_end_date)->where('is_deleted','!=',1)->count();
		// // 	$allUsers[$num]['users']	=	DB::table('users')->where('created_at','>=',$month_start_date)->where('created_at','<=',$month_end_date)->where('is_deleted','!=',1)->count();
		// // 	if($month_start_date == date( 'Y-m-01 00:00:00', strtotime( 'first day of ' . date( 'F Y')))){
		// // 		$thisMothUsers	=	$allUsers[$num]['users'];
		// // 	}
		// // 	$num ++;
		// // }


		// $allUsers		=	json_encode($allUsers);
		// dd($totalTrainings);

		return  View::make('admin.dashboard.dashboard', compact("totalTrainers", 'totalTrainees', 'totalTrainings', 'totalTests'));
	}
	/**
	 * Function for display admin account detail
	 *
	 * @param null
	 *
	 * @return view page.
	 */
	public function myaccount()
	{
		return  View::make('admin.dashboard.myaccount');
	} // end myaccount()
	/**
	 * Function for display bank detail
	 *
	 * @param null
	 *
	 * @return view page.
	 */
	public function bankdetail()
	{
		$userInfo = BankDetail::where('user_id', Auth::user()->id)->first();
		return  View::make('admin.dashboard.bankdetail', compact('userInfo'));
	} // end myaccount()
	/**


	 * Function for change_password
	 *
	 * @param null
	 *
	 * @return view page.
	 */
	public function change_password()
	{
		return  View::make('admin.dashboard.change_password');
	} // end myaccount()
	/**
	 * Function for update admin account update
	 *
	 * @param null
	 *
	 * @return redirect page.
	 */
	public function myaccountUpdate()
	{
		$thisData				=	Request::all();

		//print_r($thisData); die;
		Request::replace($this->arrayStripTags($thisData));
		$ValidationRule = array(
			'first_name' => 'required|max:55|Regex:/\A(?!.*[:;]-\))[ -~]+\z/',
			'last_name' => 'required|max:55|Regex:/\A(?!.*[:;]-\))[ -~]+\z/',
			'email' => 'required|email|max:255',
			'image' => 'image|mimes:jpeg,png,jpg|max:2048',
		);
		$validator 				= 	Validator::make(Request::all(), $ValidationRule);
		if ($validator->fails()) {
			return Redirect::to('admin/myaccount')
				->withErrors($validator)->withInput();
		} else {
			$user 				= 	User::find(Auth::user()->id);
			$user->email	 	= 	Request::get('email');
			$user->first_name			= Request::get('first_name');
			$user->last_name			= Request::get('last_name');
			$user->mobile_number			= Request::get('mobile_number');
			$user->employee_id			= Request::get('employee_id');

			if (Request::hasFile('image')) {
				$extension 	=	 Request::file('image')->getClientOriginalExtension();
				$fileName	=	time() . '-user.' . $extension;

				$folderName     	= 	strtoupper(date('M') . date('Y')) . "/";
				$folderPath			=	USER_IMAGE_ROOT_PATH . $folderName;
				if (!File::exists($folderPath)) {
					File::makeDirectory($folderPath, $mode = 0777, true);
				}
				if (Request::file('image')->move($folderPath, $fileName)) {
					$user->image	=	$folderName . $fileName;
				}
			}


			if ($user->save()) {
				return Redirect::intended('admin/myaccount')
					->with('success', 'Information updated successfully.');
			}
		}
	} // end myaccountUpdate()
	/**
	 *
	 * Function for update admin bank detail update
	 *
	 * @param null
	 *
	 * @return redirect page.
	 */
	public function bankdetailUpdate()
	{
		$thisData				=	Request::all();
		//dd($thisData);
		//print_r($thisData); die;
		Request::replace($this->arrayStripTags($thisData));
		$ValidationRule = array(
			'account_holder_name' => 'required|Regex:/\A(?!.*[:;]-\))[ -~]+\z/',
			'bank_name' => 'required|Regex:/\A(?!.*[:;]-\))[ -~]+\z/',
			'account_number' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,2})?\s*$/',
			'account_type' => 'required|Regex:/\A(?!.*[:;]-\))[ -~]+\z/',
			'iban_number' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,2})?\s*$/',
		);
		$validator 				= 	Validator::make(Request::all(), $ValidationRule);
		if ($validator->fails()) {
			return Redirect::to('admin/bankdetail')
				->withErrors($validator)->withInput();
		} else {
			$user 						= 	BankDetail::where('user_id', Auth::user()->id)->first();
			$user->account_holder_name	= 	Request::get('account_holder_name');
			$user->bank_name			=   Request::get('bank_name');
			$user->account_number		=   Request::get('account_number');
			$user->account_type	 		= 	Request::get('account_type');
			$user->iban_number			=   Request::get('iban_number');

			if ($user->save()) {
				return Redirect::intended('admin/bankdetail')
					->with('success', 'Information updated successfully.');
			}
		}
	} 

	
	public function changedPassword()
	{
		$thisData				=	Request::all();
		Request::replace($this->arrayStripTags($thisData));
		$old_password    		= 	Request::get('old_password');
		$password         		= 	Request::get('new_password');
		$confirm_password 		= 	Request::get('confirm_password');
		Validator::extend('custom_password', function ($attribute, $value, $parameters) {
			if (preg_match('#[0-9]#', $value) && preg_match('#[a-zA-Z]#', $value) && preg_match('#[\W]#', $value)) {
				return true;
			} else {
				return false;
			}
		});
		$rules        		  	= 	array(
			'old_password' 		=>	'required|max:255',
			'new_password'		=>	'required|min:8|max:255|custom_password',
			'confirm_password'  =>	'required|max:255|same:new_password'
		);
		$validator 				= 	Validator::make(
			Request::all(),
			$rules,
			array(
				"new_password.custom_password"	=>	"Password must have combination of numeric, alphabet and special characters.",
			)
		);
		if ($validator->fails()) {
			return Redirect::to('admin/change-password')
				->withErrors($validator)->withInput();
		} else {
			$user 				= User::find(Auth::user()->id);
			$old_password 		= Request::get('old_password');
			$password 			= Request::get('new_password');
			$confirm_password 	= Request::get('confirm_password');
			if ($old_password != '') {
				if (!Hash::check($old_password, $user->password)) {
					/* return Redirect::intended('change-password')
						->with('error', 'Your old password is incorrect.');
						 */
					Session::flash('error', trans("Your old password is incorrect."));
					return Redirect::to('admin/change-password');
				}
			}
			if (!empty($old_password) && !empty($password) && !empty($confirm_password)) {
				if (Hash::check($old_password, $user->password)) {
					$user->password = Hash::make($password);
					// save the new password
					if ($user->save()) {
						Session::flash('success', trans("Password changed successfully."));
						return Redirect::to('admin/change-password');
					}
				} else {
					/* return Redirect::intended('change-password')
						->with('error', 'Your old password is incorrect.'); */
					Session::flash('error', trans("Your old password is incorrect."));
					return Redirect::to('admin/change-password');
				}
			} else {
				$user->username = $username;
				if ($user->save()) {
					Session::flash('success', trans("Password changed successfully."));
					return Redirect::to('admin/change-password');
					/* return Redirect::intended('change-password')
						->with('success', 'Password changed successfully.'); */
				}
			}
		}
	} // end myaccountUpdate()
	/*
* For User Listing Demo
*/
	public function usersListing()
	{
		return View::make('admin.user.user');
	}
} //end AdminDashBoardController()
