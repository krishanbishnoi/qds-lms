<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Models\Cms;
use App\Models\CmsDescription;
use App\Models\Language;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Redirect, Request, Response, Session, URL, View, Validator;

/**
 * Cms Controller
 * Add your methods in the class below
 * This file will render views from views/Cms
 */
class CmsPagesController extends BaseController
{
	public $model		=	'Cms';
	public $sectionName	=	'Cms Pages';
	public $sectionNameSingular  =  'Cms Page';
	public function __construct()
	{
		parent::__construct();
		View::share('modelName', $this->model);
		View::share('sectionName', $this->sectionName);
		View::share('sectionNameSingular', $this->sectionNameSingular);
	}
	/**
	 * Function for display all cms page
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
	public function listCms()
	{
		$id = Auth::user()->id;
		if (Auth::user()->user_role_id != SUPER_ADMIN_ROLE_ID) {
			Session::flash('error', trans("Sorry, you don't have access to this."));
			return Redirect::back();
		}

		$DB							=	Cms::query();
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
				if (!empty($fieldValue)) {
					$DB->where("$fieldName", 'like', '%' . $fieldValue . '%');
					$searchVariable	=	array_merge($searchVariable, array($fieldName => $fieldValue));
				}
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

		return  View::make('admin.Cms.index', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string'));
	}
	/**
	 * Function for display page  for add new cms page 
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
	public function addCms()
	{
		return  View::make('admin.Cms.add');
	}
	/**
	 * Function for save added cms page
	 *
	 * @param null
	 *
	 * @return redirect page. 
	 */
	function saveCms()
	{
		Request::replace($this->arrayStripTags(Request::all()));
		$thisData					=	Request::all();
		$validator = Validator::make(
			$thisData,
			array(
				'title' 				=> 'required|max:255|Regex:/\A(?!.*[:;]-\))[ -~]+\z/|unique:cms_pages',
				'name' 					=> 'required|Regex:/\A(?!.*[:;]-\))[ -~]+\z/|max:255',
				'description' 			=> 'required',
				'image' 				=> 'required|mimes:jpeg,jpg,png,gif',
				/* 'meta_title' 		=> 'required',
				'meta_description' 	=> 'required',
				'meta_keywords' 		=> 'required' */
			),
			array(

				"description.Regex"			=>	trans("The description format is invalid."),
				"name.Regex"			=>	trans("The  name format is invalid."),

			)
		);

		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput();
		} else {
			$cms 					= new Cms;
			$cms->name    			= Request::get('name');
			$cms->title   			= Request::get('title');
			$cms->slug   			= $this->getSlug(Request::get('title'), 'title', 'Cms');
			$cms->description   			= Request::get('description');

			if (Request::hasFile('image')) {
				$extension 	=	 Request::file('image')->getClientOriginalExtension();


				$fileName	=	time() . '-cms.' . $extension;

				$folderName     	= 	strtoupper(date('M') . date('Y')) . "/";
				$folderPath			=	CMS_IMAGE_ROOT_PATH . $folderName;
				if (!File::exists($folderPath)) {
					File::makeDirectory($folderPath, $mode = 0777, true);
				}
				if (Request::file('image')->move($folderPath, $fileName)) {
					$cms->image	=	$folderName . $fileName;
				}
			}

			// $cms->meta_title   	= Request::get('meta_title');
			// $cms->meta_description  = Request::get('meta_description');
			// $cms->meta_keywords   	= Request::get('meta_keywords');

			$cmspags				=	$cms->save();
			if (!$cmspags) {
				Session::flash('error', trans("Something went wrong."));
				return Redirect::back()->withInput();
			} else {

				Session::flash('flash_notice', trans("Cms page added successfully"));
				return Redirect::to('admin/cms-manager');
			}
		}
	}
	/**
	 * Function for display page  for edit cms page
	 *
	 * @param $Id ad id of cms page
	 *
	 * @return view page. 
	 */
	public function editCms($Id)
	{
		$Cms				=	Cms::find($Id);
		if (empty($Cms)) {
			return Redirect::to('admin/cms-manager');
			Session::flash('error', trans("Something went wrong."));
		}

		return  View::make('admin.Cms.edit', array('adminCmspage' => $Cms));
	}
	/**
	 * Function for update cms page
	 *
	 * @param $Id ad id of cms page
	 *
	 * @return redirect page. 
	 */
	function updateCms($modelId)
	{
		$model					=	Cms::findorFail($modelId);
		if (empty($model)) {
			return Redirect::back();
		}

		Request::replace($this->arrayStripTags(Request::all()));
		$thisData					=	Request::all();
		//echo '<pre>'; print_r($thisData); die;

		$validator = Validator::make(
			$thisData,
			array(
				'title' 				=> "required|max:255|Regex:/\A(?!.*[:;]-\))[ -~]+\z/|unique:cms_pages,title," . $modelId,
				'name' 			=> 'required|Regex:/\A(?!.*[:;]-\))[ -~]+\z/|max:255',
				'description' 				=> 'required',
				'image' 			=> 'nullable|mimes:jpeg,jpg,png,gif',
				/* 'meta_title' 		=> 'required',
			'meta_description' 	=> 'required',
			'meta_keywords' 		=> 'required' */
			),
			array(

				"description.Regex"			=>	trans("The description format is invalid."),
				"name.Regex"			=>	trans("The  name format is invalid."),
			)
		);
		if ($validator->fails()) {
			return Redirect::back()
				->withErrors($validator)->withInput();
		} else {
			$cms = $model;
			$cms->name    		= Request::get('name');
			$cms->title   			= Request::get('title');
			$cms->description   			= Request::get('description');

			if (Request::hasFile('image')) {
				$extension 	=	 Request::file('image')->getClientOriginalExtension();


				$fileName	=	time() . '-cms.' . $extension;

				$folderName     	= 	strtoupper(date('M') . date('Y')) . "/";
				$folderPath			=	CMS_IMAGE_ROOT_PATH . $folderName;
				if (!File::exists($folderPath)) {
					File::makeDirectory($folderPath, $mode = 0777, true);
				}
				if (Request::file('image')->move($folderPath, $fileName)) {
					$cms->image	=	$folderName . $fileName;
				}
			}

			$cmspags				=	$cms->save();
			if (!$cmspags) {

				Session::flash('error', trans("Something went wrong."));
				return Redirect::route($this->model . ".index");
			} else {
				Session::flash('success', trans($this->sectionNameSingular . " has been added successfully"));
				return Redirect::route($this->model . ".index");
			}
		}
	} // end update()
	/**
	 * Function for update cms page status
	 *
	 * @param $Id as id of cms page
	 * @param $Status as status of cms page
	 *
	 * @return redirect page. 
	 */
	public function updateCmsStatus($Id = 0, $Status = 0)
	{
		if ($Status == 0) {
			$statusMessage	=	trans("Cms page deactivated successfully");
		} else {
			$statusMessage	=	trans("Cms page activated successfully");
		}
		$this->_update_all_status('cms_pages', $Id, $Status);
		Session::flash('flash_notice',  $statusMessage);
		return Redirect::to('admin/cms-manager');
	}
}
