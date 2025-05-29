<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Models\Contact;
use App\Models\EmailTemplate;
use App\Models\EmailAction;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, mongoDate, Redirect, Request, Response, Session, URL, View, Validator;

/**
 * Contacts Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/contact
 */

class ContactsController extends BaseController
{
	/**
	 * $model Contact. 
	 */
	public $model	=	'Contact';
	/**
	 * Function for __construct
	 *
	 * @param null
	 *
	 * @return model name
	 */
	public function __construct()
	{
		View::share('modelName', $this->model);
	}
	/**
	 * Function for display list of  all contact
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
	public function listContact()
	{
		$id = Auth::user()->id;
		$moduleName = $this->CheckAccess($id);
		if (!in_array("Contact & Issue Management", $moduleName) &&  Auth::user()->user_role_id != SUPER_ADMIN_ROLE_ID) {
			Session::flash('error', trans("Sorry, you don't have access to this."));
			return Redirect::back();
		}


		$DB 								= 	Contact::query();
		$searchVariable						=	array();
		$inputGet							=	Request::all();
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

			if (isset($searchData['per_page'])) {
				unset($searchData['per_page']);
			}
			foreach ($searchData as $fieldName => $fieldValue) {
				if (!empty($fieldValue)) {
					$DB->where("$fieldName", 'like', '%' . $fieldValue . '%');
					$searchVariable			=	array_merge($searchVariable, array($fieldName => $fieldValue));
				}
			}
		}
		$sortBy 							= 	(Request::get('sortBy')) ? Request::get('sortBy') : 'created_at';
		$order  							= 	(Request::get('order')) ? Request::get('order')   : 'DESC';
		$PerPageRecord					=	(Request::get('per_page')) ? Request::get('per_page') : Config::get("Reading.records_per_page");
		$model 								= 	$DB->orderBy($sortBy, $order)->paginate($PerPageRecord);
		$complete_string		=	Request::query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string			=	http_build_query($complete_string);
		$model->appends(Request::all())->render();

		return  View::make("admin.$this->model.index", compact('model', 'searchVariable', 'sortBy', 'order', 'query_string'));
	} // end listContact()
	/**
	 * Function for display contact detail
	 *
@param $modelId as id of contact
	 *
	 * @return view page. 
	 */
	public function viewContact($modelId = 0)
	{
		if ($modelId) {
			$model	=	Contact::where('id', $modelId)->first();
			if (empty($model)) {
				return Redirect::route($this->model . '.index');
			}
			return  View::make("admin.$this->model.view", compact('model', 'modelId'));
		}
	} // end viewContact()
	/**
	 * Function for delete contact
	 * 
	 * @param $modelId as id 
	 *
	 * @return redirect page. 
	 */
	public function delete($modelId = 0)
	{
		if ($modelId) {
			$model = Contact::findorFail($modelId);
			$model->delete();
			Session::flash('flash_notice', trans("$this->model.deleted successfully"));
		}
		return Redirect::route("$this->model.index");
	} // end deleteContact()
	/**
	 * Function to reply a user 
	 * 
	 * @param $modelId as id 
	 *
	 * @return view page. 
	 */
	public function replyToUser($Id)
	{
		Request::replace($this->arrayStripTags(Request::all()));
		if (!empty(Request::all())) {
			$validationRules		=	array('message'	=> 'required|Regex:/\A(?!.*[:;]-\))[ -~]+\z/');
			$validator 				=	Validator::make(
				Request::all(),
				$validationRules
			);
			if ($validator->fails()) {
				return Redirect::back()->withErrors($validator)->withInput();
			} else {
				$userData			=	Contact::where('id', $Id)->first();
				##### send email to user from admin,to inform user that your message has been received successfully #####
				$emailActions		=	EmailAction::where('action', '=', 'replay_to_user')->get()->toArray();
				$emailTemplates		=	EmailTemplate::where('action', '=', 'replay_to_user')->get(array('name', 'subject', 'action', 'body'))->toArray();
				$cons 				=	explode(',', $emailActions[0]['options']);
				$constants 			=	array();
				foreach ($cons as $key => $val) {
					$constants[] 	=	'{' . $val . '}';
				}
				$name				=	$userData->name;
				$email				=	$userData->email;
				$message			=	Request::get('message');

				$subject 			=  	$emailTemplates[0]['subject'];
				$rep_Array 			=  	array($name, $message);
				$messageBody		=  	str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
				$this->sendMail($email, $name, $subject, $messageBody, Config::get("Site.contact_email"));
				Session::flash('success', 'You have Successfully replied to ' . $name);
				return Redirect::route("$this->model.index");
			}
		}
	} //end replyToUser()
}// end ContactsController
