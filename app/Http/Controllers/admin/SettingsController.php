<?php

/**
 * Settings Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/settings
 */

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, mongoDate, Redirect, Request, Response, Session, URL, View, Validator;
use App\Models\Setting;

class SettingsController extends BaseController
{


	public $model	=	'settings';

	public function __construct()
	{
		View::share('modelName', $this->model);
	}
	/**
	 * function for list all settings
	 *
	 * @param  null
	 * 
	 * @return view page
	 */
	public function listSetting()
	{
		$DB				=	Setting::query();
		$searchVariable	=	array();
		$inputGet		=	Request::all();
		if ($inputGet) {
			$searchData	=	Request::all();
			if (isset($searchData['order'])) {
				unset($searchData['order']);
			}
			if (isset($searchData['sortBy'])) {
				unset($searchData['sortBy']);
			}
			if (isset($searchData['page'])) {
				unset($searchData['page']);
			}

			unset($searchData['display']);
			unset($searchData['_token']);
			foreach ($searchData as $fieldName => $fieldValue) {
				if (!empty($fieldValue)) {
					$DB->where("$fieldName", 'like', '%' . $fieldValue . '%');
					$searchVariable	=	array_merge($searchVariable, array($fieldName => $fieldValue));
				}
			}
		}
		$sortBy = (Request::get('sortBy')) ? Request::get('sortBy') : 'id';
		$order  = (Request::get('order')) ? Request::get('order')   : 'ASC';
		$result = $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		return  View::make('admin.' . $this->model . '.index', compact('result', 'searchVariable', 'sortBy', 'order'));
	} // end listSetting()
	/**
	 * prefix function
	 *
	 * @param $prefix as prefix
	 * 
	 * @return void
	 */
	public function prefix($prefix = null)
	{
		$id = Auth::user()->id;
		$moduleName = $this->CheckAccess($id);
		if (!in_array("Settings", $moduleName) &&  Auth::user()->user_role_id != SUPER_ADMIN_ROLE_ID) {
			Session::flash('error', trans("Sorry, you don't have access to this."));
			return Redirect::back();
		}


		$result = Setting::where('key', 'like', $prefix . '%')->where('editable', 1)->orderBy('id', 'ASC')->get()->toArray();
		return  View::make('admin.' . $this->model . '.prefix', compact('result', 'prefix'));
	} // end prefix()
	/**
	 * update prefix function
	 *
	 * @param $prefix as prefix
	 * 
	 * @return void
	 */
	public function updatePrefix($prefix = null)
	{
		$allData				=	Request::all();
		$thisData				=	Request::all();
		//Request::replace($this->arrayStripTags($thisData));
		$allData				=	Request::all();
		//  echo "<pre>";
		// print_r($allData);die; 
		if (!empty($allData)) {
			if (!empty($allData['Setting'])) {
				foreach ($allData['Setting'] as $key => $value) {
					if (!empty($value["'id'"]) && !empty($value["'key'"])) {

						if ($value["'type'"] == 'checkbox') {
							$val	=	(isset($value["'value'"])) ? 1 : 0;
						} else {
							$val	=	(isset($value["'value'"])) ? $value["'value'"] : '';
						}
						$key = $value["'key'"];
						//$data = str_replace("Stripe.","",$key);   
						$data = str_replace("stripe.", "", $key);
						$data1 = str_replace("Paypal.", "", $key);
						if (strpos($key, 'stripe.') !== false) {
							if ($data == 'STRIPE_KEY' || $data == 'STRIPE_SECRET') {
								$key = str_replace("stripe.", "", $key);
								$key = str_replace("Stripe.", "", $key);
								//$key = str_replace(".","_",$key);    
								$key = strtoupper($key);
								$path = base_path('.env');
								if (file_exists($path)) {
									if (!empty(env($key))) {
										$old = env($key);
										File::put($path, str_replace("$key=" . $old, "$key=" . $val, File::get($path)));
									} else {
										File::put($path, File::get($path) . "\n\n" . "$key=" . $val . "\n");
									}
								}
							}
						}
						if (strpos($key, 'Paypal.') !== false) {
							$key = str_replace("Paypal.", "", $key);
							//$key = str_replace(".","_",$key);    
							$key = strtoupper($key);
							$path = base_path('.env');
							if (file_exists($path)) {
								if (!empty(env($key))) {
									$old = env($key);
									File::put($path, str_replace("$key=" . $old, "$key=" . $val, File::get($path)));
								} else {
									File::put($path, File::get($path) . "\n\n" . "$key=" . $val . "\n");
								}
							}
						}
						Setting::where('id', $value["'id'"])->update(array(
							'key'   	 		=>  $value["'key'"],
							'value' 			=>  $val
						));
					}
				}
			}
		}
		$this->settingFileWrite();
		Session::flash('flash_notice', 'Settings updated successfully.');
		return  Redirect::route($this->model . '.prefix', $prefix);
	} //updatePrefix()
	/**
	 * function add new settings view page
	 *
	 *@param null
	 * @return void
	 */
	public function addSetting()
	{
		return  View::make('admin.' . $this->model . '.add');
	} //end addSetting()
	/**
	 * function for save added new settings
	 *
	 *@param null
	 *
	 * @return void
	 */
	public function saveSetting()
	{

		$thisData				=	Request::all();
		Request::replace($this->arrayStripTags($thisData));
		$validator  = 	Validator::make(
			Request::all(),
			array(
				'title' 		=> 'required|max:255|Regex:/\A(?!.*[:;]-\))[ -~]+\z/',
				'key' 			=> 'required|Regex:/\A(?!.*[:;]-\))[ -~]+\z/',
				'value' 		=> 'required',
				'input_type' 	=> 'required'
			)
		);
		if ($validator->fails()) {
			return Redirect::route($this->model . '.add')
				->withErrors($validator)->withInput();
		} else {

			$obj	 = new Setting;

			$obj->title    			= Request::get('title');
			$obj->key   			= Request::get('key');
			$obj->value   			= Request::get('value');
			$obj->input_type   		= !empty(Request::get('input_type')) ? Request::get('input_type') : '';
			$obj->editable  		= !empty(Request::get('editable')) ? 1 : 0;

			$obj->save();
		}
		$this->settingFileWrite();
		Session::flash('flash_notice', 'Setting added successfully.');
		return Redirect::route($this->model . '.listSetting');
	} //end saveSetting()
	/**
	 * function edit settings view page
	 *
	 *@param $Id as Id
	 *
	 * @return void
	 */
	public function editSetting($Id)
	{
		$result			 = 	Setting::find($Id);
		if (empty($result)) {
			return Redirect::to('/dashboard');
		}
		return  View::make('admin.' . $this->model . '.edit', compact('result'));
	} //end editSetting()
	/**
	 * function for update setting
	 *
	 * @param $Id as Id
	 *
	 * @return void
	 */
	public function updateSetting($Id)
	{



		$thisData				=	Request::all();


		Request::replace($this->arrayStripTags($thisData));
		$validator  			= 	Validator::make(
			Request::all(),
			array(
				'title' 		=> 'required|max:255|Regex:/\A(?!.*[:;]-\))[ -~]+\z/',
				'key' 			=> 'required|Regex:/\A(?!.*[:;]-\))[ -~]+\z/',
				'value' 		=> 'required',
				'input_type' 	=> 'required'
			)
		);
		if ($validator->fails()) {
			return Redirect::route($this->model . '.edit', $Id)
				->withErrors($validator)->withInput();
		} else {
			$obj	 				=  Setting::find($Id);
			$obj->title    			= Request::get('title');
			$obj->key   			= Request::get('key');
			$obj->value   			= Request::get('value');
			$obj->input_type   		= !empty(Request::get('input_type')) ? Request::get('input_type') : '';
			$obj->editable  		= !empty(Request::get('editable')) ? 1 : 0;
			$obj->save();
		}
		$this->settingFileWrite();
		Session::flash('flash_notice', 'Setting updated successfully.');
		return Redirect::route($this->model . '.listSetting');
	} //end updateSetting()
	/**
	 * function for delete setting
	 *
	 * @param $Id as Id
	 *
	 * @return void
	 */
	public function deleteSetting($Id = 0)
	{
		if ($Id) {
			$obj	=  Setting::find($Id);
			$obj->delete();
			//$this->_delete_table_entry('settings',$Id,'id');
			Session::flash('flash_notice', 'Setting deleted successfully.');
		}
		$this->settingFileWrite();
		return Redirect::route($this->model . '.listSetting');
	} //end deleteSetting()
	/**
	 * function for write file on update and create
	 *
	 *@param $Id as Id
	 *
	 * @return void
	 */
	public function settingFileWrite()
	{
		$DB		=	Setting::query();
		$list	=	$DB->orderBy('key', 'ASC')->get(array('key', 'value'))->toArray();

		$file = SETTING_FILE_PATH;
		$settingfile = '<?php ' . "\n";
		foreach ($list as $value) {
			$val		  =	 str_replace('"', "'", $value['value']);
			/* if($value['key']=='Reading.records_per_page' || $value['key']=='Site.debug'){
				$settingfile .=  '$app->make('.'"config"'.')->set("'.$value['key'].'", '.$val.');' . "\n"; 
			}else{
				$settingfile .=  '$app->make('.'"config"'.')->set("'.$value['key'].'", "'.$val.'");' . "\n"; 
			} */

			$settingfile .=  'config::set("' . $value['key'] . '", "' . $val . '");' . "\n";
		}
		$bytes_written = File::put($file, $settingfile);
		if ($bytes_written === false) {
			die("Error writing to file");
		}
	} //end settingFileWrite()
}//end SettingsController class
