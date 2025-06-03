<?php

namespace App\Imports;

use App\Models\TestController;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\TestParticipants;
use App\Models\TestAttendee;
use App\Models\Test;
use App\Models\User;
use App\Models\EmailAction;
use App\Models\EmailTemplate;
use App\Http\Controllers\BaseController;
use Notification;
use App\Notifications\AssignTestNotification;
use Illuminate\Support\Facades\Crypt;

use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, Redirect, Request, Response, Session, URL, View, Validator;

class importTestsParticipants extends BaseController implements ToModel, WithHeadingRow
{
    private $errors = [];
    private $test_id;

    public function getErrors()
    {
        return $this->errors;
    }

    public function __construct($test_id)
    {
        $this->test_id = $test_id;
    }

    public function model(array $row)
    {
        $test_id = $this->test_id;

        $trimEmail = trim($row['email']);
        $TestNumberOfAttempts = Test::where('id', $test_id)->first();
        $userAlreadyExist = DB::table('users')->where('email', $trimEmail)->first();
        if (empty($userAlreadyExist)) {

            $testAttendeeAlreadyExist = DB::table('test_attendees')->where('email', $trimEmail)->where('test_id', $test_id)->first();
            if (empty($testAttendeeAlreadyExist)) {

                $testAttendee = TestAttendee::create([
                    'email'   => $trimEmail,
                    'test_id'  => $test_id,
                ]);

                $participant = TestParticipants::create([
                    'test_id' => $test_id,
                    'trainee_id' => $testAttendee->link_id,
                    'number_of_attempts' => $TestNumberOfAttempts->number_of_attempts,
                    'type'     => 2,
                ]);

                // Send mail to trainees creater for credetials of new created trainee ( Krishan )
                // $settingsEmail             =    Config::get('Site.email');
                // $full_name                 =     $testAttendee->email;
                // $authEmail                 =     $trimEmail;
                // // $employeeId                =     $user->olms_id;
                // $employeeId                =     $trimEmail;
                // $linkId = $testAttendee->link_id;
                // $encryptedLinkId = Crypt::encrypt($linkId);
                // $route_url                 =     URL::to('test-details/' . $test_id . '/' . $encryptedLinkId);
                // $click_link                =     $route_url;
                // $emailActions              =     EmailAction::where('action', '=', 'product_status')->get()->toArray();
                // $emailTemplates            =     EmailTemplate::where('action', '=', 'product_status')->get(array('name', 'subject', 'action', 'body'))->toArray();
                // $cons                      =     explode(',', $emailActions[0]['options']);
                // $constants                 =     array();
                // foreach ($cons as $key => $val) {
                //     $constants[]           =     '{' . $val . '}';
                // }
                // $subject                   =     $emailTemplates[0]['subject'];
                // $rep_Array                 =     array($full_name, $employeeId, $employeeId, $click_link);
                // $messageBody               =     str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
                // $mail                      =     $this->sendMail($authEmail, $full_name, $subject, $messageBody, $settingsEmail);

                // return $testAttendee;
            } else {
                $this->errors[] = "User with Email {$trimEmail} is already exists in this Test.";
            }
        }
        if (!empty($userAlreadyExist)) {
            $participantAlreadyExist = DB::table('test_participants')->where('test_id', $test_id)->where('trainee_id', $userAlreadyExist->id)->first();

            if (!empty($participantAlreadyExist)) {
                $this->errors[] = "User with Email {$trimEmail} is already exists in this Test.";
            } else {

                $participant = new TestParticipants([
                    'test_id' => $test_id,
                    'trainee_id' => $userAlreadyExist->id,
                    'number_of_attempts' => $TestNumberOfAttempts->number_of_attempts,
                    'type'     => 1,
                ]);

                // Send notification to the user
                $TestDetail = Test::where('id', $test_id)->first();
                $user = User::find($userAlreadyExist->id);
                $actionUrl = URL::route('userTest.index');
                $details = [
                    'greeting' => 'New Test Available',
                    'message' => 'New Test Available',
                    'body' => 'You have been assigned a ' . $TestDetail->title . ' test.',
                    'actionText' => 'View Test',
                    'actionURL' =>  $actionUrl,
                    'training_id' => $test_id,
                ];
                Notification::send($user, new AssignTestNotification($details));
                // Send mail to trainees creater for credetials of new created trainee ( Krishan )
                $settingsEmail             =    Config::get('Site.email');
                $full_name                 =     $user->email;
                $authEmail                 =     $user->email;
                // $employeeId                =     $user->olms_id;
                $employeeId                =     $user->email;
                $route_url                 =     URL::to('/login');
                $click_link                =     $route_url;
                $emailActions              =     EmailAction::where('action', '=', 'product_status')->get()->toArray();
                $emailTemplates            =     EmailTemplate::where('action', '=', 'product_status')->get(array('name', 'subject', 'action', 'body'))->toArray();
                $cons                      =     explode(',', $emailActions[0]['options']);
                $constants                 =     array();
                foreach ($cons as $key => $val) {
                    $constants[]           =     '{' . $val . '}';
                }
                $subject                   =     $emailTemplates[0]['subject'];
                $rep_Array                 =     array($full_name, $employeeId, $employeeId, $click_link);
                $messageBody               =     str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
                $mail                      =     $this->sendMail($authEmail, $full_name, $subject, $messageBody, $settingsEmail);
                return $participant;
            }
        }
    }
}
