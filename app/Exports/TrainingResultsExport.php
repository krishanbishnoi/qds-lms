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
use Illuminate\Support\Collection;

class TrainingResultsExport extends BaseController implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{

    protected $trainingTitle;
    protected $userDetails;
    protected $status;
    protected $averageMinimumMark;
    protected $averageObtainMarks;

    public function __construct($trainingTitle, $userDetails, $status, $averageMinimumMark, $averageObtainMarks)
    {
        $this->trainingTitle = $trainingTitle;
        $this->userDetails = $userDetails;
        $this->status = $status;
        $this->averageMinimumMark = $averageMinimumMark;
        $this->averageObtainMarks = $averageObtainMarks;
    }

    public function collection()
    {
        // dd($this->userDetails);
        $data = [];
        $counter = 1; // Initialize the counter
        // dd($this->userDetails);
        // foreach ($this->userDetails as $result) {
        $user = User::where('id', $this->userDetails->user_id)->first();
        // dd($user->olms_id);
        if ($user) {
            $data[] = [
                $counter,
                $this->trainingTitle,
                $user->olms_id,
                $user->fullname,
                $this->averageMinimumMark,
                $this->averageObtainMarks,
                $this->status
            ];
            $counter++;
        }
        // }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'SL. No.',
            'Training Name',
            'OLMS Id',
            'Trainee Name',
            'Average Minimum Mark',
            'Average Obtain Marks',
            'Result',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1')->getFont()->setSize(13)->setBold(true);
        $sheet->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

        $statusColumn = 'G';
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
