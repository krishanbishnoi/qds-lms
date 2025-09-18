<?php
namespace App\Imports;

use App\Model\Training;
use App\Model\TrainingTestResult;
use App\Model\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class importChangeDesignationHierarchy implements ToModel, WithHeadingRow
{
    private $errors = [];

    public function getErrors()
    {
        return $this->errors;
    }

    public function model(array $row)
    {
        $requiredColumns = ['olms_id'];
        foreach ($requiredColumns as $column) {
            if (! array_key_exists($column, $row)) {
                $this->errors[] = "The Excel file is missing the required column: '{$column}'. Please use the sample file's heading to organize your data and try uploading again.";
                return null;
            }
        }

        // Find user
        $user = User::where('olms_id', $row['olms_id'])->first();
        if (! $user) {
            $this->errors[] = "User with OLMS ID {$row['olms_id']} is not found.";
            return null;
        }

        // Ensure user is active
        if ($user->is_active != 1) {
            $this->errors[] = "User with OLMS ID {$row['olms_id']} is not certified.";
            return null;
        }

        // Check if the user was created after 20 March 2025
        $userCreatedDate = Carbon::parse($user->created_at);
        $cutoffDate      = Carbon::create(2025, 3, 30);

        if ($userCreatedDate->greaterThan($cutoffDate)) {
            // Fetch NHIP training ID
            $nhipTrainingId = Training::where('type', 1)->value('id');

            if (! $nhipTrainingId) {
                $this->errors[] = "NHIP training type not found.";
                return null;
            }

            // Get the user's NHIP training result (average percentage)
            $averagePercentage = TrainingTestResult::where('training_id', $nhipTrainingId)
                ->where('user_id', $user->id)
                ->avg('percentage');

            if ($averagePercentage === null) {
                $this->errors[] = "User with OLMS ID {$row['olms_id']} has not been assigned NHIP training and not passed with 80% or above.";
                return null;
            }
            if ($averagePercentage < 80) {
                $this->errors[] = "User with OLMS ID {$row['olms_id']} did not pass NHIP training with 80% or above.";
                return null;
            }

            // Only update TL, AM, and Manager details if the user has passed NHIP training
            $user->update([
                'tl_name'      => isset($row['tl_name']) ? $row['tl_name'] : $user->tl_name,
                'tl_olms'      => isset($row['tl_olms_id']) ? $row['tl_olms_id'] : $user->tl_olms,
                'am_name'      => isset($row['am_name']) ? $row['am_name'] : $user->am_name,
                'am_olms'      => isset($row['am_olms_id']) ? $row['am_olms_id'] : $user->am_olms,
                'manager_name' => isset($row['manager_name']) ? $row['manager_name'] : $user->manager_name,
                'manager_olms' => isset($row['manager_olms_id']) ? $row['manager_olms_id'] : $user->manager_olms,
            ]);
        } else {
            // If user created before cutoff, update all fields
            $user->update([
                'trainer_name' => isset($row['trainer_name']) ? $row['trainer_name'] : $user->trainer_name,
                'trainer_olms' => isset($row['trainer_olms']) ? $row['trainer_olms'] : $user->trainer_olms,
                'tl_name'      => isset($row['tl_name']) ? $row['tl_name'] : $user->tl_name,
                'tl_olms'      => isset($row['tl_olms_id']) ? $row['tl_olms_id'] : $user->tl_olms,
                'am_name'      => isset($row['am_name']) ? $row['am_name'] : $user->am_name,
                'am_olms'      => isset($row['am_olms_id']) ? $row['am_olms_id'] : $user->am_olms,
                'manager_name' => isset($row['manager_name']) ? $row['manager_name'] : $user->manager_name,
                'manager_olms' => isset($row['manager_olms_id']) ? $row['manager_olms_id'] : $user->manager_olms,
                'location'     => isset($row['location']) ? $row['location'] : $user->location,
            ]);
        }

        return $user;
    }

}
