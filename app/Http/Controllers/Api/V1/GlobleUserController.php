<?php

/**
 * Apis Controller
 */

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Models\Contact;
use App\Models\Issue;
use App\Models\EmailAction;
use App\Models\EmailTemplate;
use App\Models\NewsLettersubscriber;
use App\Models\Address;
use App, Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, mongoDate, Redirect, Request, Response, Session, URL, View, Validator, Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;



class GlobleUserController extends BaseController
{

	public function CmsPage()
	{
		$response	=	array();
		$CmsPage	=	DB::table("cms_pages")->where("is_active", 1)->get()->toArray();

		if ($CmsPage) {
			$response["status"]			=	"success";
			$response["message"]		=	"Cms details found successfully";
			$response["data"]			=	$CmsPage;
		} else {
			$response["status"]			=	"error";
			$response["message"]		=	"Plsease try after some time";
			$response["data"]			=	array();
		}
		return json_encode($response);
	}

	public function Faqs()
	{
		$formData	=	Request::all();
		$response	=	array();
		$Faqs		=	DB::table("faqs")->where("is_active", 1)->get()->toArray();

		if ($Faqs) {
			$response["status"]			=	"success";
			$response["message"]		=	"Faqs details found successfully";
			$response["data"]			=	$Faqs;
		} else {
			$response["status"]			=	"error";
			$response["message"]		=	"Plsease try after some time";
			$response["data"]			=	array();
		}

		return json_encode($response);
	}
	/**
	 * Function to submit subscriber form
	 *
	 * @param null
	 *
	 * @return response
	 */
	public function ReportAnIssue()
	{
		$formData	=	Request::all();
		$response	=	array();
		if (!empty($formData)) {
			$currentUser = Auth::user();
			$user_details					=	User::where('id', $currentUser->id)->first();
			if (!empty($user_details)) {
				$validator = Validator::make(
					Request::all(),
					array(

						"subject" 				=> "required",
						"issue" 				=> "required",
					)
				);
				if ($validator->fails()) {
					$response["status"]			=	"error";
					$response["message"]		=	$validator->errors();
					$response["data"]			=	null;
				} else {
					$obj					 = 	new Issue();
					$obj->user_id			=	$user_details->id;
					$obj->name				 =	$user_details->name;
					$obj->email				 =	$user_details->email;
					$obj->issue		 	 	 =	Request::get('issue');
					$obj->subject		 	 =	Request::get('subject');
					$obj->created_at		 =	date('Y-m-d H:i:s');
					$obj->updated_at		 =	date('Y-m-d H:i:s');
					$obj->save();
					//send email to site admin with user information,to inform that user wants to contact
					$emailActions		=  EmailAction::where('action', '=', 'issue_reported')->get()->toArray();
					$emailTemplates		=  EmailTemplate::where('action', '=', 'issue_reported')->get()->toArray();
					$cons 				=  explode(',', $emailActions[0]['options']);
					$constants 			=  array();
					foreach ($cons as $key => $val) {
						$constants[] = '{' . $val . '}';
					}
					$name				=	$user_details->name;
					$email				=	$user_details->email;
					$issue			=	nl2br(Request::get('issue'));
					$sub			=	Request::get('subject');
					$subject 			=  $emailTemplates[0]['subject'];
					$rep_Array 			=  array($name, $email, $sub, $issue);
					$messageBody		=  str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
					$settingsEmail 		= 	Config::get('Site.email');
					$this->sendMail(Config::get("Site.email"), 'Admin', $subject, $messageBody, $settingsEmail, $files = false);

					$response["status"]			=	"success";
					$response["message"]		=	"Issue  has been raised successfully";
					$response["data"]			=	array();
				}
			} else {
				$response["status"]		=	"error";
				$response["message"]	=	"Invalid Request.";
				$response["data"]		=	array();
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Login your account first.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}

	public function userNotification()
	{
		$formData	=	Request::all();
		$response	=	array();
		if (!empty($formData)) {
			$validator = Validator::make(
				Request::all(),
				array(
					"user_id" 		=> "required",
				)
			);
			if ($validator->fails()) {
				$response["status"]			=	"error";
				$response["message"]		=	$validator->errors();
				$response["data"]			=	null;
			} else {
				$user_id = Request::get('user_id');
				$notifications	=	DB::table("notifications")->where("user_id", $user_id)->get()->toArray();
				foreach ($notifications as $notification) {
					if ($notification->image != "" && File::exists(STREEKS_IMAGE_ROOT_PATH . $notification->image)) {
						$notification->image = STREEKS_IMAGE_URL . $notification->image;
					}
				}



				if ($notifications) {
					$response["status"]			=	"success";
					$response["message"]		=	"Notification details found successfully";
					$response["data"]			=	$notifications;
				} else {
					$response["status"]			=	"error";
					$response["message"]		=	"Plsease try after some time";
					$response["data"]			=	array();
				}
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
}// end ApisController class
