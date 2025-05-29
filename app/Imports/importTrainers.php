<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\EmailAction;
use App\Models\EmailTemplate;
use App\Http\Controllers\BaseController;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, Redirect, Request, Response, Session, URL, View, Validator;

class importTrainers  extends BaseController implements ToModel, WithHeadingRow
{
    private $errors = [];

    public function getErrors()
    {
        return $this->errors;
    }

    public function model(array $row)
    {
        $trimEmail = trim($row['email']);

        $userAlreadyExist            =     db::table("users")->where('email', $trimEmail)->first();
        if (!empty($userAlreadyExist)) {
            $this->errors[] = "User with email {$trimEmail} already exists.";
            return new User(); // Skip importing this row
        }
        if (empty($userAlreadyExist)) {
            $validateString                            =  md5(time() . $trimEmail);
            // $obj->validate_string					=  $validateString;
            // Get the value from the Excel file or assign null if empty
            $olms = $row['olms_id'] ?? null;
            if (empty($olms)) {
                $olmsPrefix = 'A1'; // The prefix for olms_id
                $olmsRandom = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', 6)), 0, 6);
                $olms = $olmsPrefix . $olmsRandom;
            }
            // Check if the olms_id already exists in the database
            $existingUser = User::where('olms_id', $olms)->first();
            while ($existingUser) {
                // Generate a new random string and update olms
                $olmsRandom = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', 6)), 0, 6);
                $olms = $olmsPrefix . $olmsRandom;
                // Check again if the new olms_id exists in the database
                $existingUser = User::where('olms_id', $olms)->first();
            }

            $user = new User([
                // 'employee_id'           => $row['employee_id'],
                'first_name'            => $row['first_name'],
                'last_name'             => $row['last_name'],
                'lob'                   => $row['lob'],
                'circle'                => $row['center'],
                'email'                 => $trimEmail,
                'mobile_number'         => $row['mobile_number'],
                'location'              => $row['location'],
                'date_of_birth'         =>  \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['dob']),
                'gender'                => $row['gender'],
                'poi'                   => $row['poi_aadhaar_number'],
                'designation'           => $row['designation'],
                'password'              => Hash::make('Lms@1234'),
                'region'                => $row['region'] ?? null,
                'olms_id'               => $olms,
                'employee_id'           => $olms,
                'user_role_id'          => TRAINER_ROLE_ID,
                'is_active'             => 1,
                'is_mobile_verified'    => 1,
                'is_email_verified'     => 1,
                'validate_string'       => $validateString,
                'fullname'              => $row['first_name'] . " " . $row['last_name'],
            ]);

            $settingsEmail             =    Config::get('Site.email');
            $full_name                 =     $user->fullname;
            $mobile_number             =    $user->mobile_number;
            $email                     =     $user->email;
            $password                  =     $row['password'];
            $route_url                 =     URL::to('/login');
            $click_link                =   $route_url;
            $emailActions              =     EmailAction::where('action', '=', 'user_registration_information')->get()->toArray();
            $emailTemplates            =     EmailTemplate::where('action', '=', 'user_registration_information')->get(array('name', 'subject', 'action', 'body'))->toArray();
            $cons                      =     explode(',', $emailActions[0]['options']);
            $constants                 =     array();
            foreach ($cons as $key => $val) {
                $constants[]           =     '{' . $val . '}';
            }
            $subject                   =     $emailTemplates[0]['subject'];
            $rep_Array                 =     array($full_name, $email, $mobile_number, $email, $password);
            $messageBody               =     str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
            $mail                      =     $this->sendMail($email, $full_name, $subject, $messageBody, $settingsEmail);

            return $user;
        }
    }
}
