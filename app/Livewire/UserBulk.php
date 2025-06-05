<?php

namespace App\Livewire;

use App\Models\Circle;
use App\Models\Designation;
use App\Models\Domain;
use App\Models\EmailTemplate;
use App\Models\Lob;
use App\Models\Region;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Http\Controllers\BaseController;
use App\Models\EmailAction;

class UserBulk extends Component
{
    use WithFileUploads;
    public $roles;
    public $test_id;
    public $file;
    public $isLoading = false;  // Add this property to track the loader state
    public $uploadedData = [];
    public $selectedData = [];
    public $isModalOpen = false;
    public $headers = [];
    public $rowErrors = []; // Store errors for specific rows and columns


    public function mount($test_id = 0)
    {
        $this->test_id = $test_id;
    }

    private function isDateColumn($columnName)
    {
        $dateColumns = ['Question']; // Add expected date column names
        return in_array($columnName, $dateColumns);
    }

    public function convertToSnakeCase($string)
    {
        $string = preg_replace('/\s+/', '_', $string);
        $string = str_replace('*', '', $string);
        $string = strtolower($string);
        return $string;
    }


    private function convertRowKeys(array $row): array
    {
        $converted = [];
        foreach ($row as $key => $value) {
            $converted[$this->convertToSnakeCase($key)] = $value;
        }
        return $converted;
    }
    public function uploadUsers()
    {
        $this->resetImportState();

        $this->isLoading = true;
        if ($this->file) {

            try {
                // Convert the Excel file into an array
                $data = Excel::toArray([], $this->file)[0];
                // dd($data);
                if (empty($data) || !isset($data[0])) {
                    session()->flash('errorMsg', ['The file is empty or has invalid formatting.']);
                    return;
                }


                // Extract headers dynamically and remove empty ones
                $this->headers = array_values(array_filter($data[0], function ($header) {
                    return !empty($header);
                }));

                // Process the remaining rows dynamically
                $this->uploadedData = array_filter(array_map(function ($row) {
                    // Skip rows that are truly empty (not ones with false)
                    if (collect($row)->every(fn($cell) => is_null($cell) || $cell === '')) {
                        return null;
                    }

                    // Match row with header count
                    $row = array_pad($row, count($this->headers), null);
                    $row = array_slice($row, 0, count($this->headers));

                    // Convert Excel date fields dynamically
                    foreach ($this->headers as $index => $columnName) {
                        // Convert boolean false in "Question" column to string "false"
                        if ($columnName === 'Question' && $row[$index] === false) {
                            $row[$index] = 'FALSE';
                        }

                        // Date conversion
                        if ($this->isDateColumn($columnName) && isset($row[$index]) && is_numeric($row[$index])) {
                            $row[$index] = Date::excelToDateTimeObject($row[$index])->format('d/m/Y');
                        }
                    }

                    return array_combine($this->headers, $row);
                }, array_slice($data, 1)));



                // Remove null rows
                $this->uploadedData = array_values($this->uploadedData);

                // Display the modal with data
                $this->isModalOpen = true;
                $this->isLoading = false;
            } catch (\Exception $e) {
                session()->flash('errorMsg', ['Error processing the file. Please check the format and try again.']);
            }
        } else {
            session()->flash('errorMsg', ['No file uploaded.']);
        }
    }
    public function processSelectedData()
    {
        if (empty($this->selectedData)) {
            session()->flash('errorMsg', 'No data selected for processing.');
            return;
        }

        $this->rowErrors = [];
        $successCount = 0;

        // Start a database transaction
        DB::beginTransaction();

        try {
            // First pass: Validate all selected rows
            foreach ($this->uploadedData as $rowKey => $row) {
                if (!in_array($rowKey, $this->selectedData)) {
                    continue;
                }

                $convertedRow = $this->convertRowKeys($row);
                $this->validateUserRow($convertedRow, $rowKey);
            }

            // Second pass: Process valid rows
            foreach ($this->uploadedData as $rowKey => $row) {
                if (!in_array($rowKey, $this->selectedData)) {
                    continue;
                }

                $convertedRow = $this->convertRowKeys($row);
                $this->createUserWithDetails($convertedRow, $rowKey);
                $successCount++;
            }

            DB::commit();

            if ($successCount > 0) {
                $this->file = null;
                $this->isModalOpen = false;
                session()->flash('success', "{$successCount} row(s) successfully saved!");
                return redirect(request()->header('Referer'));
            }
        } catch (\Exception $e) {
            // dd($e);
            DB::rollBack();
            $this->handleImportError($e);
        }
    }

    private function validateUserRow(array $data, string $rowKey)
    {
        // dd($data);
        // Validate required fields
        $validator = Validator::make($data, [
            'employee_id' => 'required',
            'email' => 'required|email',
            'designation' => 'required',
            // 'dob_dd_mmm_yy' => 'required',
            // 'doj_dd_mmm_yy' => 'required',
            'mobile_number' => 'required',
            'last_name' => 'required',
            'first_name' => 'required',
            'lob' => 'required',
            'region' => 'required',
            'circle' => 'required',
            'gender' => 'required',
        ]);

        if ($validator->fails()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => $validator->errors()->toArray()
            ]));
        }

        // Check for duplicate employee ID
        if (User::where('olms_id', $data['employee_id'])->exists()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['employee_id' => ['User with this Employee ID already exists']]
            ]));
        }
        // Validate LOB
        if (!empty($data['lob']) && !Lob::where('lob', $data['lob'])->exists()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['lob' => ["LOB '{$data['lob']}' not found in the system"]]
            ]));
        }

        // Validate Region
        if (!empty($data['region']) && !Region::where('region', $data['region'])->exists()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['region' => ["Region '{$data['region']}' not found in the system"]]
            ]));
        }

        // Validate Circle
        if (!empty($data['circle']) && !Circle::where('circle', $data['circle'])->exists()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['circle' => ["Circle '{$data['circle']}' not found in the system"]]
            ]));
        }

        if (!empty($data['gender']) && !in_array($data['gender'], ['Male', 'Female'], true)) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['gender' => ["Gender must be either 'Male' or 'Female'"]]
            ]));
        }
        // Validate date of birth
        // $dob = Date::excelToDateTimeObject($data['dob_dd_mmm_yy']);
        // $eighteenYearsAgo = now()->subYears(18);

        // if ($dob > now()) {
        //     throw new \Exception(json_encode([
        //         'row' => $rowKey,
        //         'errors' => ['dob_dd_mmm_yy' => ['Date of birth cannot be in the future']]
        //     ]));
        // } elseif ($dob > $eighteenYearsAgo) {
        //     throw new \Exception(json_encode([
        //         'row' => $rowKey,
        //         'errors' => ['dob_dd_mmm_yy' => ['User must be at least 18 years old']]
        //     ]));
        // }

        // Validate designation exists
        if (!Designation::where('designation', $data['designation'])->exists()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['designation' => ['Designation not found in system']]
            ]));
        }

        // Validate mobile number format
        $mobileNumber = preg_replace('/[^0-9]/', '', $data['mobile_number']);
        if (strlen($mobileNumber) !== 10) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['mobile_number' => ['Mobile number must be 10 digits']]
            ]));
        }
        $allowedEmailDomains = Domain::pluck('domain')->toArray();
        // Validate email domain
        $emailDomain = "@" . substr(strrchr($data['email'], "@"), 1);
        if (!in_array($emailDomain, $allowedEmailDomains)) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['email' => ['Email domain is not allowed']]
            ]));
        }
    }
    private function handleImportError(\Exception $e)
    {
        // dd($e);

        $errorData = json_decode($e->getMessage(), true);

        if (json_last_error() === JSON_ERROR_NONE && isset($errorData['row'])) {
            $this->rowErrors[$errorData['row']] = $errorData['errors'];
            session()->flash('errorMsg', 'Some rows have errors. No data was saved. Please review.');
        } else {
            session()->flash('errorMsg', 'Error during import: ' . $e->getMessage());
        }
    }
    private function createUserWithDetails(array $data, string $rowKey)
    {
        // Format dates
        // $dob = Date::excelToDateTimeObject($data['dob_dd_mmm_yy'])->format('Y-m-d');
        // $doj = Date::excelToDateTimeObject($data['doj_dd_mmm_yy'])->format('Y-m-d');
        $mobileNumber = preg_replace('/[^0-9]/', '', $data['mobile_number']);
        $email = trim($data['email']);
        $validateString = md5(time() . $email);
        $fullName = trim("{$data['first_name']} {$data['last_name']}");

        // Determine role based on designation
        $designationName = $data['designation'];
        $roleId = TRAINEE_ROLE_ID; // Default role

        if (stripos($designationName, 'Manager') !== false) {
            $roleId = 4; // Manager role
        } elseif (stripos($designationName, 'Trainer') !== false) {
            $roleId = 2; // Trainer role
        }

        // Create user
        $user = User::create([
            'olms_id' => $data['employee_id'],
            'circle' => $data['circle'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'mobile_number' => $mobileNumber,
            'date_of_birth' => null,
            'date_of_joining' => null,
            'email' => $email,
            'designation' => $designationName,
            'gender' => strtolower($data['gender']),
            'parent_id' => Auth::id(),
            'lob' => $data['lob'],
            'ext_qa' => $data['ext_qa'] ?? null,
            'ext_qa_olms' => $data['ext_qa_olms'] ?? null,
            'lms_access' => $data['lms_access'] ?? null,
            'crm_id' => $data['crm_id'] ?? null,
            'qms_id' => $data['qms_id_if_reqd'] ?? null,
            'trainer_name' => $data['trainer_name'] ?? null,
            'trainer_olms' => $data['trainer_olms'] ?? null,
            'poi' => $data['poi_aadhaar_number'] ?? null,
            'password' => Hash::make('Lms@1234'),
            'region' => $data['region'] ?? null,
            'employee_id' => $data['employee_id'],
            'user_role_id' => $roleId,
            'is_active' => 1,
            'is_mobile_verified' => 1,
            'is_email_verified' => 1,
            'is_certified' => 1,
            'validate_string' => $validateString,
            'fullname' => $fullName,
        ]);

        // Create user details
        UserDetail::create([
            'user_id' => $user->id,
            'olms_id' => $user->olms_id,
            'first_name' => $user->first_name,
            'skill_set_1' => $data['skill_set_1'] ?? null,
            'skill_set_2' => $data['skill_set_2'] ?? null,
            'skill_set_3' => $data['skill_set_3'] ?? null,
            'internal_qa' => $data['int_qa'] ?? null,
            'internal_qa_olms' => $data['int_qa_olms'] ?? null,
            'supervisor_name' => $data['supervisor_name'] ?? null,
            'supervisor_olms' => $data['supervisor_olms'] ?? null,
            'business_manager_airtel' => $data['business_manager_airtel'] ?? null,
            'batch_code' => $data['batch_code_alfa_numeric'] ?? null,
            'days' => $data['days'] ?? null,
            'bucket' => $data['bucket'] ?? null,
        ]);

        // Send welcome email if needed
        $this->sendWelcomeEmail($user, $email);
    }

    private function sendWelcomeEmail(User $user, string $email)
    {
        // dd($email);
        $settingsEmail = config('Site.email');
        $fullName = $user->fullname;
        $mobileNumber = $user->mobile_number;
        $password = 'Lms@1234';
        $loginUrl = match ($user->user_role_id) {
            MANAGER_ROLE_ID => URL::to('/admin/login'),
            TRAINER_ROLE_ID => URL::to('/trainer'),
            default => URL::to('/login'),
        };

        $emailAction = EmailAction::where('action', 'user_registration_information')->first();
        $emailTemplate = EmailTemplate::where('action', 'user_registration_information')
            ->first(['name', 'subject', 'action', 'body']); // ← fixed here

        if ($emailAction && $emailTemplate) {
            $constants = array_map(fn($val) => '{' . trim($val) . '}', explode(',', $emailAction->options));

            $replacements = [
                $fullName,              // {full_name}
                $email,                 // {email}
                $user->olms_id,         // {employee_id}
                $mobileNumber,          // {mobile_number}
                $password,              // {password}
                $loginUrl               // {click_link}
            ];

            $messageBody = str_replace($constants, $replacements, $emailTemplate->body);

            $baseController = new BaseController();
            $baseController->sendMail($email, $fullName, $emailTemplate->subject, $messageBody, $settingsEmail);
        }
    }


    public function closeModal()
    {
        $this->resetImportState();

        $this->isLoading = false;
        $this->file = null;
    }
    private function resetImportState()
    {
        $this->rowErrors = [];
        $this->uploadedData = [];
        $this->selectedData = [];
        $this->headers = [];
        $this->isModalOpen = false;
    }
    public function render()
    {
        return view('livewire.user-bulk');
    }
}
