<?php

/**
 * Apis Controller
 */

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Models\EmailAction;
use App\Models\EmailTemplate;
use App\Models\NewsLettersubscriber;
use App\Models\Contact;
use App\Models\Slider;
use App\Models\OtpVerification;
use App\Models\Category;
use App\Models\Properties;
use App\Models\Block;
use App\Models\DeviceToken;
use App\Models\Testimonial;
use App\Models\TestimonialDescription;
use App\Models\RandomBanners;
use App\Models\ResumeRequests;
use App\Models\ImageIssue;
use App\Models\Avatar;
use DateTime;
use App, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, mongoDate, Redirect, Request, Response, Session, URL, View, Validator, Carbon;
use Intervention\Image\Facades\Image as Image;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Http\Controllers\Controller;


class UsersController extends BaseController
{

	/**
	 * Function use for register a user
	 *
	 * @param null
	 *
	 * @return response
	 */
	public function Signup()
	{
		$formData	=	Request::all();
		$response	=	array();
		if (!empty($formData)) {
			//if(Request::get('user_role_id') == CUSTOMER_ROLE_ID){
			$mobile_number				=	!empty(Request::get("mobile_number")) ? Request::get("mobile_number") : '';
			$mobile_number				= 	!empty(Request::get("mobile_number")) ? preg_replace("/[^0-9]/", "", $mobile_number) : '';
			Validator::extend("custom_password", function ($attribute, $value, $parameters) {
				if (preg_match("#[0-9]#", $value) && preg_match("#[a-zA-Z]#", $value)) {
					return true;
				} else {
					return false;
				}
			});
			Validator::extend("min_phone_field", function ($attribute, $value, $parameters) {
				if (strlen(strlen($parameters[0]) == 10)) {
					return true;
				} else {
					return false;
				}
			});

			$validator = Validator::make(
				Request::all(),
				array(
					//'mobile_number' 					=> 	 "required|numeric|unique:users,mobile_number|min_phone_field:$mobile_number",
					"date_of_birth"						=>	 "required",
					"bio"						=>	 "required",
					"first_name"						=>	 "required",
					"last_name"							=>	 "required",
					'username'						=> 'required|max:100|unique:users',
					"email" 							=>	 "required|email|unique:users",
					'mobile_number' 					=> 'required|numeric|unique:users',
					"password"							=>	 "required|min:8|custom_password",
					"confirm_password"					=>	 "required|same:password",
					"image"						=>	 "required",
				),
				array(
					"password.custom_password"			=>	trans("Password must have be a combination of numeric and alphabets."),
					"password.required"					=>	trans("The password field is required"),
					"confirm_password.required"			=>	trans("The confirm password field is required"),
					"confirm_password.same"				=>	trans("Password and confirm password must match."),
					"password.min"						=>	trans("Password must have minimum of 8 characters."),
					"email.required"					=>	trans("The email field is required"),
					"email.unique"						=>	trans("This email already exist."),
					"mobile_number.unique"						=>	trans("This mobile number already exist."),
					//"iagree.required"					=>	trans("Please accept tems of use."),
					"mobile_number.min_phone_field"			=>	trans("Mobile number must be 10 digits"),
				)
			);



			if ($validator->fails()) {
				$val = array_column(json_decode(json_encode($validator->errors()), true), 0);
				$response["status"]			=	"error";
				//	$response["message"]		=	$validator->errors();
				$response["message"]		=	$validator->errors()->first();
				$response["data"]			=	null;
			} else {
				$obj 		=  new User;
				$validateString							=  md5(time() . Request::get("email"));
				$obj->user_role_id						=  2;
				$obj->first_name						=  Request::get("first_name");
				$first_name = Request::get("first_name");
				$last_name = Request::get("last_name");
				$fullname = $first_name . " " . $last_name;
				$obj->fullname							=  $fullname;
				$obj->last_name							=  Request::get("last_name");
				$obj->mobile_number						=  Request::get("mobile_number");
				$obj->username							=  Request::get("username");
				$obj->email								=  Request::get("email");
				$obj->bio								=  Request::get("bio");
				$obj->date_of_birth								=  Request::get("date_of_birth");
				$obj->password	 						= 	Hash::make(Request::get('password'));
				$obj->is_active							=  1;
				$obj->is_email_verified						=  0;
				$obj->is_mobile_verified						=  0;
				$obj->validate_string					=  $validateString;
				$obj->forgot_password_validate_string	=  "";
				$obj->is_deleted						=  0;
				$obj->remember_token					=  "";
				$obj->device_token							=  !empty(Request::get('device_token')) ? Request::get('device_token') : '';
				$obj->device_id							=  !empty(Request::get('device_id')) ? Request::get('device_id') : '';
				$obj->device_type						=  !empty(Request::get('device_type')) ? Request::get('device_type') : '';
				$obj->image		=  Request::get('image');
				if (Request::hasFile('image')) {
					$extension 	=	 Request::file('image')->getClientOriginalExtension();
					$fileName	=	time() . '-image.' . $extension;

					$folderName     	= 	strtoupper(date('M') . date('Y')) . "/";
					$folderPath			=	USER_IMAGE_ROOT_PATH . $folderName;
					if (!File::exists($folderPath)) {
						File::makeDirectory($folderPath, $mode = 0777, true);
					}
					if (Request::file('image')->move($folderPath, $fileName)) {
						$obj->image	=	$folderName . $fileName;
					}
				}

				$obj->save();
				$userId							=	$obj->id;
				$email							=	$obj->email;
				if (!$userId) {

					$response["status"]					=	"error";
					$response["message"]				=	"Something went wrong.";
					$response["data"]					=	array();
				} else {
					$otp_number 		= 	mt_rand(1000, 9999);
					if (!empty($otp_number)) {
						$record					=	OtpVerification::where('email', Request::get("email"))->first();
						if ($record) {
							$otp				=	OtpVerification::find($record->id);
						} else {
							$otp				=	new OtpVerification();
						}
						$otp->email				=	!empty(Request::get('email')) ? Request::get('email') : '';
						$otp->otp				=	$otp_number;
						$otp->save();
					}

					$full_name          =  	$obj->first_name;
					$email		        =	$obj->email;
					$settingsEmail 		= 	Config::get('Site.email');
					$emailActions		= 	EmailAction::where('action', '=', 'user_otp_verification')->get()->toArray();
					$emailTemplates		= 	EmailTemplate::where('action', '=', 'user_otp_verification')->get(array('name', 'subject', 'action', 'body'))->toArray();
					$cons 				= 	explode(',', $emailActions[0]['options']);
					$constants 			= 	array();
					foreach ($cons as $key => $val) {
						$constants[] 	= '{' . $val . '}';
					}
					$subject 			= 	$emailTemplates[0]['subject'];
					$rep_Array 			= 	array($full_name, $otp_number);
					$messageBody		= 	str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
					$mail				= 	$this->sendMail($email, $full_name, $subject, $messageBody, $settingsEmail);


					$user_details						=	DB::table('users')->where('id', $userId)->first();
					$otp_number							=	DB::table('otp_verifications')->where('email', Request::get("email"))->pluck('otp')->first();
					$response["otp"]					=	$otp_number;
					$response["status"]					=	"success";
					$response["message"]				=	"An otp will be sent to your email ,please enter to verify";
					$response["data"]					=	$user_details;
				}
			}
		} else {
			$response["status"]			=	"error";
			$response["message"]		=	"Invalid Request.";
			$response["data"]			=	array();
		}
		return json_encode($response);
	}

	/**
	 * Function use for to login user
	 *
	 * @param null
	 *
	 * @return response
	 */
	public function Login()
	{
		$formData	=	Request::all();
		$response	=	array();
		if (!empty($formData)) {
			$mobile_number				=	!empty(Request::get("mobile_number")) ? Request::get("mobile_number") : '';
			$mobile_number				= 	!empty(Request::get("mobile_number")) ? preg_replace("/[^0-9]/", "", $mobile_number) : '';
			Validator::extend("min_phone_field", function ($attribute, $value, $parameters) {
				if (strlen(strlen($parameters[0]) == 10)) {
					return true;
				} else {
					return false;
				}
			});

			$validator = Validator::make(
				Request::all(),
				array(
					'email' 		=> 'required',
					"password"		=>  "required",
				),
				array(
					"email.required"	=>  trans("This email is required."),
					"password.required"	=>  trans("This password is required."),
				)
			);
			if ($validator->fails()) {
				$response["status"]			=	"error";
				$response["message"]		=	$validator->errors()->first();
				$response["data"]			=	null;
			} else {
				$email				=	Request::get("email");
				$userData			=	DB::table("users")
					->where("email", $email)
					->where("is_deleted", 0)
					->first();
				//echo '<pre>'; print_r($userData); die;				
				if (!empty($userData)) {
					if ($userData->is_email_verified == 1) {
						if ($userData->is_mobile_verified == 1) {
							$email = $userData->email;

							$userData		=	json_decode(json_encode($userData, true), true);

							if ($userData["is_active"] == 0) {
								$response["status"]			=	"inactive";
								$response["message"]		=	"Your account is inactive please contact to admin.";
								$response["data"]			=	array();
							} else {
								if (Hash::check(Request::get("password"), $userData['password'])) {

									$userDetails	=	DB::table("users")
										->where("email", $email)
										->where("is_deleted", 0)
										->where("is_email_verified", 1)
										->where("is_mobile_verified", 1)
										->first();
									if ($userDetails->image != "" && File::exists(USER_IMAGE_ROOT_PATH . $userDetails->image)) {
										$userDetails->image = USER_IMAGE_URL . $userDetails->image;
									}
									//echo "<pre>";print_r($user);die;	

									if (!empty($userDetails)) {
										if ($userDetails->user_role_id != SUPER_ADMIN_ROLE_ID) {
											$device_token							=  !empty(Request::get('device_token')) ? Request::get('device_token') : '';
											$device_id							=  !empty(Request::get('device_id')) ? Request::get('device_id') : '';
											$device_type						=  !empty(Request::get('device_type')) ? Request::get('device_type') : '';


											$userData = array(
												"email" 			=> $userData['email'],
												"password" 			=> Request::get("password"),
											);

											if (! $token = auth('api')->attempt($userData)) {
												return response()->json(['error' => ' '], 401);
											}

											$response["status"]		=	"success";
											$response["message"]	=	"You are now logged in!";
											$response["token"]		=	$token;
										} else {
											$response["status"]		=	"error";
											$response["message"]	=	"Unauthorized.";
											$response["data"]		=	array();
										}
									} else {
										$response["status"]		=	"error";
										$response["message"]	=	"Username or Password is incorrect.";
										$response["data"]		=	array();
									}
								} else {
									$response["status"]		=	"error";
									$response["message"]	=	"Password is incorrect.";
									$response["data"]		=	array();
								}
							}
						} else {

							$otp_number 		= 	mt_rand(1000, 9999);
							if (!empty($otp_number)) {
								$record					=	OtpVerification::where('mobile_number', $userData->mobile_number)->first();
								if ($record) {
									$otp				=	OtpVerification::find($record->id);
								} else {
									$otp				=	new OtpVerification();
								}
								$otp->mobile_number		=	$userData->mobile_number;
								$otp->otp				=	$otp_number;
								$otp->save();
							}

							$response["status"]			=	"success";
							$response["message"]		=	"Your mobile number is not verified yet,we have send an otp to your mobile number please enter to verify.";

							$response["is_email_verified"]			=	$userData->is_email_verified;
							$response["is_mobile_verified"]			=	$userData->is_mobile_verified;
							$response["otp"]		=	$otp_number;
						}
					} else {
						$otp_number 		= 	mt_rand(1000, 9999);
						if (!empty($otp_number)) {
							$record					=	OtpVerification::where('email', $userData->email)->first();
							if ($record) {
								$otp				=	OtpVerification::find($record->id);
							} else {
								$otp				=	new OtpVerification();
							}
							$otp->email		=	!empty($userData->email) ? $userData->email : '';
							$otp->otp				=	$otp_number;
							$otp->save();
						}

						//Send Verification Email
						$settingsEmail 						= 	Config::get('Site.email');
						$full_name          =   $userData->fullname;
						$email		        =	$userData->email;

						$settingsEmail 		= 	Config::get('Site.email');
						//$route_url      	=	FRONT_WEBSITE_URL.'login';
						//$click_link   	=   '<a href="'.$route_url.'" class="btn btn-primary signin-btn" >Click Here</a>';
						$emailActions	= 	EmailAction::where('action', '=', 'user_otp_verification')->get()->toArray();
						$emailTemplates	= 	EmailTemplate::where('action', '=', 'user_otp_verification')->get(array('name', 'subject', 'action', 'body'))->toArray();
						$cons 			= 	explode(',', $emailActions[0]['options']);
						$constants 		= 	array();
						foreach ($cons as $key => $val) {
							$constants[] = '{' . $val . '}';
						}
						$subject 		= 	$emailTemplates[0]['subject'];
						$rep_Array 			= 	array($full_name, $otp_number);
						$messageBody	= 	str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
						//echo '<pre>'; print_r($messageBody); die;
						$mail			= 	$this->sendMail($email, $full_name, $subject, $messageBody, $settingsEmail);

						$response["status"]			=	"success";
						$response["message"]		=	"Your email is not verified yet,we have send an otp to your email please enter to verify.";
						$response["is_email_verified"]			=	$userData->is_email_verified;
						$response["is_mobile_verified"]			=	$userData->is_mobile_verified;
						$response["data"]			=	array();
					}
				} else {
					$response["status"]			=	"error";
					$response["message"]		=	"Your account is not registered with " . ucfirst(Config::get("Site.title"));
					$response["data"]			=	array();
				}
			}
		} else {
			$response["status"]			=	"error";
			$response["message"]		=	"Invalid Request.";
			$response["data"]			=	array();
		}
		return json_encode($response);
	}

	/**
	 * Function use for to send  otp
	 *
	 * @param null
	 *
	 * @return response
	 */

	public function SendMobileOtp()
	{
		$formData	=	Request::all();
		$response	=	array();
		if (!empty($formData)) {
			$mobile_number	= 	!empty(Request::get("mobile_number")) ? Request::get("mobile_number") : '';
			$name	= 	!empty(Request::get("name")) ? Request::get("name") : '';
			$validator = Validator::make(
				Request::all(),
				array(
					"mobile_number" 	=> "required",
					//"name" 				=> "required"
				)
			);
			if ($validator->fails()) {
				$response["status"]			=	"error";
				$response["message"]		=	$validator->errors()->first();
				$response["data"]			=	null;
			} else {
				$otp_number 		= 	mt_rand(1000, 9999);
				if (!empty($otp_number)) {
					$record					=	OtpVerification::where('mobile_number', $mobile_number)->first();
					if ($record) {
						$otp				=	OtpVerification::find($record->id);
					} else {
						$otp				=	new OtpVerification();
					}
					$otp->mobile_number		=	$mobile_number;
					$otp->otp				=	$otp_number;
					$otp->save();

					// if(!empty($mobile_number)){
					// 	$name = $name;

					// 	if(Request::get("type") == 'forgetpassword'){
					// 		$message		=	"Hello $name\nPlease enter this otp :$otp_number to reset your password.";
					// 	}else{
					// 		$message		=	"Hello $name\nPlease enter this otp :$otp_number to verify your account.";
					// 	}
					// 	$messgae		= $this->sendSMS($message,$mobile_number);
					// }
					$response["otp"]		=	$otp_number;
					$response["status"]		=	"success";
					$response["message"]	=	"OTP sent successfully";
				} else {

					$response["status"]		=	"error";
					$response["message"]	=	"Error in generating otp";
					$response["data"]		=	null;
				}
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}


	/**
	 * Function use for to send email otp
	 *
	 * @param null
	 *
	 * @return response
	 */
	public function SendEmailOtp()
	{
		$formData	=	Request::all();
		$response	=	array();
		if (!empty($formData)) {
			$email	= 	!empty(Request::get("email")) ? Request::get("email") : '';
			$name	= 	!empty(Request::get("name")) ? Request::get("name") : '';
			$validator = Validator::make(
				Request::all(),
				array(
					"type" 				=> "required",
					"email" 	=> "required|email",
					//	"name" 				=> "required"
				)
			);
			if ($validator->fails()) {
				$response["status"]			=	"error";
				$response["message"]		=	$validator->errors()->first();
				$response["data"]			=	null;
			} else {
				$otp_number 		= 	mt_rand(1000, 9999);
				if (!empty($otp_number)) {
					$record					=	OtpVerification::where('email', $email)->first();
					if ($record) {
						$otp				=	OtpVerification::find($record->id);
					} else {
						$otp				=	new OtpVerification();
					}
					$otp->email				=	$email;
					$otp->otp				=	$otp_number;
					$otp->save();


					$full_name          =  	$name;
					$email		        =	$email;
					$settingsEmail 		= 	Config::get('Site.email');

					if (Request::get("type") == 'forgetpassword') {
						$emailActions		= 	EmailAction::where('action', '=', 'forget_password_otp')->get()->toArray();
						$emailTemplates		= 	EmailTemplate::where('action', '=', 'forget_password_otp')->get(array('name', 'subject', 'action', 'body'))->toArray();
					} else if (Request::get("type") == 'signup') {
						$emailActions		= 	EmailAction::where('action', '=', 'user_otp_verification')->get()->toArray();
						$emailTemplates		= 	EmailTemplate::where('action', '=', 'user_otp_verification')->get(array('name', 'subject', 'action', 'body'))->toArray();
					}
					$cons 				= 	explode(',', $emailActions[0]['options']);
					$constants 			= 	array();
					foreach ($cons as $key => $val) {
						$constants[] 	= '{' . $val . '}';
					}
					$subject 			= 	$emailTemplates[0]['subject'];
					$rep_Array 			= 	array($full_name, $otp_number);
					$messageBody		= 	str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
					$mail				= 	$this->sendMail($email, $full_name, $subject, $messageBody, $settingsEmail);


					// if(!empty($mobile_number)){
					// 	$name = $name;

					// 	if(Request::get("type") == 'forgetpassword'){
					// 		$message		=	"Hello $name\nPlease enter this otp :$otp_number to reset your password.";
					// 	}else{
					// 		$message		=	"Hello $name\nPlease enter this otp :$otp_number to verify your account.";
					// 	}
					// 	$messgae		= $this->sendSMS($message,$mobile_number);
					// }
					$response["otp"]		=	$otp_number;
					$response["status"]		=	"success";
					$response["message"]	=	"OTP sent successfully";
					//$response["otp"]		=	null;
				} else {

					$response["status"]		=	"error";
					$response["message"]	=	"Error in generating otp";
					$response["data"]		=	null;
				}
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}


	/**
	 * Function use for to verify email 0tp 
	 *
	 * @param null
	 *
	 * @return response
	 */
	public function verifyMobileOtp()
	{
		$formData	=	Request::all();
		$response	=	array();
		if (!empty($formData)) {
			$mobile_number	= 	!empty(Request::get("mobile_number")) ? Request::get("mobile_number") : '';
			$otp_number		=	Request::get('otp');

			$validator = Validator::make(
				Request::all(),
				array(
					"type" 				=> "required",
					"mobile_number" 	=> "required",
					"otp"				=> "required"
				),
				array(
					"mobile_number.required" 	=>	trans("The mobile number is required."),
					"otp.required" 		=>	trans("The otp is required."),
				)
			);
			if ($validator->fails()) {
				$val = array_column(json_decode(json_encode($validator->errors()), true), 0);
				$response["status"]			=	"error";
				//	$response["message"]		=	$validator->errors();
				$response["message"]		=	$val;
				$response["data"]			=	null;
			} else {
				$checkDetails				=	OtpVerification::where('mobile_number', $mobile_number)->first();

				if (!empty($checkDetails)) {
					$savedOtp	=	$checkDetails->otp;
					if ($otp_number == $savedOtp) {
						if (Request::get("type") == 'forgetpassword') {
							$response["status"]		=	"success";
							$response["message"]	=	"Otp varification successful.";
							$response["data"]		=	1;
						} else if (Request::get("type") == 'signup') {
							User::where('mobile_number', $mobile_number)->update(array('is_mobile_verified' => '1'));
							$response["status"]		=	"success";
							$response["message"]	=	"Otp varification successful.";
							$response["data"]		=	1;
						}
					} else {
						$response["status"]		=	"error";
						$response["message"]	=	"Invalid OTP";
						$response["data"]		=	array();
					}
				} else {
					$response["status"]		=	"error";
					$response["message"]	=	"Invalid OTP";
					$response["data"]		=	array();
				}
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	/**
	 * Function use for to verify email 0tp 
	 *
	 * @param null
	 *
	 * @return response
	 */
	public function verifyEmailOtp()
	{
		$formData	=	Request::all();
		$response	=	array();
		if (!empty($formData)) {
			$email	= 	!empty(Request::get("email")) ? Request::get("email") : '';
			$otp_number		=	Request::get('otp');

			$validator = Validator::make(
				Request::all(),
				array(
					"type" 				=> "required",
					"email" 	=> "required|email",
					"otp"				=> "required"
				),
				array(
					"email.required" 	=>	trans("The email is required."),
					"otp.required" 		=>	trans("The otp is required."),
				)
			);
			if ($validator->fails()) {
				$val = array_column(json_decode(json_encode($validator->errors()), true), 0);
				$response["status"]			=	"error";
				//	$response["message"]		=	$validator->errors();
				$response["message"]		=	$val;
				$response["data"]			=	null;
			} else {
				$checkDetails				=	OtpVerification::where('email', $email)->first();


				if (!empty($checkDetails)) {
					$savedOtp	=	$checkDetails->otp;


					if ($otp_number == $savedOtp) {
						if (Request::get("type") == 'forgetpassword') {
							$response["status"]		=	"success";
							$response["message"]	=	"Otp varification successful.";
							$response["data"]		=	1;
						} else if (Request::get("type") == 'signup') {
							User::where('email', $email)->update(array('is_email_verified' => '1'));
							$response["status"]		=	"success";
							$response["message"]	=	"Otp varification successful.";
							$response["data"]		=	1;
						}
					} else {
						$response["status"]		=	"error";
						$response["message"]	=	"Invalid OTP";
						$response["data"]		=	array();
					}
				} else {
					$response["status"]		=	"error";
					$response["message"]	=	"Invalid OTP";
					$response["data"]		=	array();
				}
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}

	/**
	 * Function use for Change Password
	 *
	 * @param null
	 *
	 * @return response
	 */
	public function ChangePassword()
	{

		$currentUser = Auth::user();
		$response	=	array();
		if (!empty($currentUser)) {
			$formData	=	Request::all();
			if (!empty($formData)) {
				Validator::extend('custom_password', function ($attribute, $value, $parameters) {
					if (preg_match('#[0-9]#', $value) && preg_match('#[a-zA-Z]#', $value) && preg_match('#[\W]#', $value)) {
						return true;
					} else {
						return false;
					}
				});
				$validator = Validator::make(
					Request::all(),
					array(

						'new_password'			=> 	 'required|min:8|custom_password',
						'confirm_password'  	=> 	 'required|same:new_password',
						'old_password'	  		=> 	 'required',
					),
					array(
						'new_password.custom_password'	=>	trans("Password must have be a combination of numeric, alphabet and special characters.")
					)
				);
				if ($validator->fails()) {
					$response["status"]			=	"error";
					$response["message"]		=	$validator->errors()->first();
					$response["data"]			=	null;
				} else {

					$old_password 			= Request::get('old_password');

					if (Hash::check($old_password, $currentUser->password)) {
						$password 					= Request::get('new_password');
						$currentUser->password 		= Hash::make($password);
						if ($currentUser->save()) {
							$response["status"]		=	"success";
							$response["message"]	=	"Password changed successfully.";
							$response["data"]		=	array();
						} else {
							$response["status"]		=	"error";
							$response["message"]	=	"Something went wrong. Please try again.";
							$response["data"]		=	array();
						}
					} else {
						$response["status"]		=	"error";
						$response["message"]	=	"Your old password is incorrect.";
						$response["data"]		=	array();
					}
				}
			} else {
				$response["status"]		=	"error";
				$response["message"]	=	"Invalid Request.";
				$response["data"]		=	array();
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Login your account first().";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	/**
	 * Function use for forget password
	 *
	 * @param null
	 *
	 * @return response
	 */
	public function ForgetPassword()
	{
		$formData	=	Request::all();
		$response	=	array();
		if (!empty($formData)) {
			$validator 	=	Validator::make(
				Request::all(),
				array(
					//'mobile_number' 	=> 'required|numeric|digits_between:7,15',
					'email' 			=> 'required',
				),
				array(
					'email.required' 	=> trans('The email field is required.'),
				)
			);
			if ($validator->fails()) {
				$response["status"]			=	"error";
				$response["message"]		=	$validator->errors()->first();
				$response["data"]			=	null;
			} else {
				$email		=	Request::get('email');
				$userDetail	=	DB::table("users")
					->where(function ($query) use ($email) {
						//$query->orWhere("mobile_number",$email);
						$query->orWhere("email", $email);
					})
					->where("is_deleted", 0)
					->select('is_active', 'email', 'first_name', 'mobile_number', 'is_email_verified', 'is_mobile_verified')
					->first();
				if (!empty($userDetail)) {
					if ($userDetail->is_active == 1) {
						if ($userDetail->is_email_verified == 1) {
							if ($userDetail->is_mobile_verified == 1) {
								$otp_number 		= 	mt_rand(1000, 9999);
								if (!empty($otp_number)) {
									$record					=	OtpVerification::where('email', Request::get("email"))->first();
									if ($record) {
										$otp				=	OtpVerification::find($record->id);
									} else {
										$otp				=	new OtpVerification();
									}
									$otp->email				=	Request::get("email");
									$otp->otp				=	$otp_number;
									$otp->save();
								}

								$full_name          =  	!empty($userDetail->first_name) ? $userDetail->first_name : '';
								$email		        =	Request::get("email");
								$settingsEmail 		= 	Config::get('Site.email');
								$emailActions		= 	EmailAction::where('action', '=', 'forget_password_otp')->get()->toArray();
								$emailTemplates		= 	EmailTemplate::where('action', '=', 'forget_password_otp')->get(array('name', 'subject', 'action', 'body'))->toArray();
								$cons 				= 	explode(',', $emailActions[0]['options']);
								$constants 			= 	array();
								foreach ($cons as $key => $val) {
									$constants[] 	= '{' . $val . '}';
								}
								$subject 			= 	$emailTemplates[0]['subject'];
								$rep_Array 			= 	array($full_name, $otp_number);
								$messageBody		= 	str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
								$mail				= 	$this->sendMail($email, $full_name, $subject, $messageBody, $settingsEmail);

								$otp_number							=	DB::table('otp_verifications')->where('email', Request::get("email"))->pluck('otp')->first();
								$response["otp"]					=	$otp_number;
								$response["status"]					=	"success";
								$response["message"]				=	"An otp will be sent to your email,please enter to update password";
								$response["data"]					=	$userDetail;
							} else {
								$response["status"]			=	"error";
								$response["message"]		=	"Your mobile number is not verified yet, please login to verify.";
								$response["data"]			=	array();
							}
						} else {
							$response["status"]			=	"error";
							$response["message"]		=	"Your email is not verified yet, please login to verify.";
							$response["data"]			=	array();
						}
					} else {
						$response["status"]			=	"error";
						$response["message"]		=	"Your account has been temporarily disabled. Please contact administrator to unlock.";
						$response["data"]			=	array();
					}
				} else {
					$response["status"]		=	"error";
					$response["message"]		=	"Your email is not registered with " . ucfirst(Config::get("Site.title"));
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
	/**
	 * Function use for  ResetPassword
	 *
	 * @param null
	 *
	 * @return response
	 */
	public function ResetPassword()
	{
		$formData	=	Request::all();
		$response	=	array();
		if (!empty($formData)) {
			Validator::extend('custom_password', function ($attribute, $value, $parameters) {
				if (preg_match('#[0-9]#', $value) && preg_match('#[a-zA-Z]#', $value)) {
					return true;
				} else {
					return false;
				}
			});
			$validator 		=	Validator::make(
				Request::all(),
				array(
					'email'			=>	'required',
					'password'			=>	'required|min:8',
					'confirm_password' 	=> 	'required|same:password',
				),
				array(
					"password.required"				=>	trans("New password field is required."),
					"password.min"					=>	trans("Password must have minimum of 8 characters."),
					"password.custom_password"		=>	trans("Password must have be a combination of numeric and alphabets."),
					"confirm_password.required"		=>	trans("Please enter confirm password."),
					"confirm_password.same"			=>	trans("Password and confirm password must match."),
				)
			);
			if ($validator->fails()) {
				$response["status"]			=	"error";
				$response["message"]		=	$validator->errors()->first();
				$response["data"]			=	null;
				//$response["error"]			=	$this->change_error_msg_layout_api($validator->errors()->getMessages());
			} else {
				$email	  =	Request::get('email');
				$userInfo = 	DB::table("users")
					->where(function ($query) use ($email) {
						//$query->orWhere("mobile_number",$email);
						$query->orWhere("email", $email);
					})
					->where('is_deleted', 0)
					->where("user_role_id", "!=", SUPER_ADMIN_ROLE_ID)
					->first();

				if (!empty($userInfo)) {
					$changepassword = User::where('email', $email)->update(array('password' => Hash::make(Request::get('password')), 'forgot_password_validate_string' => ''));

					if ($changepassword) {
						$response["status"]			=	"success";
						$response["message"]		=	"Password has been reset successfully";
						$response["data"]			=	array();
					} else {
						$response["status"]			=	"error";
						$response["message"]		=	"Somthing went wrong.";
						$response["data"]			=	array();
					}



					$settingsEmail 		= Config::get('Site.email');
					$action				= "reset_password";
					$emailActions		=	EmailAction::where('action', '=', 'reset_password')->get()->toArray();
					$emailTemplates		=	EmailTemplate::where('action', '=', 'reset_password')->get(array('name', 'subject', 'action', 'body'))->toArray();
					$cons 				= 	explode(',', $emailActions[0]['options']);
					$constants 			= 	array();
					foreach ($cons as $key => $val) {
						$constants[] = '{' . $val . '}';
					}
					$subject 			=  $emailTemplates[0]['subject'];
					$rep_Array 			= array($userInfo->first_name);
					$messageBody		=  str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
					$this->sendMail($userInfo->email, $userInfo->first_name, $subject, $messageBody, $settingsEmail);
				} else {
					$response["status"]			=	"error";
					$response["message"]		=	"Invalid request";
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
	/** 
	 * Function use to get site settings
	 *
	 * @param null
	 * 
	 * @return response
	 */

	public function getSettings()
	{
		$settingsDetails	 	=   DB::table('settings')->whereIn('key', array('Site.title', 'Site.email', 'Site.android_app_url', 'Site.iphone_app_url', 'Site.address', 'Site.contact_number', 'Site.google_api_key', 'Social.facebook', 'Social.google', 'Social.instagram'))
			->select('title', 'key', 'value')
			->get()
			->toArray();
		$result			 =		 array();
		if (empty($settingsDetails)) {
			$response["status"]				=	"error";
			$response["message"]			=	"No record found";
			$response["data"]				=	array();
		} else {
			$result = array();
			foreach ($settingsDetails as $settingsDetail) {
				$settingsKey  = explode(".", $settingsDetail->key);
				$result[$settingsKey[0]][$settingsDetail->key]	=	$settingsDetail->value;
			}
			$response["status"]				=	"success";
			$response["message"]			=	"Settings found successfully";
			$response["data"]				=	$result;
		}
		return json_encode($response);
	}
	/**
	 * Function use for to get user details
	 *
	 * @param null
	 *
	 * @return response
	 */
	public function userDetail()
	{

		$user_details 	=  Auth::user();
		$response		=	array();
		if (!empty($user_details)) {
			if ($user_details->image != "" && File::exists(USER_IMAGE_ROOT_PATH . $user_details->image)) {
				$user_details->image = USER_IMAGE_URL . $user_details->image;
			}
			if (!empty($user_details)) {
				$response["status"]		=	"success";
				$response["message"]	=	"User Details Found successfully";
				$response["data"]		=	$user_details;
			} else {
				$response["status"]		=	"error";
				$response["message"]	=	"Details not found";
				$response["data"]		=	array();
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Please login your account first";
			$response["data"]		=	array();
		}

		return json_encode($response);
	}
	/**
	 * Function use for to update user name
	 *
	 * @param null
	 *
	 * @return response
	 */
	public function updateImage()
	{
		$currentUser = Auth::user();
		$formData	=	Request::all();
		$response	=	array();
		if (!empty($formData)) {

			//	$currentUser = User::where('id',$user_id)->first();
			if (!empty($currentUser)) {

				$validator = Validator::make(
					Request::all(),
					array(
						'image' 				=> 	 'required',
					)
				);
				if ($validator->fails()) {
					$response["status"]			=	"error";
					$response["message"]		=	$validator->errors()->first();
					$response["data"]			=	null;
				} else {

					if (Request::hasFile('image')) {
						$extension 	=	 Request::file('image')->getClientOriginalExtension();
						$fileName	=	time() . '-user.' . $extension;

						$folderName     	= 	strtoupper(date('M') . date('Y')) . "/";
						$folderPath			=	USER_IMAGE_ROOT_PATH . $folderName;
						if (!File::exists($folderPath)) {
							File::makeDirectory($folderPath, $mode = 0777, true);
						}
						if (Request::file('image')->move($folderPath, $fileName)) {
							$image	=	$folderName . $fileName;
							$updateimage =   User::where('id', $currentUser->id)->update(array('image' => $image));
						}
					}

					$response["status"]		=	"success";
					$response["message"]	=	"Details updated successfully.";
					$response["data"]		=	array();
				}
			} else {
				$response["status"]		=	"error";
				$response["message"]	=	"Please login first.";
				$response["data"]		=	array();
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}

	/**
	 * Function use for to update user name
	 *
	 * @param null
	 *
	 * @return response
	 */
	public function updateProfile()
	{

		$formData	=	Request::all();
		$response	=	array();
		if (!empty($formData)) {
			$currentUser = Auth::user();
			if (!empty($currentUser)) {

				$validator = Validator::make(
					Request::all(),
					array(
						'first_name' 			=> 	 'required',
						'last_name' 			=> 	 'required',
						'bio' 					=> 	 'required',
						'username' 				=> 	 "required|unique:users,username,$currentUser->id|max:255",
					)
				);

				if ($validator->fails()) {
					$val = array_column(json_decode(json_encode($validator->errors()), true), 0);
					$response["status"]			=	"error";
					//	$response["message"]		=	$validator->errors();
					$response["message"]		=	$val;
					$response["data"]			=	null;
				} else {
					$bio = Request::get("bio");
					$first_name = Request::get("first_name");
					$last_name 	= Request::get("last_name");
					$fullname 	= $first_name . " " . $last_name;


					$updateName =   User::where('id', $currentUser->id)->update(['first_name' => Request::get("first_name"), 'last_name' => Request::get("last_name"), 'username' => Request::get("username"), 'bio' => Request::get("bio"), 'fullname' => $fullname]);

					$response["status"]		=	"success";
					$response["message"]	=	"Details updated successfully.";
					$response["data"]		=	array();
				}
			} else {
				$response["status"]		=	"error";
				$response["message"]	=	"Please login first.";
				$response["data"]		=	array();
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	/**
	 * Function use for to edit email
	 *
	 * @param null
	 *
	 * @return response
	 */
	public function editEmail()
	{
		$formData	=	Request::all();
		$currentUser = Auth::user();
		$response	=	array();
		if (!empty($formData)) {
			if (!empty($currentUser)) {
				$email	= 	!empty(Request::get("email")) ? Request::get("email") : '';


				$validator = Validator::make(
					Request::all(),
					array(
						"email" 						=> "required",
					)
				);
				if ($validator->fails()) {
					$response["status"]			=	"error";
					$response["message"]		=	$validator->errors()->first();
					$response["data"]			=	null;
				} else {

					if (!empty($email)) {
						$otp_number 		= 	mt_rand(1000, 9999);
						if (!empty($otp_number)) {
							$record					=	OtpVerification::where('email', $email)->first();
							if ($record) {
								$otp				=	OtpVerification::find($record->id);
							} else {
								$otp				=	new OtpVerification();
							}
							$otp->email				=	$email;
							$otp->otp				=	$otp_number;
							$otp->save();
						}

						$full_name          =  	$currentUser->name;
						$email		        =	$email;
						$settingsEmail 		= 	Config::get('Site.email');
						$emailActions		= 	EmailAction::where('action', '=', 'user_otp_verification')->get()->toArray();
						$emailTemplates		= 	EmailTemplate::where('action', '=', 'user_otp_verification')->get(array('name', 'subject', 'action', 'body'))->toArray();
						$cons 				= 	explode(',', $emailActions[0]['options']);
						$constants 			= 	array();
						foreach ($cons as $key => $val) {
							$constants[] 	= '{' . $val . '}';
						}
						$subject 			= 	$emailTemplates[0]['subject'];
						$rep_Array 			= 	array($full_name, $otp_number);
						$messageBody		= 	str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
						$mail				= 	$this->sendMail($email, $full_name, $subject, $messageBody, $settingsEmail);

						$response["status"]					=	"success";
						$response["message"]				=	"An otp will be sent to your email,please enter to update";
						$response["data"]					=	array();
					} else {
						$response["status"]		=	"error";
						$response["message"]	=	"User Not Found";
						$response["data"]		=	array();
					}
				}
			} else {
				$response["status"]		=	"error";
				$response["message"]	=	"Please login your account first.";
				$response["data"]		=	array();
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}

	/**
	 * Function use for to update email
	 *
	 * @param null
	 *
	 * @return response
	 */
	public function updateEmail()
	{

		$formData	=	Request::all();
		$response	=	array();
		if (!empty($formData)) {

			$currentUser = Auth::user();
			if (!empty($currentUser)) {

				$email	= 	!empty(Request::get("email")) ? Request::get("email") : '';
				$otp_number		=	Request::get('otp');

				$validator = Validator::make(
					Request::all(),
					array(
						"email" 						=> "required",
						"otp"							=> "required",
					),
					array(
						"email.required" 					=>	trans("The email is required."),
						"otp.required" 					=>	trans("The otp is required."),
					)
				);
				if ($validator->fails()) {
					$response["status"]			=	"error";
					$response["message"]		=	$validator->errors()->first();
					$response["data"]			=	null;
				} else {
					$checkDetails				=	OtpVerification::where('email', Request::get("email"))->first();


					if (!empty($checkDetails)) {
						$savedOtp	=	$checkDetails->otp;
						if ($otp_number == $savedOtp) {

							User::where('id', $currentUser->id)->update(array('email' => Request::get("email")));

							$response["status"]		=	"success";
							$response["message"]	=	"Your email has been updated successfully.";
							$response["data"]		=	1;
						} else {
							$response["status"]		=	"error";
							$response["message"]	=	"Invalid OTP";
							$response["data"]		=	array();
						}
					} else {
						$response["status"]		=	"error";
						$response["message"]	=	"Invalid OTP";
						$response["data"]		=	array();
					}
				}
			} else {
				$response["status"]		=	"error";
				$response["message"]	=	"Please login your account first.";
				$response["data"]		=	array();
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}

	/**
	 * Function use for to edit email
	 *
	 * @param null
	 *
	 * @return response
	 */
	public function editMobileNumber()
	{
		$formData	=	Request::all();
		$currentUser = Auth::user();
		$user_id	=  $currentUser->id;

		//print_r(	$currentUser ); die;
		$response	=	array();
		if (!empty($formData)) {
			if (!empty($currentUser)) {
				$mobile_number	= 	!empty(Request::get("mobile_number")) ? Request::get("mobile_number") : '';
				$validator = Validator::make(
					Request::all(),
					array(
						"mobile_number" 						=> "required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users,mobile_number,$user_id",
					)
				);
				if ($validator->fails()) {
					$response["status"]			=	"error";
					$response["message"]		=	$validator->errors()->first();
					$response["data"]			=	null;
				} else {

					if (!empty($mobile_number)) {
						$otp_number 		= 	mt_rand(1000, 9999);
						if (!empty($otp_number)) {
							$record					=	OtpVerification::where('mobile_number', $mobile_number)->first();
							if ($record) {
								$otp				=	OtpVerification::find($record->id);
							} else {
								$otp				=	new OtpVerification();
							}
							$otp->mobile_number		=	$mobile_number;
							$otp->otp				=	$otp_number;
							$otp->save();
						}

						// $message				= 	"Hello\ nPlease enter this $otp_number otp to verify your email.";
						// $sendOtpSms				= 	$this->sendSMS($message,$mobile_number);
						$response["otp"]		=	$otp_number;
						$response["status"]					=	"success";
						$response["message"]				=	"An otp will be sent to your mobile number,please enter to update";
					} else {
						$response["status"]		=	"error";
						$response["message"]	=	"User NOt Found";
						$response["data"]		=	array();
					}
				}
			} else {
				$response["status"]		=	"error";
				$response["message"]	=	"Please login your account first.";
				$response["data"]		=	array();
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}

	/**
	 * Function use for to update email
	 *
	 * @param null
	 *
	 * @return response
	 */
	public function updateMobileNumber()
	{

		$formData	=	Request::all();
		$response	=	array();
		if (!empty($formData)) {
			$currentUser = Auth::user();
			$user_id	=  $currentUser->id;
			//$currentUser = User::where('id',$user_id)->first();
			if (!empty($currentUser)) {

				$mobile_number	= 	!empty(Request::get("mobile_number")) ? Request::get("mobile_number") : '';
				$otp_number		=	Request::get('otp');

				$validator = Validator::make(
					Request::all(),
					array(
						"mobile_number" 						=> "required",
						"otp"							=> "required",
					),
					array(
						"mobile_number.required" 					=>	trans("The email is required."),
						"otp.required" 					=>	trans("The otp is required."),
					)
				);
				if ($validator->fails()) {
					$response["status"]			=	"error";
					$response["message"]		=	$validator->errors()->first();
					$response["data"]			=	null;
				} else {
					$checkDetails				=	OtpVerification::where('mobile_number', Request::get("mobile_number"))->first();
					if (!empty($checkDetails)) {
						$savedOtp	=	$checkDetails->otp;
						if ($otp_number == $savedOtp) {

							User::where('id', $user_id)->update(array('mobile_number' => Request::get("mobile_number")));

							$response["status"]		=	"success";
							$response["message"]	=	"Your mobile number has been updated successfully.";
							$response["data"]		=	1;
						} else {
							$response["status"]		=	"error";
							$response["message"]	=	"Invalid OTP";
							$response["data"]		=	array();
						}
					} else {
						$response["status"]		=	"error";
						$response["message"]	=	"Invalid OTP";
						$response["data"]		=	array();
					}
				}
			} else {
				$response["status"]		=	"error";
				$response["message"]	=	"Please login your account first.";
				$response["data"]		=	array();
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
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
	public function ContactUs()
	{
		$formData	=	Request::all();
		$response	=	array();
		if (!empty($formData)) {
			$validator = Validator::make(
				Request::all(),
				array(
					"name" 					=> "required|Regex:/\A(?!.*[:;]-\))[ -~]+\z/",
					"subject" 				=> "required",
					"mobile_number" 		=> "required|numeric",
					"comments" 				=> "required",
					"email" 				=> "required|email",
				),
				array(
					"email.required"		=>	trans("The email is required."),
				)
			);
			if ($validator->fails()) {
				$response["status"]			=	"error";
				$response["message"]		=	$validator->errors()->first();
				$response["data"]			=	null;
			} else {
				$obj					 = 	new Contact();
				$obj->name		 		=	Request::get('name');
				$obj->email				 =	Request::get('email');
				$obj->mobile_number		 =	Request::get('mobile_number');
				$obj->subject		 	 =	Request::get('subject');
				$obj->comments		 	 =	Request::get('comments');
				$obj->created_at		 =	date('Y-m-d H:i:s');
				$obj->updated_at		 =	date('Y-m-d H:i:s');
				$obj->save();
				//send email to site admin with user information,to inform that user wants to contact
				$emailActions		=  EmailAction::where('action', '=', 'contact_us')->get()->toArray();
				$emailTemplates		=  EmailTemplate::where('action', '=', 'contact_us')->get()->toArray();
				$cons 				=  explode(',', $emailActions[0]['options']);
				$constants 			=  array();
				foreach ($cons as $key => $val) {
					$constants[] = '{' . $val . '}';
				}
				$name				=	Request::get('name');
				$email				=	Request::get('email');
				$message			=	nl2br(Request::get('comments'));
				$subject 			=  $emailTemplates[0]['subject'];
				$rep_Array 			=  array($name, $email, $message);
				$messageBody		=  str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
				$settingsEmail 		= 	Config::get('Site.email');
				$this->sendMail(Config::get("Site.email"), 'Admin', $subject, $messageBody, $settingsEmail, $files = false);

				$response["status"]			=	"success";
				$response["message"]		=	"Message has been sent successfully";
				$response["data"]			=	array();
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}

	public function logout()
	{
		$response	=	array();

		User::where('id', Request::get('user_id'))->update(array('device_token' => ''));
		Auth::logout();
		$response["status"]		=	"success";
		$response["message"]	=	"You are now logged out!";
		$response["data"]		=	array();

		return json_encode($response);
	}
}// end ApisController class
