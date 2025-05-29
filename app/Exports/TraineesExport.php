<?php

namespace App\Exports;

use App\Models\User;
use App\Models\UserDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Http\Controllers\BaseController;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TraineesExport extends BaseController implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{

    private $filteredResult;

    public function __construct($filteredResult)
    {
        $this->filteredResult = $filteredResult;
    }

    // public function collection()
    // {
    //     return $this->filteredResult;
    // }

    public function collection()
    {
        return $this->filteredResult->map(function ($item, $index) {
            $user = User::findOrFail($item['id']); // Fetch the user based on user_id

            // $userDetails = $user->userDetails;
            // $floorHitDate = $userDetails->floor_hit_date;
            // $days = null;
            // if ($floorHitDate && $floorHitDate !== '-') {
            //     $today = \Carbon\Carbon::now();
            //     $floorHit = \Carbon\Carbon::createFromFormat('Y-m-d', $floorHitDate);
            //     $daysDiff = $today->diffInDays($floorHit);
            //     $days = $daysDiff;
            // }
            // $bucket = $this->getBucket($days);
            // // Determine the value for 'Floor Hit date' based on final_certification_status
            // $floorHitDateValue = '';
            // if ($userDetails->final_certification_status === 'Certified') {
            //     $floorHitDateValue = ($userDetails->floor_hit_date !== '-' && !empty($userDetails->floor_hit_date))
            //         ? \Carbon\Carbon::createFromFormat('Y-m-d', $userDetails->floor_hit_date)->format('d-M-y')
            //         : '-';
            // }
            // $daysColumn = '';
            // $bucketColumn = '';

            // // Check if final_certification_status is 'Certified', then populate Days and Bucket columns
            // if ($userDetails->final_certification_status === 'Certified') {
            //     $daysColumn = $days;
            //     $bucketColumn = $bucket;
            // }
            // dd($item);
            return [
                'SL No.' => $index + 1,
                'Employee ID' => $item['olms_id'],
                'First Name' => $item['first_name'],
                'Middle Name' => $item['middle_name'],
                'Last Name' => $item['last_name'],
                'Mobile Number' => $item['mobile_number'],
                'Email' => $item['email'],
                'LOB' => $item['lob'],
                'Region' => $item['region'],
                'Circle' => $item['circle'],
                'Designation' => $item['designation'],
                'Gender' => $item['gender'],
            ];
        });
    }

    public function headings(): array
    {
        return [
            'SL No.',
            'Employee ID',
            'First Name',
            'Middle Name',
            'Last Name',
            'Mobile Number',
            'Email',
            'LOB',
            'Region',
            'Circle',
            'Designation',
            'Gender',

        ];
    }

    public function styles(Worksheet $sheet)
    {
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
        // $statusColors = [
        //     'Certified' => Color::COLOR_GREEN,
        //     'Not Certified' => Color::COLOR_RED,

        // ];

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
}
