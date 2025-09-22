<?php

namespace App\Http\Controllers;

use App\Model\Cms;
use App\Model\User;
use Config;
use DB;
use Mail;
use Redirect;
use Request;
use Response;
use Session;
use Str;
use Twilio\Rest\Client;
use URL;
use View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\HtmlString;

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
    protected function setupLayout()
    {
        if (Request::segment(1) != 'admin') {
        }
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    } //end setupLayout()

    /**
     * Function to make slug according model from any certain field
     *
     * @param title     as value of field
     * @param modelName as section model name
     * @param limit 	as limit of characters
     * @return string
     */
    public function getSlug($title, $fieldName, $modelName, $limit = 30)
    {
        $slug = substr(Str::slug($title), 0, $limit);
        $Model = "\App\Model\\$modelName";
        $slugCount = count($Model::where($fieldName, 'regexp', "/^{$slug}(-[0-9]*)?$/i")->get());

        return ($slugCount > 0) ? $slug . '-' . $slugCount : $slug;
    }

    //end getSlug()
    /**
     * Function to make slug without model name from any certain field
     *
     * @param title     as value of field
     * @param tableName as table name
     * @param limit 	as limit of characters
     * @return string
     */
    public function getSlugWithoutModel($title, $fieldName, $tableName, $limit = 30)
    {
        $slug = substr(Str::slug($title), 0, $limit);
        $slug = Str::slug($title);
        $DB = DB::table($tableName);
        $slugCount = count($DB->whereRaw("$fieldName REGEXP '^{$slug}(-[0-9]*)?$'")->get());

        return ($slugCount > 0) ? $slug . '-' . $slugCount : $slug;
    } //end getSlugWithoutModel()

    /**
     * Function to search result in database
     *
     * @param data  as form data array
     * @return query string
     */
    public function search($data)
    {
        unset($data['display']);
        unset($data['_token']);
        $ret = '';
        if (!empty($data)) {
            foreach ($data as $fieldName => $fieldValue) {
                $ret .= "where('$fieldName', 'LIKE',  '%' . $fieldValue . '%')";
            }

            return $ret;
        }
    } //end search()


    public function sendSMS($message, $mobile_number)
    {
        // $mobile_number = "+15102489999";
        // $message = "This is testing from  ravi ";

        try {

            $account_sid = getenv('TWILIO_SID');
            $auth_token = getenv(' ');
            $twilio_number = getenv('TWILIO_FROM');

            $client = new Client($account_sid, $auth_token);
            $client->messages->create($mobile_number, [
                'from' => $twilio_number,
                'body' => $message
            ]);

            // print_r($message);

        } catch (Exception $e) {
            dd('Error: ' . $e->getMessage());
        }
    }

    /**
     * Function to send email form website
     *
     * @param  string  $to  as to address
     * @param  string  $fullName  as full name of receiver
     * @param  string  $subject  as subject
     * @param  string  $messageBody  as message body
     * @return void
     */
    public function sendMail($to, $fullName, $subject, $messageBody, $settingsEmail, $files = false, $path = '', $attachmentName = '')
    {
        $data = [];
        $data['to'] = $to;
        $data['from'] = 'do_not_reply@airtelbank.com';
        $data['fullName'] = $fullName;
        $data['subject'] = $subject;
        $data['filepath'] = $path;
        $data['attachmentName'] = $attachmentName;
        if ($files === false) {
            Mail::send('emails.template', ['messageBody' => $messageBody], function ($message) use ($data) {
                $message->to($data['to'], $data['fullName'])->from($data['from'])->subject($data['subject']);
            });
        } else {
            if ($attachmentName != '') {
                Mail::send('emails.template', ['messageBody' => $messageBody], function ($message) use ($data) {
                    $message->to($data['to'], $data['fullName'])->from($data['from'])->subject($data['subject'])->attach($data['filepath'], ['as' => $data['attachmentName']]);
                });
            } else {
                Mail::send('emails.template', ['messageBody' => $messageBody], function ($message) use ($data) {
                    $message->to($data['to'], $data['fullName'])->from($data['from'])->subject($data['subject'])->attach($data['filepath']);
                });
            }
        }

        DB::table('email_logs')->insert(
            [
                'email_to' => $data['to'],
                'email_from' => $data['from'],
                'subject' => $data['subject'],
                'message' => $messageBody,
                'created_at' => DB::raw('NOW()'),
            ]
        );
    }

    public function send_push_notification($deviceToken = '', $device_type = '', $data = [])
    {
        //echo '<pre>'; print_r($data); die;
        $serverKey = Config::get('Site.web_notification_server_key');
        $notification = [
            'title' => $data['title'],
            'body' => $data['message'],
            'sound' => 'default',
            'badge' => '1',
            ///	'image'	=> $data['image'],
            //	'notification_type'=> $notification_type,
            'mutable-content' => 1,
            'category' => 'rich-apns',
            'image-url' => $data['image'],
        ];
        $arrayToSend = ['to' => $deviceToken, 'notification' => $notification, 'priority' => 'high'];
        $json = json_encode($arrayToSend);
        $headers = [];
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key=' . $serverKey;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        // if ($response === FALSE){
        // 	die('FCM Send Error: ' . curl_error($ch));
        // }
        curl_close($ch);
    }

    public function send_push_notification_byadmin($deviceToken = '', $device_type = '', $message = '', $notification_title = '')
    {
        $server_key = Config::get('Site.web_notification_server_key');
        // $deviceToken    = 	$token;
        $registrationIds = [$deviceToken];

        $msg = [
            'message' => $message,
            'title' => $notification_title,
            'vibrate' => 1,
            'sound' => 1,
        ];

        $fields = [
            'registration_ids' => $registrationIds,
            'data' => $msg,
        ];
        // dd($fields);
        $headers = [
            'Authorization: key=' . $server_key,
            'Content-Type: application/json',
        ];
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
        $code = 0000;

        return $code;
    }

    public function arrayStripTags($array)
    {
        $result = [];
        foreach ($array as $key => $value) {
            // Don't allow tags on key either, maybe useful for dynamic forms.
            $key = strip_tags($key, config('ALLOWED_TAGS_XSS'));

            // If the value is an array, we will just recurse back into the
            // function to keep stripping the tags out of the array,
            // otherwise we will set the stripped value.
            if (is_array($value)) {
                $result[$key] = $this->arrayStripTags($value);
            } else {
                // I am using strip_tags(), you may use htmlentities(),
                // also I am doing trim() here, you may remove it, if you wish.
                $result[$key] = trim(strip_tags($value, config('ALLOWED_TAGS_XSS')));
            }
        }

        return $result;
    }
    public function saveCkeditorImages(Request $request)
    {
        if (!empty($request->input('CKEditorFuncNum'))) {
            $callback = htmlspecialchars($request->input('CKEditorFuncNum'), ENT_QUOTES, 'UTF-8');
            $image_url = '';
            $msg = '';

            // Define valid extensions and mime types
            $validExtensions = ['jpeg', 'jpg', 'gif', 'png'];
            $validMimeTypes = ['image/jpeg', 'image/jpg', 'image/gif', 'image/png'];

            // Validate the uploaded file
            $validator = Validator::make($request->all(), [
                'upload' => 'required|file|mimes:' . implode(',', $validExtensions),
            ]);

            if ($validator->fails()) {
                $msg = 'error: Please select a valid image. Valid extensions are jpeg, jpg, gif, png';
            } else {
                $file = $request->file('upload');

                // Verify the MIME type using the Fileinfo extension
                $fileMimeType = $file->getMimeType();
                if (!in_array($fileMimeType, $validMimeTypes)) {
                    $msg = 'error: Invalid image mime type.';
                } else {
                    // Check the contents of the file using GD or Imagick
                    $image = @getimagesize($file->getRealPath());
                    if ($image === false) {
                        $msg = 'error: Invalid image content.';
                    } else {
                        // Proceed to save the file
                        $ext = $file->getClientOriginalExtension();
                        $fileName = 'ck_editor_' . time() . '.' . $ext;
                        $upload_path = CK_EDITOR_ROOT_PATH;

                        if ($file->move($upload_path, $fileName)) {
                            $image_url = CK_EDITOR_URL . $fileName;
                        } else {
                            $msg = 'error: Could not save the uploaded file.';
                        }
                    }
                }
            }

            // Escape the message and image URL to prevent XSS
            $msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
            $image_url = htmlspecialchars($image_url, ENT_QUOTES, 'UTF-8');

            // Return the response to the CKEditor
            $output = '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction("' . $callback . '", "' . $image_url . '", "' . $msg . '");</script>';
            echo $output;
            exit;
        }
    }


    public function getExtension($str)
    {
        $i = strrpos($str, '.');
        if (!$i) {
            return '';
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
        $response = DB::statement("CALL UpdateAllTableStatus('$tableName',$id,$status)");
        if (!$response) {
            DB::rollback();
            Session::flash('error', trans('messages.msg.error.something_went_wrong'));

            return Redirect::back();
        }
        DB::commit();
    }

    public function _update_status($tableName = null, $id = 0, $status = 0, $fieldName = 'order_status')
    {
        DB::beginTransaction();
        $response = DB::statement("CALL UpdateTableStatus('$tableName',$id,$status)");
        if (!$response) {
            DB::rollback();
            Session::flash('error', trans('messages.msg.error.something_went_wrong'));

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
        $response = DB::statement("CALL DeleteAllTableDataById('$tableName',$id,'$fieldName')");
        if (!$response) {
            DB::rollback();
            Session::flash('error', trans('messages.msg.error.something_went_wrong'));

            return Redirect::back();
        }
        DB::commit();
    } // end _delete_table_entry()

    public function change_error_msg_layout($errors = [])
    {
        $response = [];
        $response['status'] = 'error';
        if (!empty($errors)) {
            $error_msg = '';
            foreach ($errors as $errormsg) {
                $error_msg1 = (!empty($errormsg[0])) ? $errormsg[0] : '';
                $error_msg .= $error_msg1 . ', ';
            }
            $response['msg'] = trim($error_msg, ', ');
        } else {
            $response['msg'] = '';
        }
        $response['data'] = (object) [];

        return $response;
    }

    public function change_error_msg_layout_with_array($errors = [])
    {
        $response = [];
        $response['status'] = 'error';
        if (!empty($errors)) {
            $error_msg = '';
            foreach ($errors as $errormsg) {
                $error_msg1 = (!empty($errormsg[0])) ? $errormsg[0] : '';
                $error_msg .= $error_msg1 . ', ';
            }
            $response['msg'] = trim($error_msg, ', ');
        } else {
            $response['msg'] = '';
        }
        $response['data'] = [];

        return $response;
    }

    public function getUserByID($user_id)
    {

        $userDetails = '';
        $userDetails = User::where('id', $user_id)->where('is_deleted', 0)->first();
        if (!empty($userDetails)) {
            $userDetails = $userDetails;
        }

        return $userDetails;
    }

    public function CheckAccess($user_id)
    {

        $moduleName = [];

        $userDetails = User::where('id', $user_id)->where('is_active', 1)->where('is_deleted', 0)->first();
        $admin_role_id = $userDetails->admin_role_id;
        $modulesIds = DB::table('admin_roles')->where('id', $admin_role_id)->pluck('modules')->first();
        if (!empty($modulesIds)) {
            $modules = explode(',', $modulesIds);

            $moduleName = DB::table('modules')->whereIn('id', $modules)->pluck('name')->toArray();
        }

        //echo '<pre>';print_r($moduleName);  die;
        return $moduleName;
    }

    public function getDriverID()
    {
        $prifix = 'CYDE#';
        $lastId = User::orderBy('created_at', 'DESC')->pluck('id')->first();
        if (!empty($lastId)) {
            $driver_id = $prifix . str_pad($lastId, 5, 0, STR_PAD_LEFT);
            //	dd($driver_id);

        }

        return $driver_id;
    }

    //end getDealCode()
    public function cmsPages($slug)
    {

        $cmsPages = Cms::where('slug', $slug)->where('is_active', 1)->first();
        if (!empty($cmsPages)) {
            $cmsPages = $cmsPages;
        } else {
            $cmsPages = '';
        }

        return $cmsPages;
    }

    public function getValueByTableOrFieldName($tableName = null, $fieldName = null, $id = 0)
    {

        $value = DB::table("$tableName")->where('id', $id)->pluck("$fieldName")->first();
        if (!empty($value)) {
            $value = $value;
        } else {
            $value = '';
        }

        return $value;
    }

    public function send_notification_History($user_id = '', $data = '', $streek_id = '', $Game_id = '', $noti_message = '', $image = '')
    {
        DB::table('notifications')->insert(
            [
                'user_id' => $user_id,
                'title' => $data['title'],
                'streek_id' => !empty($streek_id) ? $streek_id : 0,
                'game_id' => !empty($Game_id) ? $Game_id : 0,
                'message' => $noti_message,
                'image' => $image,
                'is_send' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
    }

    public function generatePaypalAccessToken($clientId, $secretId, $paypalUrl)
    {
        $ch = curl_init();
        $clientId = $clientId;
        $secret = $secretId;
        $paypalUrl = $paypalUrl;
        $ch = curl_init();
        $url = $paypalUrl . '/v1/oauth2/token';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6); //NEW ADDITION
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $clientId . ':' . $secret);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        $result = curl_exec($ch);
        if (!empty($result)) {
            $json = json_decode($result);
            curl_close($ch);
            $accesstoken = $json->access_token;
        } else {
            $accesstoken = '';
        }

        return $accesstoken;
    }

    public function sendPushNotification($data)
    {

        $SERVER_API_KEY = env('SERVER_API_KEY');

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
}// end BaseController class
