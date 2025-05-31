<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Models\Partner;
use App\Models\StateDescription;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Mail, Redirect, Response, Session, URL, View, Validator;
use Illuminate\Http\Request;

class PartnerController extends BaseController
{

	public $model		=	'Partner';
	public $sectionName	=	'Partner';
	public $sectionNameSingular	=	'Partner';

	public function __construct()
	{
		parent::__construct();
		View::share('modelName', $this->model);
		View::share('sectionName', $this->sectionName);
		View::share('sectionNameSingular', $this->sectionNameSingular);
	}


	public function index(Request $request)
	{
		$DB							=	Partner::query();
		$searchVariable				=	array();
		$inputGet					=	$request->all();
		if (($request->all())) {
			$searchData				=	$request->all();
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
						$DB->where("partners.is_active", $fieldValue);
					}
					if ($fieldName == "name") {
						$DB->where("partners.name", 'like', '%' . $fieldValue . '%');
					}
				}
				$searchVariable	=	array_merge($searchVariable, array($fieldName => $fieldValue));
			}
		}
		//$DB->where("areas.is_deleted",0);
		$sortBy 					= 	($request->get('sortBy')) ? $request->get('sortBy') : 'updated_at';
		$order  					= 	($request->get('order')) ? $request->get('order')   : 'DESC';
		$results 					= 	$DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		$complete_string			=	$request->query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string				=	http_build_query($complete_string);
		$results->appends($request->all())->render();
		return view("admin.Partner.index", compact('results', 'searchVariable', 'sortBy', 'order', 'query_string'));
	}

	public function add()
	{
		return view("admin.Partner.add");
	}

	function save(Request $request)
	{
		$request->replace($this->arrayStripTags($request->all()));

		$validator = Validator::make($request->all(), [
			'name'      => 'required',
			'location'  => 'required',
			'is_active'    => 'required',
		]);

		if ($validator->fails()) {
				return Redirect::back()->withErrors($validator)->withInput();
			}

		// Prepare data for updateOrCreate
		$partnerData = [
			'name'      => $request->name,
			'location'  => $request->location,
			'is_active'    => $request->is_active,
		];


		try {
			$partner = Partner::updateOrCreate(
				['id' =>  $request->id],
				$partnerData
			);

			if (!$partner) {
				Session::flash('error', __(config('constants.REC_ADD_FAILED')));
			} else {
				$message = $request->id ? __(config('constants.REC_UPDATE_SUCCESS'), ['section' => $this->sectionNameSingular])
					: __(config('constants.REC_ADD_SUCCESS'), ['section' => $this->sectionNameSingular]);
				Session::flash('success', $message);
			}
			return redirect()->route('Partner.index');
		} catch (\Exception $e) {
			Session::flash('error', __(config('constants.FLASH_TRY_CATCH')));
			return redirect()->route('Partner.index');
		}
	}
	public function changeStatus($modelId = 0, $status = 0)
	{
		if ($status == 0) {
			$statusMessage	=	trans($this->sectionNameSingular . " has been deactivated successfully");
		} else {
			$statusMessage	=	trans($this->sectionNameSingular . " has been activated successfully");
		}

		Partner::where('id', $modelId)->update(array('is_active' => $status));
		Session::flash('flash_notice', $statusMessage);
		return Redirect::back();
	}

	public function edit($modelId = 0)
	{
		$model				=	Partner::find($modelId);
		if (empty($model)) {
			Session::flash('error', __(config('constants.REC_NOT_FOUND')));
			return redirect()->route('Partner.index');
		}
		return view("admin.Partner.add", compact('model'));
	} 


	public function delete($id = 0)
	{
		$model	=	Partner::find($id);
		if (empty($model)) {
			return Redirect::back();
		}
		if ($id) {
			Partner::where('id', $id)->delete();
			Session::flash('flash_notice', trans($this->sectionNameSingular . " has been removed successfully"));
		}
		return Redirect::back();
	} 
}
