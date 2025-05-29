<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Test;
use App\Models\UserAssignedTestQuestion;
use App\Models\Answer;
use App\Models\QuestionAttribute;
use App\Models\TestResult;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class exportTestsReport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return $this->data[0];
    }

    // public function styles( Worksheet $sheet ) {
    //     $sheet->getStyle( 'A1:' . $sheet->getHighestColumn() . '1' )->applyFromArray( [
    //         'fill' => [
    //             'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    //             'color' => [ 'rgb' => 'FFFF00' ], // Yellow color
    //         ],
    //     ] );

    //     foreach ( $sheet->getRowIterator() as $row ) {
    //         $resultColumn = $sheet->getCell( 'R' . $row->getRowIndex() )->getValue();

    //         if ( $resultColumn == 'Failed' ) {
    //             $sheet->getStyle( 'R' . $row->getRowIndex() )->applyFromArray( [
    //                 'fill' => [
    //                     'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    //                     'color' => [ 'rgb' => 'FF0000' ], // Red color
    //                 ],
    //             ] );
    //         } elseif ( $resultColumn == 'Passed' ) {
    //             $sheet->getStyle( 'R' . $row->getRowIndex() )->applyFromArray( [
    //                 'fill' => [
    //                     'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    //                     'color' => [ 'rgb' => '00FF00' ], // Green color
    //                 ],
    //             ] );
    //         }
    //     }
    // }
}
