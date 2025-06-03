<?php

namespace App\Imports;

use App\Models\TrainingController;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\TrainingParticipants;
use App\Models\TraineeAssignedTrainingDocument;
use App\Models\Training;
use App\Models\User;
use App\Http\Controllers\BaseController;
use App\Models\EmailAction;
use App\Models\EmailTemplate;
use Notification;
use App\Notifications\AssignTrainingNotification;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, Redirect, Request, Response, Session, URL, View, Validator;

class importParticipants extends BaseController implements ToModel, WithHeadingRow
{

    private $errors = [];
    private $training_id;

    public function getErrors()
    {
        return $this->errors;
    }

    public function __construct($training_id)
    {
        $this->training_id = $training_id;
    }

    public function model(array $row)
    {
        $training_id = $this->training_id;

        $userAlreadyExist = DB::table('users')->where('olms_id', $row['employee_id'])->first();
        if (empty($userAlreadyExist)) {
            $this->errors[] = "User with employee_id {$row['employee_id']} is not  exists please add.";
        }
        if (!empty($userAlreadyExist)) {

            $participantAlreadyExist = DB::table('training_participants')->where('training_id', $training_id)->where('trainee_id', $userAlreadyExist->id)->first();

            if (!empty($participantAlreadyExist)) {
                $this->errors[] = "User with employee_id {$row['employee_id']} is already exists in this training.";
            } else {

                $participant = new TrainingParticipants([
                    'training_id' => $training_id,
                    'trainee_id' => $userAlreadyExist->id,
                ]);

                $courses = DB::table('courses')->where('training_id', $training_id)->get();

                foreach ($courses as $course) {
                    $trainingDocument = DB::table('training_documents')
                        ->where('course_id', $course->id)
                        ->get();
                    foreach ($trainingDocument as $document) {
                        // Save data in TraineeAssignedTrainingDocument model
                        $traineeAssignedTrainingDocument = new TraineeAssignedTrainingDocument([
                            'user_id' => $userAlreadyExist->id,
                            'training_id' => $training_id,
                            'course_id' => $course->id,
                            'document_id' => $document->id,
                            'type' => $document->type,
                            'status' => 0,
                        ]);

                        $traineeAssignedTrainingDocument->save();
                    }
                }

                // Send notification to the user
                $trainingDetail = Training::where('id', $training_id)->first();
                $user = User::find($userAlreadyExist->id);
                $actionUrl = URL::route('userTraining.index');
                $details = [
                    'greeting' => 'New Training Available',
                    'message' => 'New Training Available',
                    'body' => 'You have been assigned a ' . $trainingDetail->title . ' training.',
                    'actionText' => 'View Training',
                    'actionURL' =>  $actionUrl,
                    'training_id' => $training_id,
                ];
                Notification::send($user, new AssignTrainingNotification($details));

                // $settingsEmail             =    Config::get('Site.email');
                // $full_name                 =    $user->fullname;
                // $authEmail                 =    $user->email;
                // // $employeeId                =     $user->olms_id;
                // $employeeId                =    $user->email;
                // $route_url                 =     URL::route('userTraining.index');
                // $click_link                =     $route_url;
                // $emailActions              =     EmailAction::where('action', '=', 'training_assigned')->get()->toArray();
                // //  dd($emailActions);
                // $emailTemplates            =     EmailTemplate::where('action', '=', 'training_assigned')->get(array('name', 'subject', 'action', 'body'))->toArray();
                // $cons                      =     explode(',', $emailActions[0]['options']);
                // $constants                 =     array();
                // foreach ($cons as $key => $val) {
                //     $constants[]           =     '{' . $val . '}';
                // }
                // $subject                   =     $emailTemplates[0]['subject'];
                // $rep_Array                 =     array($full_name, $employeeId, $employeeId, $click_link);
                // $messageBody               =     str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
                // $mail                      =     $this->sendMail($authEmail, $full_name, $subject, $messageBody, $settingsEmail);

                return $participant;
            }
        }
    }
}
