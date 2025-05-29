<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Models\EmailLog;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, mongoDate, Redirect, Request, Response, Session, URL, View, Validator;

/**
 * Base Controller
 *
 * Add your methods in the class below
 *
 * This is the base controller called everytime on every request
 */
class  EmailLogsController extends BaseController
{
	/*
* Function for display email detail from database   
*
* @param null
*
* @return view page. 
*/
	public function listEmail()
	{

		$DB							=	EmailLog::query();
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

					if ($fieldName == "email_to") {
						$DB->where("email_logs.email_to", 'like', '%' . $fieldValue . '%');
					}
					if ($fieldName == "subject") {
						$DB->where("email_logs.subject", 'like', '%' . $fieldValue . '%');
					}
					if ($fieldName == "keyword") {
						$DB->where(function ($query) use ($fieldValue) {
							$query->where("email_logs.message", 'like', '%' . $fieldValue . '%')
								->orWhere("email_logs.subject", 'like', '%' . $fieldValue . '%')
								->orWhere("email_logs.email_from", 'like', '%' . $fieldValue . '%')
								->orWhere("email_logs.email_to", 'like', '%' . $fieldValue . '%');
						});
					}
				}
				$searchVariable	=	array_merge($searchVariable, array($fieldName => $fieldValue));
			}
		}
		$sortBy 					= 	(Request::get('sortBy')) ? Request::get('sortBy') : 'created_at';
		$order  					= 	(Request::get('order')) ? Request::get('order')   : 'DESC';
		$result 					= 	$DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		$complete_string			=	Request::query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string				=	http_build_query($complete_string);
		$result->appends(Request::all())->render();
		//echo '<pre>'; print_r($results); die;
		return View::make('admin.emaillogs.index', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string'));
	} //end listEmail()
	/*
* Function for dispaly email details on popup   
*
* @param $id as mail id 
*
* @return view page. 
*/
	public function EmailDetail($id)
	{
		if (Request::ajax()) {
			$result	= EmailLog::where('id', $id)->get();
			return View::make('admin.emaillogs.popup', compact('result'));
		}
	} // end EmailDetail()
}// end EmailLogsController