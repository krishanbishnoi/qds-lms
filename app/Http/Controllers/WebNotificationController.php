<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Models\StateDescription;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, Redirect, Request, Response, Session, URL, View, Validator;

/**
 * AdminRoleController Controller
 *
 * Add your methods in the class below
 *
 */
class WebNotificationController extends BaseController
{
    public function storeToken()
    {
        $inputGet            =    Request::all();
        $userId   = $inputGet['user_id'];
        User::where('id', $userId)->update(array('device_token' => $inputGet['device_token']));
        return response()->json(['Token successfully stored.']);
    }

    public function sendWebNotification()
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        // $user_token = User::whereNotNull('device_key')->pluck('device_key')->all();
        $user_token  = User::where('id', Auth::user()->id)->pluck('device_token')->first();
        // $user_token ='fidMP2stcViab0MkHNEBN7:APA91bFmizIegI3VNRMK5tmk4eLjQWo5kRaNP1xYVkLCiYTiJ-ruUI4MHIxlmfZpJXM-RyE_5x57cvXag09K7NaF3JwdAULvN-iwRtslsUtVuAhKO701deY5YOWt9KSNfPQRV4KczxLq';

        $serverKey = 'AAAA6p2seTs:APA91bExTn83tLoc4nsoAc8LqpWltcr63_Iv3N07u5drwJ3chSfW-eLSpA0xnIIsG1a-JXJRls-HJrvKmLbnei8OoC3v1KvXaLtwKxyYQLdfulFRJuVRMJKtpCifhRZ5dndaubN3wbWD';

        $data = [
            'to' => $user_token,
            'notification' => array('title' => 'Tdsadsadt', 'body' => ' ravi'),
            'aps' => array('title' => 'Title', 'body' => 'Body of msg'),
            'data' => array('title' => 'Title', 'body' => 'Body of msgasd')
        ];
        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        // FCM response
        dd($result);
    }
}
