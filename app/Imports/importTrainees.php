<?php

namespace App\Imports;

use App\Models\User;
use App\Models\UserDetail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\EmailAction;
use App\Models\EmailTemplate;
use App\Models\Partner;
use App\Models\Lob;
use App\Models\Domain;
use App\Models\Designation;
use App\Http\Controllers\BaseController;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, Redirect, Request, Response, Session, URL, View, Validator;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;

class importTrainees extends BaseController implements ToModel, WithHeadingRow
{
    private $errors = [];
    private $skippedRows = [];
    private $allowedEmailDomains = [];

    public function getErrors()
    {
        return $this->errors;
    }

    public function __construct()
    {
        // Fetch the domains from the 'domains' table and store them in the array
        $this->allowedEmailDomains = Domain::pluck('domain')->toArray();
    }
    public function model(array $row)
    {
        $employeeId = $row['employee_id'];
        $trimEmail = trim($row['email']);

        if (empty($employeeId)) {
            $this->errors[] = "Employee Id cannot be null.";
            $this->skippedRows[] = $row;
        } else {


            $userAlreadyExist  =  db::table("users")->where('olms_id', $employeeId)->first();

            if ($userAlreadyExist) {
                // Add the error and fields to the error arrays
                $this->errors[] = "User with Employee Id {$employeeId} already exists.";
                $this->skippedRows[] = $row;
            } else {
                // Convert Excel date to PHP DateTime object
                $dob = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['dob_dd_mmm_yy']);
                // Check if the date_of_birth is less than 18 years from the current date
                $eighteenYearsAgo = Carbon::now()->subYears(18);
                if ($dob > Carbon::now()) {
                    $this->errors[] = "Employee Id $employeeId Date of birth '{$row['dob_dd_mmm_yy']}' cannot be a future date.";
                    $this->skippedRows[] = $row;
                } elseif ($dob > $eighteenYearsAgo) {
                    $this->errors[] = "Employee Id $employeeId Date of birth '{$row['dob_dd_mmm_yy']}' should not be less than 18 years from the current date.";
                    $this->skippedRows[] = $row;
                    // return null; // Return null to skip inserting this row
                } else {
                    $dobFormatted = $dob->format('Y-m-d');
                    // Check if the 'designation' value exists in the 'designations' table
                    $designationName = $row['designation'];

                    $designationExists = Designation::where('designation', $designationName)->exists();

                    if (!$designationExists) {
                        $this->errors[] = "Designation '$designationName' does not found in the 'designations' table. Skipping row.";
                        $this->skippedRows[] = $row;
                        // return; // Skip inserting this row and continue to the next row
                    } else {
                        $validateString =  md5(time() . $trimEmail);

                        // Get the value from the Excel file or assign null if empty

                        $mobileNumber = preg_replace('/[^0-9]/', '', $row['mobile_number_10_digit_only']); // Remove all non-numeric characters

                        if (strlen($mobileNumber) !== 10) {
                            $this->errors[] = "Employee Id $employeeId Mobile number '{$row['mobile_number_10_digit_only']}' is not a valid 10-digit number.";
                            $this->skippedRows[] = $row;
                        } else {
                            $email = $trimEmail;
                            $emailDomain = "@" . substr(strrchr($email, "@"), 1);
                            // Check if the email domain exists in the allowed domains array
                            if (!in_array($emailDomain, $this->allowedEmailDomains)) {
                                $this->errors[] = "Email domain '{$emailDomain}' is not allowed.";
                                $this->skippedRows[] = $row;
                            } else {


                                $dateOfJoining = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['doj_dd_mmm_yy']);
                                $dojFormatted = $dateOfJoining->format('Y-m-d');

                                $user = User::create([
                                    // 'employee_id'           => $row['employee_id'],
                                    'olms_id'               => $employeeId,
                                    'circle'                => $row['circle'],
                                    'location'              => $row['location'],
                                    'first_name'            => $row['first_name'],
                                    'middle_name'           => $row['middle_name'],
                                    'last_name'             => $row['last_name'],
                                    'mobile_number'         => $mobileNumber,
                                    'date_of_birth'         =>  $dobFormatted,
                                    'date_of_joining'       =>  $dojFormatted,
                                    'email'                 => $trimEmail,
                                    'designation'           => $row['designation'],
                                    'gender'                => $row['gender'],
                                    'parent_id'             => Auth::user()->id,
                                    'lob'                   => $row['lob'],
                                    'ext_qa'                => $row['ext_qa'] ?? null,
                                    'ext_qa_olms'           => $row['ext_qa_olms'] ?? null,
                                    'lms_access'            => $row['lms_access'] ?? null,
                                    'crm_id'                => $row['crm_id'] ?? null,
                                    'qms_id'                => $row['qms_id_if_reqd'] ?? null,
                                    'lms_access'            => $row['lms_access'] ?? null,
                                    'trainer_name'          => $row['trainer_name'] ?? null,
                                    'trainer_olms'          => $row['trainer_olms'] ?? null,

                                    'poi'                   => $row['poi_aadhaar_number'] ?? null,
                                    'password'              => Hash::make('Lms@1234'),
                                    'region'                => $row['region'] ?? null,
                                    'employee_id'           => $employeeId,
                                    'user_role_id'          => TRAINEE_ROLE_ID,
                                    'is_active'             => 1,
                                    'is_mobile_verified'    => 1,
                                    'is_email_verified'     => 1,
                                    'is_certified'          => 1,
                                    'validate_string'       => $validateString,
                                    'fullname'              => $row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name'],
                                ]);
                                // return $user;

                                // Create a new UserDetail instance and associate it with the newly created user
                                $userDetail =  UserDetail::create([
                                    // dd($user->olms_id),
                                    'user_id'                          => $user->id,
                                    'olms_id'                          => $user->olms_id,
                                    'first_name'                       => $user->first_name,
                                    'skill_set_1'                      => $row['skill_set_1'] ?? null,
                                    'skill_set_2'                      => $row['skill_set_2'] ?? null,
                                    'skill_set_3'                      => $row['skill_set_3'] ?? null,
                                    'internal_qa'                      => $row['int_qa'] ?? null,
                                    'internal_qa_olms'                 => $row['int_qa_olms'] ?? null,
                                    'supervisor_name'                  => $row['supervisor_name'] ?? null,
                                    'supervisor_olms'                  => $row['supervisor_olms'] ?? null,
                                    'business_manager_airtel'          => $row['business_manager_airtel'] ?? null,
                                    'batch_code'                       => $row['batch_code_alfa_numeric'] ?? null,


                                    // 'certification_date' => isset($row['certification_date']) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['certification_date']) : null,

                                    // // Convert the percentage value to a decimal
                                    // 'final_certification_score'        => floatval(str_replace('%', '', $row['final_certification_score'])) / 100 ?? null,
                                    // // 'final_certification_score'        => $row['final_certification_score'],
                                    // 'final_certification_status'       => $row['final_certification_status'] ?? null,
                                    // 'floor_hit_date'                   => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['floor_hit_date']) ?? null,
                                    'days'                             => $row['days'] ?? null,
                                    'bucket'                           => $row['bucket'] ?? null,
                                ]);
                                // Save the UserDetail instance and associate it with the user
                                $user->userDetails()->save($userDetail);

                                // Check if the user exists and is certified

                                if ($user->is_certified == 1) {
                                    // Check if the designation contains the word "Manager" or "Trainer"
                                    if (stripos($designationName, 'Manager') !== false) {
                                        // Update user_role_id to 4 for Manager
                                        $user->update(['user_role_id' => 4]);
                                    } elseif (stripos($designationName, 'Trainer') !== false) {
                                        // Update user_role_id to 2 for Trainer
                                        $user->update(['user_role_id' => 2]);
                                    } else {
                                        // Update designation only
                                        $user->update(['designation' => $designationName]);
                                    }
                                } else {
                                    $this->errors[] = "User with Employee Id {$row['employee_id']} is not certified.";
                                    // return;
                                }

                                // Send mail to trainees creater for credetials of new created trainee (Krishan)
                                $settingsEmail             =    Config::get('Site.email');
                                // $settingsEmail             =    "info@qdegrees.com";
                                $full_name                 =     $user->fullname;
                                $mobile_number             =    $user->mobile_number;
                                // $authEmail                 =     Auth::user()->email;
                                $authEmail                 =    $trimEmail;
                                // $employeeId                =     $user->olms_id;
                                $employeeId                =    $trimEmail;
                                $password                  =     'Lms@1234';
                                $route_url                 =     URL::to('/login');
                                $click_link                =     $route_url;
                                $emailActions              =     EmailAction::where('action', '=', 'user_registration_information')->get()->toArray();
                                $emailTemplates            =     EmailTemplate::where('action', '=', 'user_registration_information')->get(array('name', 'subject', 'action', 'body'))->toArray();
                                $cons                      =     explode(',', $emailActions[0]['options']);
                                $constants                 =     array();
                                foreach ($cons as $key => $val) {
                                    $constants[]           =     '{' . $val . '}';
                                }
                                $subject                   =     $emailTemplates[0]['subject'];
                                $rep_Array                 =     array($full_name, $employeeId, $mobile_number, $employeeId, $password, $click_link);
                                $messageBody               =     str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
                                $mail                      =     $this->sendMail($authEmail, $full_name, $subject, $messageBody, $settingsEmail);
                            }
                        }
                    }
                }
            }
        }
    }
}
