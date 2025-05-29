<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Models\EmailTemplate;
use App\Models\EmailAction;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, mongoDate, Redirect, Request, Response, Session, URL, View, Validator;

/**
 * Emailtemplate Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/emailtemplates
 */

class EmailtemplateController extends BaseController
{

	public $model		=	'EmailTemplate';
	public $sectionName	=	'Email Template';

	public function __construct()
	{
		parent::__construct();
		View::share('modelName', $this->model);
		View::share('sectionName', $this->sectionName);
	}
	/**
	 * Function for display list of all email templates
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
	// public function listTemplate(){
	// 	$DB				=	EmailTemplate::query();
	// 	$searchVariable	=	array(); 
	// 	$inputGet		=	Request::get();
	// 	if (Request::get()){
	// 		$searchData	=	Request::get();
	// 		unset($searchData['display']);
	// 		unset($searchData['_token']);
	// 		if(isset($searchData['order'])){
	// 			unset($searchData['order']);
	// 		}
	// 		if(isset($searchData['sortBy'])){
	// 			unset($searchData['sortBy']);
	// 		}
	// 		if(isset($searchData['page'])){
	// 			unset($searchData['page']);
	// 		}

	// 		foreach($searchData as $fieldName => $fieldValue){
	// 			if($fieldValue != ""){
	// 				if($fieldName == "name"){
	// 					$DB->where("email_templates.name",'like','%'.$fieldValue.'%');
	// 				}
	// 				if($fieldName == "subject"){
	// 					$DB->where("email_templates.subject",'like','%'.$fieldValue.'%');
	// 				}
	// 			}
	// 			$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
	// 		}
	// 	}

	// 	$sortBy = (Request::get('sortBy')) ? Request::get('sortBy') : 'updated_at';
	//     $order  = (Request::get('order')) ? Request::get('order')   : 'DESC';

	// 	$result	 	= 	$DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));

	// 	$complete_string		=	Request::query();
	// 	unset($complete_string["sortBy"]);
	// 	unset($complete_string["order"]);
	// 	$query_string			=	http_build_query($complete_string);
	// 	$result->appends(Request::all())->render();

	// 	return  View::make('admin.emailtemplates.index', compact('result','searchVariable','sortBy','order','query_string'));
	// }// end listTemplate()


	public function listTemplate()
	{

		$id = Auth::user()->id;
		if (Auth::user()->user_role_id != SUPER_ADMIN_ROLE_ID) {
			Session::flash('error', trans("Sorry, you don't have access to this."));
			return Redirect::back();
		}

		$DB							=	EmailTemplate::query();
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
					if ($fieldName == "name") {
						$DB->where("email_templates.name", 'like', '%' . $fieldValue . '%');
					}
					if ($fieldName == "subject") {
						$DB->where("email_templates.subject", 'like', '%' . $fieldValue . '%');
					}
				}
				$searchVariable	=	array_merge($searchVariable, array($fieldName => $fieldValue));
			}
		}
		$sortBy 					= 	(Request::get('sortBy')) ? Request::get('sortBy') : 'updated_at';
		$order  					= 	(Request::get('order')) ? Request::get('order')   : 'DESC';
		$result 					= 	$DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		$complete_string			=	Request::query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string				=	http_build_query($complete_string);
		$result->appends(Request::all())->render();

		return  View::make('admin.emailtemplates.index', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string'));
	}
	/**
	 * Function for display page for add email template
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
	public function addTemplate()
	{
		$Action_options	=	EmailAction::pluck('action', 'action');
		return  View::make('admin.emailtemplates.add', compact('Action_options'));
	} // end addTemplate()
	/**
	 * Function for display save email template
	 *
	 * @param null
	 *
	 * @return redirect page. 
	 */
	public function saveTemplate()
	{
		Request::replace($this->arrayStripTags(Request::all()));
		$validator = Validator::make(
			Request::all(),
			array(
				'name' 			=> 'required|max:255|unique:email_templates',
				'subject' 		=> 'required|max:255|unique:email_templates',
				'action' 		=> 'required|unique:email_templates',
				'constants' 	=> 'required',
				'body' 			=> 'required'
			)
		);
		if ($validator->fails()) {
			return Redirect::to('/admin/email-manager/add-template')
				->withErrors($validator)->withInput();
		} else {
			EmailTemplate::insert(
				array(
					'name'		 	=> Request::get('name'),
					'subject' 		=> Request::get('subject'),
					'action' 		=> Request::get('action'),
					'body'			=> Request::get('body'),
					'created_at' 	=> DB::raw('NOW()'),
					'updated_at' 	=> DB::raw('NOW()')
				)
			);

			Session::flash('flash_notice', trans("Email template added successfully"));
			return Redirect::intended('/admin/email-manager');
		}
	} //  end saveTemplate()
	/**
	 * Function for display page for edit email template page
	 *
	 * @param $Id as id of email template
	 *
	 * @return view page. 
	 */
	public function editTemplate($Id)
	{
		$Action_options	=	EmailAction::pluck('action', 'action')->toArray();
		$emailTemplate	=	EmailTemplate::find($Id);
		if (empty($emailTemplate)) {
			return Redirect::to('/admin/email-manager');
		}
		return  View::make('admin.emailtemplates.edit', compact('Action_options', 'emailTemplate'));
	} // end editTemplate()
	/**
	 * Function for update email template
	 *
	 * @param $Id as id of email template
	 *
	 * @return redirect page. 
	 */
	public function updateTemplate($Id)
	{
		Request::replace($this->arrayStripTags(Request::all()));
		$validator = Validator::make(
			Request::all(),
			array(
				'name' 			=> "required|max:255|unique:email_templates,name,$Id",
				'subject' 		=> "required|max:255|unique:email_templates,subject,$Id",
				'body' 			=> 'required',
				'action' 		=> "required|unique:email_templates,action,$Id",
			)
		);
		if ($validator->fails()) {
			return Redirect::to('/admin/email-manager/edit-template/' . $Id)
				->withErrors($validator)->withInput();
		} else {
			EmailTemplate::where('id', $Id)
				->update(
					array(
						'name'		 	=> Request::get('name'),
						'subject' 		=> Request::get('subject'),
						'body'			=> Request::get('body'),
						'updated_at' 	=> DB::raw('NOW()')
					)
				);
			Session::flash('flash_notice', trans("Email template updated successfully"));
			return Redirect::intended('/admin/email-manager');
		}
	} // end updateTemplate()
	/**
	 * Function for get all  defined constant  for email template
	 *
	 * @param null
	 *
	 * @return all  constant defined for template. 
	 */
	public function getConstant()
	{
		if (Request::ajax() && Request::all()) {
			$constantName 	= 	Request::get('constant');
			$options		= 	EmailAction::where('action', '=', $constantName)->pluck('options', 'action');
			$a 				= 	explode(',', $options[$constantName]);
			echo json_encode($a);
		}
		exit;
	}
}
