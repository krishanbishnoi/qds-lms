<?php

/**
 * Apis Controller
 */

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Models\Contact;
use App\Models\Streeks;
use App\Models\Games;
use App\Models\Answers;
use App\Models\StreekGames;
use App\Models\EmailAction;
use App\Models\EmailTemplate;
use App\Models\NewsLettersubscriber;
use App\Models\Address;
use App\Models\Payment;

use App\Models\StreekParticipant;
use App, Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, mongoDate, Redirect, Request, Response, Session, URL, View, Validator, Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class TransactionController extends BaseController
{

	public function TransactionHistory()
	{
		$formData	=	Request::all();
		$response	=	array();
		if (!empty($formData)) {
			$validator = Validator::make(
				Request::all(),
				array(
					"user_id"				=> "required"
				),
				array(
					"user_id.required" 		=>	trans("The user id is required."),
				)
			);

			if ($validator->fails()) {
				$val = array_column(json_decode(json_encode($validator->errors()), true), 0);
				$response["status"]			=	"error";
				$response["message"]		=	$val;
				$response["data"]			=	null;
			} else {
				$user_id	=  Request::get('user_id');
				$transactions_details					=	DB::table('transactions')->where('user_id', $user_id)->get();
				if (!empty($transactions_details)) {
					$response["status"]		=	"success";
					$response["message"]	=	"Transaction History Found successfully";
					$response["data"]		=	$transactions_details;
				} else {
					$response["status"]		=	"error";
					$response["message"]	=	"Details not found";
					$response["data"]		=	array();
				}
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"invalid request";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
}// end ApisController class