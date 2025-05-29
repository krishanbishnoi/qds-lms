<?php

namespace App\Helpers;
use App\Models\Settings;

class Helper {

    public static function getWebSettings($name){

        $Settings = Settings::where('name','=',$name)->pluck('value');
        if(!empty($Settings[0])){
            return $Settings[0];
        }
        else{
            return '';
        }

    }


    public static function getSateCity($pin){


        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://www.postalpincode.in/api/pincode/'.$pin,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $resp = json_decode($response);
        if(isset($resp->PostOffice[0]) && !empty($resp->PostOffice[0])){
            $res = $resp->PostOffice[0];
        }else{
             $res = [];
        }
        return $res;
    }


}
