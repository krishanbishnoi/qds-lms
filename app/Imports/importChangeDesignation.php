<?php

namespace App\Imports;

use App\Models\Designation;
use App\Models\User;
use App\Models\UserDetail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class importChangeDesignation implements ToModel, WithHeadingRow
{
    private $errors = [];
    private $skippedRows = [];

    public function getErrors()
    {
        return $this->errors;
    }

    public function model(array $row)
    {
        // Check if the 'designation' value exists in the 'designations' table
        $designationName = $row['designation'] ?? null;
        $designationExists = Designation::where('designation', $designationName)->first();

        if (!$designationExists) {
            $this->errors[] = "'{$row['olms_id']}'  This  OLMS  Id   Designation  '{$designationName}'  is not registered.";
            // return;
        } else {

            // Check if the user exists and is certified
            $userAlreadyExist = User::where('olms_id', $row['olms_id'])->first();

            if (empty($userAlreadyExist)) {
                $this->errors[] = "User with olms_id {$row['olms_id']} is not found.";
                // return;
            } else {

                if ($userAlreadyExist->is_certified == 1) {
                    // Check if the designation contains the word "Manager" or "Trainer"
                    if (stripos($designationName, 'Manager') !== false) {
                        // Update user_role_id to 4 for Manager
                        $userAlreadyExist->update(['designation' => $designationExists->designation, 'user_role_id' => 4]);
                    } elseif (stripos($designationName, 'Trainer') !== false) {
                        // Update user_role_id to 2 for Trainer
                        $userAlreadyExist->update(['designation' => $designationExists->designation, 'user_role_id' => 2]);
                    } else {
                        // Update designation only
                        $userAlreadyExist->update(['designation' => $designationExists->designation]);
                    }
                } else {
                    $this->errors[] = "User with OLMS ID {$row['olms_id']} is not certified.";
                    // return;
                }
                if (isset($row['mobile_number_10_digit_only']) && !empty($row['mobile_number_10_digit_only'])) {
                    $mobileNumber = preg_replace('/[^0-9]/', '', $row['mobile_number_10_digit_only']); // Remove all non-numeric characters
                    if (strlen($mobileNumber) !== 10) {
                        $this->errors[] = "OLMS ID '{$row['olms_id']}'  Mobile number '{$row['mobile_number_10_digit_only']}' is not a valid 10-digit number.";
                        $this->skippedRows[] = $row;
                    } else {
                        $userAlreadyExist->update(['mobile_number' => $row['mobile_number_10_digit_only']]);
                    }
                }
                if (isset($row['avaya_id_pbx_id']) && !empty($row['avaya_id_pbx_id'])) {
                    $avayaIdPXB = $row['avaya_id_pbx_id'];
                    if (!is_numeric($avayaIdPXB) || strlen($avayaIdPXB) !== 12) {
                        $this->errors[] = "Invalid avaya_id_pbx_id format in the Excel file for OLMS ID ' {$row['olms_id']}'. It should be a 12-digit numeric ID.";
                        $this->skippedRows[] = $row;
                    } else {
                        $userAlreadyExist->update(['avaya_id_pbx_id' => $row['avaya_id_pbx_id']]);
                    }
                }
                // Update additional fields if provided in the Excel file
                if (!empty($row['ext_qa'])) {
                    $userAlreadyExist->update(['ext_qa' => $row['ext_qa']]);
                }

                if (!empty($row['ext_qa_olms'])) {
                    $userAlreadyExist->update(['ext_qa_olms' => $row['ext_qa_olms']]);
                }

                if (!empty($row['crm_id'])) {
                    $userAlreadyExist->update(['crm_id' => $row['crm_id']]);
                }

                if (!empty($row['qms_id_if_reqd'])) {
                    $userAlreadyExist->update(['qms_id' => $row['qms_id_if_reqd']]);
                }

                if (!empty($row['lms_access'])) {
                    $userAlreadyExist->update(['lms_access' => $row['lms_access']]);
                }

                if (!empty($row['trainer_name'])) {
                    $userAlreadyExist->update(['trainer_name' => $row['trainer_name']]);
                }

                if (!empty($row['trainer_olms'])) {
                    $userAlreadyExist->update(['trainer_olms' => $row['trainer_olms']]);
                }

                // return null;
                $userAlreadyExistDetails = UserDetail::where('olms_id', $row['olms_id'])->first();

                if (!empty($row['skill_set_1'])) {
                    $userAlreadyExistDetails->update(['skill_set_1' => $row['skill_set_1']]);
                }

                if (!empty($row['skill_set_2'])) {
                    $userAlreadyExistDetails->update(['skill_set_2' => $row['skill_set_2']]);
                }

                if (!empty($row['skill_set_3'])) {
                    $userAlreadyExistDetails->update(['skill_set_3' => $row['skill_set_3']]);
                }

                if (!empty($row['int_qa'])) {
                    $userAlreadyExistDetails->update(['int_qa' => $row['int_qa']]);
                }

                if (!empty($row['int_qa_olms'])) {
                    $userAlreadyExistDetails->update(['int_qa_olms' => $row['int_qa_olms']]);
                }
                if (!empty($row['supervisor_name'])) {
                    $userAlreadyExistDetails->update(['supervisor_name' => $row['supervisor_name']]);
                }
                if (!empty($row['supervisor_olms'])) {
                    $userAlreadyExistDetails->update(['supervisor_olms' => $row['supervisor_olms']]);
                }
                if (!empty($row['business_manager_airtel'])) {
                    $userAlreadyExistDetails->update(['business_manager_airtel' => $row['business_manager_airtel']]);
                }
            }
        }
    }
}
