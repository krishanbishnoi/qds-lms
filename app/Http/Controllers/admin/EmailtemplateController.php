<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Models\EmailTemplate;
use App\Models\EmailAction;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, mongoDate, Redirect, Response, Session, URL, View, Validator;
use Illuminate\Http\Request;

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

	public function listTemplate(Request $request)
	{
		$id = Auth::user()->id;
		if (Auth::user()->user_role_id != SUPER_ADMIN_ROLE_ID) {
			Session::flash('error', trans("Sorry, you don't have access to this."));
			return Redirect::back();
		}
		$DB							=	EmailTemplate::query();
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
		$sortBy 					= 	($request->get('sortBy')) ? $request->get('sortBy') : 'updated_at';
		$order  					= 	($request->get('order')) ? $request->get('order')   : 'DESC';
		$result 					= 	$DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		$complete_string			=	$request->query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string				=	http_build_query($complete_string);
		$result->appends($request->all())->render();

		return view('admin.emailtemplates.index', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string'));
	}

	public function addTemplate()
	{
		$Action_options	=	EmailAction::pluck('action', 'action');
		return view('admin.emailtemplates.add', compact('Action_options'));
	}

	public function saveTemplate(Request $request)
	{
		$request->replace($this->arrayStripTags($request->all()));

		$rules = [
			'name'     => "required|max:255|unique:email_templates,name,{$request->id}",
			'subject'  => "required|max:255|unique:email_templates,subject,{$request->id}",
			'action'   => "required|unique:email_templates,action,{$request->id}",
			'body'     => 'required',
		];

		if (empty($request->id)) {
			$rules['constants'] = 'required';
		}

		$validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput();
		}
		try {
			$emailTemplate = EmailTemplate::updateOrCreate(
				['id' => $request->id],
				[
					'name'       => $request->name,
					'subject'    => $request->subject,
					'action'     => $request->action,
					'body'       => $request->body,
					'updated_at' => DB::raw('NOW()'),
					'created_at' => DB::raw('NOW()'),
				]
			);

			if (!$emailTemplate) {
				Session::flash('error', __(config('constants.REC_ADD_FAILED')));
			} else {
				$message = $request->id ? __(config('constants.REC_UPDATE_SUCCESS'))
					: __(config('constants.REC_ADD_SUCCESS'));
				Session::flash('success', $message);
			}
			return redirect()->route('EmailTemplate.index');
		} catch (\Exception $e) {
			Session::flash('error', __(config('constants.FLASH_TRY_CATCH')));
			return redirect()->route('EmailTemplate.index');
		}
	}

	public function editTemplate($Id)
	{
		$Action_options	=	EmailAction::pluck('action', 'action')->toArray();
		$emailTemplate	=	EmailTemplate::find($Id);
		if (empty($emailTemplate)) {
			return Redirect::to('/admin/email-manager');
		}
		return  View::make('admin.emailtemplates.add', compact('Action_options', 'emailTemplate'));
	}

	public function getConstant(Request $request)
	{
		if ($request->ajax() && $request->all()) {
			$constantName 	= 	$request->get('constant');
			$options		= 	EmailAction::where('action', '=', $constantName)->pluck('options', 'action');
			$a 				= 	explode(',', $options[$constantName]);
			echo json_encode($a);
		}
		exit;
	}
}
