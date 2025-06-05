<?php

namespace App\Http\Controllers;

use App\Models\EmailAction;
use App\Models\EmailTemplate;
use App\Models\User;
use App\Models\Cms;
use Twilio\Rest\Client;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, mongoDate, Redirect, Request, Response, Session, URL, View, Validator, Str, App, DateTime;

/**
 * Base Controller
 *
 * Add your methods in the class below
 *
 * This is the base controller called everytime on every request
 */
class BaseController extends Controller
{

	protected $user;

	public function __construct()
	{
		/* $this->middleware(function ($request, $next){

		});
		 */
	} // end function __construct()

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return layout
	 */
	public function sendSuccess($data = [], $message)
	{
		$response = [
			'success' => true,
			'data'    => $data,
			'message' => $message,
		];
		return response()->json($response, 200);
	}


	/**
	 * return error response.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function sendError($errorMessages, $error = [], $code = 401)
	{
		$response = [
			'success' => false,
			'message' => $errorMessages,
		];
		if (!empty($error)) {
			$response['data'] = $error;
		}
		return response()->json($response, $code);
	}
	protected function setupLayout()
	{
		if (Request::segment(1) != 'admin') {
		}
		if (! is_null($this->layout)) {
			$this->layout = View::make($this->layout);
		}
	} //end setupLayout()

	/**
	 * Function to make slug according model from any certain field
	 *
	 * @param title     as value of field
	 * @param modelName as section model name
	 * @param limit 	as limit of characters
	 *
	 * @return string
	 */
	public function getSlug($title, $fieldName, $modelName, $limit = 30)
	{
		$slug 		= 	 substr(Str::slug($title), 0, $limit);
		$Model		=	 "\App\Models\\$modelName";
		$slugCount 	=    count($Model::where($fieldName, 'regexp', "/^{$slug}(-[0-9]*)?$/i")->get());
		return ($slugCount > 0) ? $slug . "-" . $slugCount : $slug;
	} //end getSlug()
	/**
	 * Function to make slug without model name from any certain field
	 *
	 * @param title     as value of field
	 * @param tableName as table name
	 * @param limit 	as limit of characters
	 *
	 * @return string
	 */
	// public function getSlugWithoutModel($title, $fieldName='' ,$tableName,$limit = 30){
	// 	$slug 		=	substr(Str::slug($title),0 ,$limit);
	// 	$slug 		=	Str::slug($title);
	// 	$DB 		= 	DB::table($tableName);
	// 	$slugCount 	= 	count( $DB->whereRaw("$fieldName REGEXP '^{$slug}(-[0-9]*)?$'")->get() );
	// 	return ($slugCount > 0) ? $slug."-".$slugCount: $slug;
	// }//end getSlugWithoutModel()

	/**
	 * Function to search result in database
	 *
	 * @param data  as form data array
	 *
	 * @return query string
	 */
	public function search($data)
	{
		unset($data['display']);
		unset($data['_token']);
		$ret	=	'';
		if (!empty($data)) {
			foreach ($data as $fieldName => $fieldValue) {
				$ret	.=	"where('$fieldName', 'LIKE',  '%' . $fieldValue . '%')";
			}
			return $ret;
		}
	} //end search()

	// public function sendSMS($msg,$phone){

	// 	//Your authentication key
	// 	$username = env('SMS_SEND_USER_ID');
	// 	$password = env('SMS_SEND_USER_PASSWORD');
	// 	//Multiple mobiles numbers separated by comma
	// 	//$mobileNumber =$_POST['phone'];
	// 	//Sender ID,While using route4 sender id should be 6 characters long.
	// 	$senderId = "test";
	// 	//Your message to send, Add URL encoding here.
	// 	$message = urlencode($msg);

	// 	$url = "http://sms.bulk-sms-cloud.me/API_SendSMS.aspx?User=$username&passwd=$password&mobilenumber=$phone&message=$message&sid=$senderId&mtype=N&DR=N";
	// 	//echo '<pre>';print_r($url); die;
	// 	// init the resource
	// 	$ch = curl_init();
	// 	curl_setopt($ch, CURLOPT_URL, $url);
	// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	// 	curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
	// 	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	// 	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	// 	$response1 = curl_exec($ch);

	// 	// if(curl_errno($ch)){
	// 	// 	echo $x = 'error:' . curl_error($ch);	}
	// 	// curl_close($ch);
	// 	// die;

	// 	curl_close($ch);
	// 	$response_a = json_decode($response1, true);
	// 	die;
	//}


	/**
	 * Write code on Method
	 *
	 * @return response()
	 */
	public function sendSMS($message, $mobile_number)
	{
		// $mobile_number = "+15102489999";
		// $message = "This is testing from  ravi ";

		try {

			$account_sid = 	getenv("TWILIO_SID");
			$auth_token = 	getenv(" ");
			$twilio_number = getenv("TWILIO_FROM");

			$client = new Client($account_sid, $auth_token);
			$client->messages->create($mobile_number, [
				'from' => $twilio_number,
				'body' => $message
			]);


			// print_r($message);

		} catch (Exception $e) {
			dd("Error: " . $e->getMessage());
		}
	}



	/**
	 * Function to send email form website
	 *
	 * @param string $to            as to address
	 * @param string $fullName      as full name of receiver
	 * @param string $subject       as subject
	 * @param string $messageBody   as message body
	 *
	 * @return void
	 */
	public function sendMail($to, $fullName, $subject, $messageBody, $from = '', $files = false, $path = '', $attachmentName = '')
	{
		$data				=	array();
		$data['to']			=	$to;
		$data['from']		=	(!empty($from) ? $from : Config::get("Site.email"));
		$data['fullName']	=	$fullName;
		$data['subject']	=	$subject;
		$data['filepath']	=	$path;
		$data['attachmentName']	=	$attachmentName;
		if ($files === false) {
			Mail::send('emails.template', array('messageBody' => $messageBody), function ($message) use ($data) {
				$message->to($data['to'], $data['fullName'])->from($data['from'])->subject($data['subject']);
			});
		} else {
			if ($attachmentName != '') {
				Mail::send('emails.template', array('messageBody' => $messageBody), function ($message) use ($data) {
					$message->to($data['to'], $data['fullName'])->from($data['from'])->subject($data['subject'])->attach($data['filepath'], array('as' => $data['attachmentName']));
				});
			} else {
				Mail::send('emails.template', array('messageBody' => $messageBody), function ($message) use ($data) {
					$message->to($data['to'], $data['fullName'])->from($data['from'])->subject($data['subject'])->attach($data['filepath']);
				});
			}
		}

		DB::table('email_logs')->insert(
			array(
				'email_to'	 => $data['to'],
				'email_from' => $data['from'],
				'subject'	 => $data['subject'],
				'message'	 =>	$messageBody,
				'created_at' => DB::raw('NOW()')
			)
		);
	}

	public function send_push_notification($deviceToken = "", $device_type = "", $data = array())
	{
		//echo '<pre>'; print_r($data); die;
		$serverKey			=	Config::get("Site.web_notification_server_key");
		$notification = array(
			'title' => $data['title'],
			'body' => $data['message'],
			'sound' => 'default',
			'badge' => '1',
			///	'image'	=> $data['image'],
			//	'notification_type'=> $notification_type,
			'mutable-content' => 1,
			'category' => 'rich-apns',
			'image-url' => $data['image']
		);
		$arrayToSend = array('to' => $deviceToken, 'notification' => $notification, 'priority' => 'high');
		$json = json_encode($arrayToSend);
		$headers = array();
		$headers[] = 'Content-Type: application/json';
		$headers[] = 'Authorization: key=' . $serverKey;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$response = curl_exec($ch);
		// if ($response === FALSE){
		// 	die('FCM Send Error: ' . curl_error($ch));
		// }
		curl_close($ch);
	}
	public function send_push_notification_byadmin($deviceToken = "", $device_type = "", $message = "", $notification_title = "")
	{
		$server_key			=	Config::get("Site.web_notification_server_key");
		// $deviceToken    = 	$token;
		$registrationIds = array($deviceToken);

		$msg = array(
			'message'			=> $message,
			'title'				=> $notification_title,
			'vibrate'			=> 1,
			'sound'				=> 1,
		);

		$fields = array(
			'registration_ids' => $registrationIds,
			'data'	=> $msg
		);
		// dd($fields);
		$headers = array(
			'Authorization: key=' . $server_key,
			'Content-Type: application/json'
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($ch);
		// print_r($result);die;
		curl_close($ch);
	}
	public function getVerificationCode()
	{
		//$code	=	rand(100000,999999);
		$code	=	0000;
		return $code;
	}

	public  function arrayStripTags($array)
	{
		$result			=	array();
		foreach ($array as $key => $value) {
			// Don't allow tags on key either, maybe useful for dynamic forms.
			$key = strip_tags($key, ALLOWED_TAGS_XSS);

			// If the value is an array, we will just recurse back into the
			// function to keep stripping the tags out of the array,
			// otherwise we will set the stripped value.
			if (is_array($value)) {
				$result[$key] = $this->arrayStripTags($value);
			} else {
				// I am using strip_tags(), you may use htmlentities(),
				// also I am doing trim() here, you may remove it, if you wish.
				$result[$key] = trim(strip_tags($value, ALLOWED_TAGS_XSS));
			}
		}

		return $result;
	}

	public function saveCkeditorImages()
	{
		if (!empty($_GET['CKEditorFuncNum'])) {
			$image_url				=	"";
			$msg					=	"";
			// Will be returned empty if no problems
			$callback = ($_GET['CKEditorFuncNum']);        // Tells CKeditor which function you are executing
			$image_details 				= 	getimagesize($_FILES['upload']["tmp_name"]);
			$image_mime_type			=	(isset($image_details["mime"]) && !empty($image_details["mime"])) ? $image_details["mime"] : "";
			if ($image_mime_type	==	'image/jpeg' || $image_mime_type == 'image/jpg' || $image_mime_type == 'image/gif' || $image_mime_type == 'image/png') {
				$ext					=	$this->getExtension($_FILES['upload']['name']);
				$fileName				=	"ck_editor_" . time() . "." . $ext;
				$upload_path			=	CK_EDITOR_ROOT_PATH;
				if (move_uploaded_file($_FILES['upload']['tmp_name'], $upload_path . $fileName)) {
					$image_url 			= 	CK_EDITOR_URL . $fileName;
				}
			} else {
				$msg =  'error : Please select a valid image. valid extension are jpeg, jpg, gif, png';
			}
			$output = '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction(' . $callback . ', "' . $image_url . '","' . $msg . '");</script>';
			echo $output;
			exit;
		}
	}

	function getExtension($str)
	{
		$i = strrpos($str, ".");
		if (!$i) {
			return "";
		}
		$l = strlen($str) - $i;
		$ext = substr($str, $i + 1, $l);
		$ext = strtolower($ext);
		return $ext;
	}

	/**
	 * Function to _update_all_status
	 *
	 * param source tableName,id,status,fieldName
	 */
	public function _update_all_status($tableName = null, $id = 0, $status = 0, $fieldName = 'is_active')
	{
		DB::beginTransaction();
		$response			=	DB::statement("CALL UpdateAllTableStatus('$tableName',$id,$status)");
		if (!$response) {
			DB::rollback();
			Session::flash('error', trans("messages.msg.error.something_went_wrong"));
			return Redirect::back();
		}
		DB::commit();
	}

	public function _update_status($tableName = null, $id = 0, $status = 0, $fieldName = 'order_status')
	{
		DB::beginTransaction();
		$response			=	DB::statement("CALL UpdateTableStatus('$tableName',$id,$status)");
		if (!$response) {
			DB::rollback();
			Session::flash('error', trans("messages.msg.error.something_went_wrong"));
			return Redirect::back();
		}
		DB::commit();
	}

	/**
	 * Function to _delete_table_entry
	 *
	 * param source tableName,id,fieldName
	 */
	public function _delete_table_entry($tableName = null, $id = 0, $fieldName = null)
	{
		DB::beginTransaction();
		$response			=	DB::statement("CALL DeleteAllTableDataById('$tableName',$id,'$fieldName')");
		if (!$response) {
			DB::rollback();
			Session::flash('error', trans("messages.msg.error.something_went_wrong"));
			return Redirect::back();
		}
		DB::commit();
	} // end _delete_table_entry()

	public function change_error_msg_layout($errors = array())
	{
		$response				=	array();
		$response["status"]		=	"error";
		if (!empty($errors)) {
			$error_msg				=	"";
			foreach ($errors as $errormsg) {
				$error_msg1			=	(!empty($errormsg[0])) ? $errormsg[0] : "";
				$error_msg			.=	$error_msg1 . ", ";
			}
			$response["msg"]	=	trim($error_msg, ", ");
		} else {
			$response["msg"]	=	"";
		}
		$response["data"]			=	(object)array();
		return $response;
	}

	public function change_error_msg_layout_with_array($errors = array())
	{
		$response				=	array();
		$response["status"]		=	"error";
		if (!empty($errors)) {
			$error_msg				=	"";
			foreach ($errors as $errormsg) {
				$error_msg1			=	(!empty($errormsg[0])) ? $errormsg[0] : "";
				$error_msg			.=	$error_msg1 . ", ";
			}
			$response["msg"]	=	trim($error_msg, ", ");
		} else {
			$response["msg"]	=	"";
		}
		$response["data"]			=	array();
		return $response;
	}

	public function getUserByID($user_id)
	{


		$userDetails = '';
		$userDetails = User::where('id', $user_id)->where('is_deleted', 0)->first();
		if (!empty($userDetails)) {
			$userDetails  =  $userDetails;
		}
		return $userDetails;
	}

	public function CheckAccess($user_id)
	{


		$moduleName = array();

		$userDetails 		= 		User::where('id', $user_id)->where('is_active', 1)->where('is_deleted', 0)->first();
		$admin_role_id     	=   	$userDetails->admin_role_id;
		$modulesIds 		=    	DB::table('admin_roles')->where('id', $admin_role_id)->pluck('modules')->first();
		if (!empty($modulesIds)) {
			$modules 			= 		explode(",", $modulesIds);

			$moduleName =   DB::table('modules')->whereIn('id', $modules)->pluck('name')->toArray();
		}
		//echo '<pre>';print_r($moduleName);  die;
		return $moduleName;
	}

	public function getDriverID()
	{
		$prifix			=	"CYDE#";
		$lastId 		=    User::orderBy("created_at", "DESC")->pluck("id")->first();
		if (!empty($lastId)) {
			$driver_id 			= 	$prifix . str_pad($lastId, 5, 0, STR_PAD_LEFT);
			//	dd($driver_id);

		}
		return $driver_id;
	} //end getDealCode()
	public function cmsPages($slug)
	{

		$cmsPages 		= 		Cms::where('slug', $slug)->where('is_active', 1)->first();
		if (!empty($cmsPages)) {
			$cmsPages 		= $cmsPages;
		} else {
			$cmsPages 		= '';
		}
		return $cmsPages;
	}

	public function getValueByTableOrFieldName($tableName = null, $fieldName = null, $id = 0)
	{

		$value 		= 		DB::table("$tableName")->where('id', $id)->pluck("$fieldName")->first();
		if (!empty($value)) {
			$value 		= $value;
		} else {
			$value 		= '';
		}
		return $value;
	}

	public function send_notification_History($user_id  = "", $data  = "", $streek_id = "", $Game_id = "", $noti_message  = "", $image  = "")
	{
		DB::table('notifications')->insert(
			array(
				'user_id'	 => $user_id,
				'title'	 	 => $data['title'],
				'streek_id'  => !empty($streek_id) ? $streek_id : 0,
				'game_id'    => !empty($Game_id) ? $Game_id : 0,
				'message'	 =>	$noti_message,
				'image'		 => $image,
				'is_send'	=> 1,
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s"),
			)
		);
	}


	// public function generatePaypalAccessToken($clientId='',$secretId='',$paypalUrl){
	// 	$ch 		= 	curl_init();
	// 	$clientId 	= 	$clientId;
	// 	$secret 	= 	$secretId;
	// 	$paypalUrl 	= 	$paypalUrl;
	// 	$ch 		= 	curl_init();
	// 	$url 		= 	$paypalUrl."/v1/oauth2/token";
	// 	curl_setopt($ch, CURLOPT_URL, $url);
	// 	curl_setopt($ch, CURLOPT_HEADER, false);
	// 	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	// 	curl_setopt($ch, CURLOPT_SSLVERSION , 6); //NEW ADDITION
	// 	curl_setopt($ch, CURLOPT_POST, true);
	// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// 	curl_setopt($ch, CURLOPT_USERPWD, $clientId.":".$secret);
	// 	curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
	// 	$result = curl_exec($ch);
	// 	if(!empty($result)){
	// 		$json = json_decode($result);
	// 		curl_close($ch);
	// 		$accesstoken = $json->access_token;
	// 	}else{
	// 		$accesstoken = "";
	// 	}
	// 	return $accesstoken;
	// }
	public function sendPushNotification($data)
	{

		$SERVER_API_KEY = 'AAAA6p2seTs:APA91bExTn83tLoc4nsoAc8LqpWltcr63_Iv3N07u5drwJ3chSfW-eLSpA0xnIIsG1a-JXJRls-HJrvKmLbnei8OoC3v1KvXaLtwKxyYQLdfulFRJuVRMJKtpCifhRZ5dndaubN3wD';

		// $data = [
		//     "registration_ids" => $firebaseToken,
		//     "notification" => [
		//         "title" => $request->title,
		//         "body" => $request->body,
		//     ]
		// ];
		$dataString = json_encode($data);

		$headers = [
			'Authorization: key=' . $SERVER_API_KEY,
			'Content-Type: application/json',
		];

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

		$response = curl_exec($ch);
		// dd($response);

	}

	public function stockdata($last_url)
	{

		$ch = curl_init();
		$api_key = "&apiKey=wmeqeYhnP16OpA86XfNkN0EUV9kJjS0C";
		if (empty($last_url)) {
			$url = "https://api.polygon.io/v3/reference/tickers?active=true&sort=ticker&order=asc&limit=1000" . $api_key;
		} else {
			$url = $last_url . $api_key;
		}

		// set url
		curl_setopt($ch, CURLOPT_URL, $url);
		//return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// $output contains the output string
		$output = curl_exec($ch);
		return $output;
		// dd($response);

	}
	public function singleData($ticker)
	{

		$ch = curl_init();
		// set url
		curl_setopt($ch, CURLOPT_URL, "https://api.polygon.io/v3/reference/tickers/" . $ticker . "?apiKey=wmeqeYhnP16OpA86XfNkN0EUV9kJjS0C");
		//return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// $output contains the output string
		$output = curl_exec($ch);
		return $output;
		// dd($response);

	}

	public function lastDayClose($ticker)
	{

		$ch = curl_init();
		// set url
		curl_setopt($ch, CURLOPT_URL, "https://api.polygon.io/v2/aggs/ticker/" . $ticker . "/prev?adjusted=true&apiKey=wmeqeYhnP16OpA86XfNkN0EUV9kJjS0C");



		//return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// $output contains the output string
		$output = curl_exec($ch);
		return $output;
		// dd($response);

	}
}// end BaseController class
