<?php

namespace App\Imports;

use App\Http\Controllers\BaseController;
use App\Model\Batch;
use App\Model\Designation;
use App\Model\Domain;
use App\Model\EmailAction;
use App\Model\EmailTemplate;
use App\Model\Lob;
use App\Model\Partner;
use App\Model\User;
use App\Model\UserDetail;
use Auth;
use Config;
use DB;
use File;
use Hash;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Mail;
use URL;

class importTrainees extends BaseController implements ToModel, WithHeadingRow
{
    private $errors = [];

    private $skippedRows = [];

    private $allowedEmailDomains = [];
    private $batchId;

    public function __construct($batchId = null)
    {
        $this->allowedEmailDomains = Domain::pluck('domain')->toArray();
        $this->batchId = $batchId;
    }
    public function getErrors()
    {
        return $this->errors;
    }
    public function model(array $row)
    {
        $olms = $row['olms_id'];
        if (strlen($olms) !== 8 and !ctype_alnum($olms)) {
            $this->errors[] = "OLMS ID '{$olms}' should be exactly 8 characters long and alphanumeric.";
            $this->skippedRows[] = $row;
        } else {
            if (empty($olms)) {
                $this->errors[] = 'OLMS ID cannot be null.';
                $this->skippedRows[] = $row;
            } else {

                // if (empty($olms)) {
                //     $olmsPrefix = 'A1'; // The prefix for olms_id
                //     $olmsRandom = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', 6)), 0, 6);
                //     $olms = $olmsPrefix . $olmsRandom;
                // }
                // // Check if the olms_id already exists in the database
                // $existingUser = User::where('olms_id', $olms)->first();
                // while ($existingUser) {
                //     // Generate a new random string and update olms
                //     $olmsRandom = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', 6)), 0, 6);
                //     $olms = $olmsPrefix . $olmsRandom;
                //     // Check again if the new olms_id exists in the database
                //     $existingUser = User::where('olms_id', $olms)->first();
                // }

                $userAlreadyExist = db::table('users')->where('olms_id', $olms)->first();

                if ($userAlreadyExist) {
                    // Add the error and fields to the error arrays
                    $this->errors[] = "User with OLMS ID {$olms} already exists.";
                    $this->skippedRows[] = $row;
                } else {
                    // Convert Excel date to PHP DateTime object
                    $dob = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['dob_dd_mmm_yy']);
                    // Check if the date_of_birth is less than 18 years from the current date
                    $eighteenYearsAgo = Carbon::now()->subYears(18);
                    if ($dob > Carbon::now()) {
                        $this->errors[] = "OLMS ID $olms Date of birth '{$row['dob_dd_mmm_yy']}' cannot be a future date.";
                        $this->skippedRows[] = $row;
                    } elseif ($dob > $eighteenYearsAgo) {
                        $this->errors[] = "OLMS ID $olms Date of birth '{$row['dob_dd_mmm_yy']}' should not be less than 18 years from the current date.";
                        $this->skippedRows[] = $row;
                        // return null; // Return null to skip inserting this row
                    } else {
                        $dobFormatted = $dob->format('Y-m-d');
                        if (isset($row['avaya_id_pbx_id']) && !empty($row['avaya_id_pbx_id'])) {
                            // Use the value from the Excel file
                            $avayaIdPXB = $row['avaya_id_pbx_id'];
                            // Check if the avaya_id_pbx_id is numeric and 12 digits long
                            if (!is_numeric($avayaIdPXB) || strlen($avayaIdPXB) !== 12) {
                                // Add an error and skip this row if the value is not a valid 12-digit numeric ID
                                $this->errors[] = "Invalid avaya_id_pbx_id format in the Excel file for OLMS ID {$olms}. It should be a 12-digit numeric ID.";
                                $this->skippedRows[] = $row;
                            }
                        } else {
                            $avayaIdPXB = $this->generateUniqueAvayaIdPXB();
                        }
                        // condition for match partner and location from partners table
                        $circle = $row['center'];
                        $location = $row['location'];
                        $partnerExists = Partner::where('name', $circle)
                            ->where('location', $location)
                            ->exists();

                        if (!$partnerExists) {
                            $this->errors[] = "No matching partner found for circle: '$circle' and location: '$location'. Skipping row.";
                            $this->skippedRows[] = $row;
                        } else {
                            $lobName = $row['lob'];

                            $lobExists = Lob::where('lob', $lobName)->exists();

                            if (!$lobExists) {
                                $this->errors[] = "Lob '$lobName' does not found in the 'lobs'. Skipping row.";
                                $this->skippedRows[] = $row;
                                // return; // Skip inserting this row and continue to the next row
                            } else {
                                // Check if the 'designation' value exists in the 'designations' table
                                $designationName = $row['designation'];

                                $designationExists = Designation::where('designation', $designationName)->exists();

                                if (!$designationExists) {
                                    $this->errors[] = "Designation '$designationName' does not found in the 'designations' table. Skipping row.";
                                    $this->skippedRows[] = $row;
                                    // return; // Skip inserting this row and continue to the next row
                                } else {
                                    $validateString = md5(time() . $row['email']);

                                    // Get the value from the Excel file or assign null if empty

                                    $mobileNumber = preg_replace('/[^0-9]/', '', $row['mobile_number_10_digit_only']); // Remove all non-numeric characters

                                    if (strlen($mobileNumber) !== 10) {
                                        $this->errors[] = "OLMS ID $olms Mobile number '{$row['mobile_number_10_digit_only']}' is not a valid 10-digit number.";
                                        $this->skippedRows[] = $row;
                                    } else {
                                        $email = $row['email'];
                                        $emailDomain = '@' . substr(strrchr($email, '@'), 1);
                                        // Check if the email domain exists in the allowed domains array
                                        if (!in_array($emailDomain, $this->allowedEmailDomains)) {
                                            $this->errors[] = "Email domain '{$emailDomain}' is not allowed.";
                                            $this->skippedRows[] = $row;
                                        } else {
                                            if (!ctype_alnum($row['batch_code_alfa_numeric'])) {
                                                $this->errors[] = 'Batch Code should contain only alphanumeric characters.';
                                                $this->skippedRows[] = $row;
                                            } else {

                                                if ($row['designation'] !== 'Trainee') {
                                                    // Check for fields that should not be blank
                                                    $requiredFields = [
                                                        'batch_code_alfa_numeric',
                                                        'certification_date',
                                                        'final_certification_score',
                                                        'final_certification_status',
                                                        'floor_hit_date',
                                                    ];

                                                    foreach ($requiredFields as $field) {
                                                        if (empty($row[$field])) {
                                                            $this->errors[] = "For non-Trainee designation, field '{$field}' should not be blank.";
                                                            $this->skippedRows[] = $row;
                                                        }
                                                    }
                                                    if (!ctype_alnum($row['batch_code_alfa_numeric'])) {
                                                        $this->errors[] = 'For non-Trainee designation, batch_code_alfa_numeric should contain only alphanumeric characters.';
                                                        $this->skippedRows[] = $row;
                                                    }
                                                } else {

                                                    $dateOfJoining = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['doj_dd_mmm_yy']);
                                                    $dojFormatted = $dateOfJoining->format('Y-m-d');

                                                    if ($this->batchId !== null) {
                                                        // Use the batch ID passed from the controller
                                                        $batchId = $this->batchId;
                                                    } else {
                                                        // Use the batch name from the row to get the batch ID
                                                        $batch = Batch::where('name', $row['batch'])->first();
                                                        if (!$batch) {
                                                            $this->errors[] = "Batch '{$row['batch']}' does not exist.";
                                                            $this->skippedRows[] = $row;
                                                            return;
                                                        } else {
                                                            $batchId = $batch->id;
                                                        }
                                                    }
                                                    $user = User::create([
                                                        // 'employee_id'           => $row['employee_id'],
                                                        'olms_id' => $olms,
                                                        'batch_id' => $batchId,
                                                        'circle' => $row['center'],
                                                        'location' => $row['location'],
                                                        'parent_id' => Auth::user()->id,
                                                        'first_name' => $row['first_name'],
                                                        'middle_name' => $row['middle_name'],
                                                        'last_name' => $row['last_name'],
                                                        'mobile_number' => $mobileNumber,
                                                        'date_of_birth' => $dobFormatted,
                                                        'date_of_joining' => $dojFormatted,
                                                        'email' => $row['email'],
                                                        'designation' => $row['designation'],
                                                        'gender' => $row['gender'],
                                                        'lob' => $row['lob'],
                                                        'avaya_id_pbx_id' => $avayaIdPXB,
                                                        'ext_qa' => $row['ext_qa'],
                                                        'ext_qa_olms' => $row['ext_qa_olms'],
                                                        'lms_access' => $row['lms_access'],
                                                        'qms_id' => $row['qms_id_if_reqd'],
                                                        'trainer_name' => $row['trainer_name'],
                                                        'trainer_olms' => $row['trainer_olms'],

                                                        'poi' => $row['poi_aadhaar_number'] ?? null,
                                                        'password' => Hash::make('Airtel@123'),
                                                        'region' => $row['region'] ?? null,
                                                        'employee_id' => $olms,
                                                        'user_role_id' => 3,
                                                        'is_active' => 1,
                                                        'is_mobile_verified' => 1,
                                                        'is_email_verified' => 1,
                                                        'is_certified' => 1,
                                                        'validate_string' => $validateString,
                                                        'fullname' => $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'],
                                                    ]);
                                                    // return $user;

                                                    // Create a new UserDetail instance and associate it with the newly created user
                                                    $userDetail = UserDetail::create([
                                                        // dd($user->olms_id),
                                                        'user_id' => $user->id,
                                                        'olms_id' => $user->olms_id,
                                                        'internal_qa' => $row['int_qa'],
                                                        'internal_qa_olms' => $row['int_qa_olms'],
                                                        'supervisor_name' => $row['supervisor_name'],
                                                        'supervisor_olms' => $row['supervisor_olms'],
                                                        'business_manager_airtel' => $row['business_manager_airtel'],
                                                        'batch_code' => $row['batch_code_alfa_numeric'],
                                                        'certification_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['certification_date']),
                                                        // Convert the percentage value to a decimal
                                                        'final_certification_score' => floatval(str_replace('%', '', $row['final_certification_score'])) / 100,
                                                        // 'final_certification_score'        => $row['final_certification_score'],
                                                        'final_certification_status' => $row['final_certification_status'],
                                                        'floor_hit_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['floor_hit_date']),
                                                        'days' => $row['days'],
                                                        'bucket' => $row['bucket'],
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
                                                        $this->errors[] = "User with OLMS ID {$row['olms_id']} is not certified.";
                                                        // return;
                                                    }

                                                    // Send mail to trainees creater for credetials of new created trainee (Krishan)
                                                    $settingsEmail = Config::get('Site.email');
                                                    $full_name = $user->fullname;
                                                    $mobile_number = $user->mobile_number;
                                                    $authEmail = Auth::user()->email;
                                                    $olms = $user->olms_id;
                                                    $password = 'Airtel@123';
                                                    $route_url = URL::to('/login');
                                                    $click_link = $route_url;
                                                    $emailActions = EmailAction::where('action', '=', 'user_registration_information')->get()->toArray();
                                                    $emailTemplates = EmailTemplate::where('action', '=', 'user_registration_information')->get(['name', 'subject', 'action', 'body'])->toArray();
                                                    $cons = explode(',', $emailActions[0]['options']);
                                                    $constants = [];
                                                    foreach ($cons as $key => $val) {
                                                        $constants[] = '{' . $val . '}';
                                                    }
                                                    $subject = $emailTemplates[0]['subject'];
                                                    $rep_Array = [$full_name, $olms, $mobile_number, $olms, $password];
                                                    $messageBody = str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
                                                    $mail = $this->sendMail($authEmail, $full_name, $subject, $messageBody, $settingsEmail);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function generateUniqueAvayaIdPXB()
    {
        // Generate a random 12-digit numeric ID
        $avayaIdPXB = str_pad(mt_rand(1, 999999999999), 12, '0', STR_PAD_LEFT);
        // Check if the generated ID already exists in the database
        while (User::where('avaya_id_pbx_id', $avayaIdPXB)->exists()) {
            $avayaIdPXB = str_pad(mt_rand(1, 999999999999), 12, '0', STR_PAD_LEFT);
        }

        return $avayaIdPXB;
    }
}
