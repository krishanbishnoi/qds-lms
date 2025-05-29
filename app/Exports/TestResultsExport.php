<?php

namespace App\Exports;

use App\Models\User;
use App\Models\TestAttendee;
use App\Models\UserDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Http\Controllers\BaseController;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TestResultsExport extends BaseController implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $test;
    protected $testResults;

    public function __construct($test, $testResults)
    {
        $this->test = $test;
        $this->testResults = $testResults;
    }

    public function collection()
    {
        $data = [];
        $counter = 1;

        foreach ($this->testResults as $result) {
            $user = User::find($result->user_id);
            if (!$user) {

                $testAttendee = TestAttendee::where('link_id', $result->user_id)->first();

                if ($testAttendee) {
                    $data[] = [
                        $counter,
                        $this->test->title,
                        null,
                        $testAttendee->email,
                        $result->total_questions,
                        $result->total_attemted_questions,
                        $result->total_marks,
                        $result->obtain_marks,
                        $result->percentage,
                        $result->result
                    ];
                }
            } else {

                $data[] = [
                    $counter,
                    $this->test->title,
                    $user->olms_id,
                    $user->email,
                    $result->total_questions,
                    $result->total_attemted_questions,
                    $result->total_marks,
                    $result->obtain_marks,
                    $result->percentage,
                    $result->result
                ];
            }
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'SL. No.',
            'Test Name',
            'Employee Id',
            'Trainee Email',
            'Total Questions',
            'Total Attempted Questions',
            'Total Marks',
            'Obtained Marks',
            'Percentage',
            'Result',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1')->getFont()->setSize(13)->setBold(true);
        $sheet->getStyle('A1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

        $statusColumn = 'J';
        $lastRow = $sheet->getHighestRow();

        $statusColors = [
            'Passed' => Color::COLOR_GREEN,
            'Failed' => Color::COLOR_RED,

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
}
