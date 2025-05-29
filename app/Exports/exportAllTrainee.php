<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Http\Controllers\BaseController;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;


class exportAllTrainee extends BaseController implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize

{
    public function collection()
    {

        $users = User::select(
            'id',
            'olms_id',
            'email',
            'password',
            'employee_id',
            'first_name',
            'middle_name',
            'avaya_id_pbx_id',
            'fullname',
            'last_name',
            'lob',
            'circle',
            'gender',
            'poi',
            'mobile_number',
            'password',
            'region',
            'user_role_id',
            'validate_string',
            'olms_id',
            'designation',
            'date_of_birth',
            'ext_qa',
            'ext_qa_olms',
            'crm_id',
            'qms_id',
            'lms_access',
            'trainer_name',
            'trainer_olms',
            'location',
            'is_active',
            'created_at',
            'date_of_joining',
        )
            ->where('is_deleted', 0)
            ->where('is_developer', 0)
            ->where('user_role_id', '!=', SUPER_ADMIN_ROLE_ID)
            ->with('userDetails') // Load user details relationship
            ->get();

        return $users->map(function ($item, $index) {
            $userDetails = $item->userDetails;
            //for get days today()- floor_hit_date
            $floorHitDate = $userDetails->floor_hit_date;
            $days = null;
            if ($floorHitDate && $floorHitDate !== '-') {
                $today = \Carbon\Carbon::now();
                $floorHit = \Carbon\Carbon::createFromFormat('Y-m-d', $floorHitDate);
                $daysDiff = $today->diffInDays($floorHit);
                $days = $daysDiff;
            }
            $bucket = $this->getBucket($days);
            // Determine the value for 'Floor Hit date' based on final_certification_status
            $floorHitDateValue = '';
            if ($userDetails->final_certification_status === 'Certified') {
                $floorHitDateValue = ($userDetails->floor_hit_date !== '-' && !empty($userDetails->floor_hit_date))
                    ? \Carbon\Carbon::createFromFormat('Y-m-d', $userDetails->floor_hit_date)->format('d-M-y')
                    : '-';
            }
            $daysColumn = '';
            $bucketColumn = '';

            // Check if final_certification_status is 'Certified', then populate Days and Bucket columns
            if ($userDetails->final_certification_status === 'Certified') {
                $daysColumn = $days;
                $bucketColumn = $bucket;
            }


            return ([
                'SL No.' => $index + 1,
                'OLMS ID' => $item['olms_id'],
                'Center' => $item['circle'],
                'Location' => $item['location'],
                'First Name' => $item['first_name'],
                'Middle Name' => $item['middle_name'],
                'Last Name' => $item['last_name'],
                'Mobile Number' => $item['mobile_number'],
                'DOB' => \Carbon\Carbon::createFromFormat('Y-m-d', $item['date_of_birth'])->format('d-M-y'),
                'DOJ' => \Carbon\Carbon::createFromFormat('Y-m-d', $item['date_of_joining'])->format('d-M-y'),
                'Email' => $item['email'],
                'Designation' => $item['designation'],
                'Gender' => $item['gender'],
                'LOB' => $item['lob'],
                'Ext. QA' => $item['ext_qa'],
                'Ext. QA OLMS' => $item['ext_qa_olms'],
                'CRM ID' => $item['crm_id'],
                'QMS ID' => $item['qms_id'],
                'LMS access' => $item['lms_access'],
                'Trainer Name' => $item['trainer_name'],
                'Trainer OLMS' => $item['trainer_olms'],
                'Skill Set 1' => $userDetails->skill_set_1 ?? '',
                'Skill Set 2' => $userDetails->skill_set_2 ?? '',
                'Skill Set 3' => $userDetails->skill_set_3 ?? '',
                'Int. QA' => $userDetails->internal_qa ?? '',
                'Int. QA OLMS' => $userDetails->internal_qa_olms ?? '',
                'Supervisor Name' => $userDetails->supervisor_name ?? '',
                'Supervisor OLMS' => $userDetails->supervisor_olms ?? '',
                'Business Manager (Airtel)' => $userDetails->business_manager_airtel ?? '',
                'Batch Code (Alfa-numeric)' => $userDetails->batch_code ?? '',
                'Certification date' => ($userDetails->certification_date !== '-' && !empty($userDetails->certification_date)) ? \Carbon\Carbon::createFromFormat('Y-m-d', $userDetails->certification_date)->format('d-M-y') : ($userDetails->certification_date === '-' ? '-' : null),
                'Final Certification score' => $userDetails->final_certification_score . '%' ?? '',
                'Final Certification Status' => $userDetails->final_certification_status ?? '',
                'Floor Hit date' => $floorHitDateValue,
                'Days' => $daysColumn,
                'Bucket' => $bucketColumn,
            ]
            );
        });
    }


    public function headings(): array
    {
        return [
            'SL No.',
            'OLMS ID',
            'Center',
            'Location',
            'First Name',
            'Middle Name',
            'Last Name',
            'Mobile Number',
            'DOB (DD-MMM-YY)',
            'DOJ (DD-MMM-YY)',
            'Email',
            'Designation',
            'Gender',
            'LOB',
            // 'Avaya ID PBX ID',
            'Ext. QA',
            'Ext. QA OLMS',
            'CRM ID',
            'QMS ID',
            'LMS access',
            'Trainer Name',
            'Trainer OLMS',

            'Skill Set 1',
            'Skill Set 2',
            'Skill Set 3',
            'Int. QA',
            'Int. QA OLMS',
            'Supervisor Name',
            'Supervisor OLMS',
            'Business Manager (Airtel)',
            'Batch Code (Alfa-numeric)',
            'Certification date',
            'Final Certification score',
            'Final Certification Status',
            'Floor Hit date',
            'Days',
            'Bucket',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set font size for the entire sheet
        $sheet->getStyle('A1:AK1')->getFont()->setSize(12);

        // Apply bold style to headings row
        $sheet->getStyle('A1:AK1')->getFont()->setBold(true);

        // Set row height for headings row
        $sheet->getRowDimension(1)->setRowHeight(30); // Adjust the value as needed

        // Set fill color for header row
        $sheet->getStyle('A1:AK1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

        $statusColumn = 'o'; // Assuming 'Status' field is in the 7th column (column G)
        $statusColumn = 'P'; // Assuming 'Status' field is in the 7th column (column G)

        // Get the last row in the sheet
        $lastRow = $sheet->getHighestRow();

        // Define the status-to-color mapping
        $statusColors = [
            'Activated' => Color::COLOR_GREEN,
            'Deactivated' => Color::COLOR_RED,
        ];

        // Set the fill color for each status value
        for ($row = 2; $row <= $lastRow; $row++) {
            $statusCellValue = $sheet->getCell($statusColumn . $row)->getValue();
            $statusColor = $statusColors[$statusCellValue] ?? Color::COLOR_WHITE;

            $sheet->getStyle($statusColumn . $row)
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB($statusColor);
        }
    }

    private function getBucket($days)
    {
        if ($days <= 30) {
            return '1-30 Days';
        } elseif ($days <= 90) {
            return '31-90 Days';
        } elseif ($days <= 180) {
            return '91-180 Days';
        } else {
            return '>180 Days';
        }
    }


    // public function styles(Worksheet $sheet)
    // {
    //     $sheet->getStyle('A1:AK1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

    //     $statusColumn = 'o'; // Assuming 'Status' field is in the 7th column (column G)
    //     $statusColumn = 'P'; // Assuming 'Status' field is in the 7th column (column G)

    //     // Get the last row in the sheet
    //     $lastRow = $sheet->getHighestRow();

    //     // Define the status-to-color mapping
    //     $statusColors = [
    //         'Activated' => Color::COLOR_GREEN,
    //         'Deactivated' => Color::COLOR_RED,

    //     ];
    //     // $statusColors = [
    //     //     'Certified' => Color::COLOR_GREEN,
    //     //     'Not Certified' => Color::COLOR_RED,

    //     // ];

    //     // Set the fill color for each status value
    //     for ($row = 2; $row <= $lastRow; $row++) {
    //         $statusCellValue = $sheet->getCell($statusColumn . $row)->getValue();
    //         $statusColor = $statusColors[$statusCellValue] ?? Color::COLOR_WHITE;

    //         $sheet->getStyle($statusColumn . $row)
    //             ->getFill()
    //             ->setFillType(Fill::FILL_SOLID)
    //             ->getStartColor()
    //             ->setARGB($statusColor);
    //     }
    // }
}
