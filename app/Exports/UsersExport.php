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

class UsersExport extends BaseController implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{

    private $filteredResult;

    public function __construct($filteredResult)
    {
        $this->filteredResult = $filteredResult;
    }

    public function collection()
    {
        return $this->filteredResult;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Employee ID',
            'First name',
            'Last name',
            'Email',
            'Mobile Number',
            'Status',
            'Created At',

        ];
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF00FF00');

        $statusColumn = 'G';
        // Get the last row in the sheet
        $lastRow = $sheet->getHighestRow();

        $statusColors = [
            'Activated' => Color::COLOR_GREEN,
            'Deactivated' => Color::COLOR_RED,

        ];

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
