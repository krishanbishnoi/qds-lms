<?php

namespace App\Exports;

use App\Http\Controllers\BaseController;
use App\Model\Course;
use App\Model\Test;
use App\Model\TrainingTestResult;
use App\Model\UserAssignedTrainingProgress;
use App\Models\TraineeAssignedTrainingDocument;
use App\Models\TrainingDocument;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TrainingDocumentResultsExport extends BaseController implements FromCollection, ShouldAutoSize, WithHeadings, WithStyles
{
    protected $trainingTitle;
    protected $userDetails;
    protected $courses;

    public function __construct($trainingTitle, $userDetails, $courses)
    {
        $this->trainingTitle = $trainingTitle;
        $this->userDetails   = $userDetails;
        $this->courses       = $courses;
    }

    public function collection()
    {
        $data = [];

        foreach ($this->userDetails as $result) {
            $user = User::find($result['trainee_id']);

            if ($user) {
                $row = [
                    $this->trainingTitle,
                    $user->olms_id,
                    $user->fullname,
                ];
                $cumulativeTotal = 0;
                $cumulativeRead = 0;
                // For each course, get the document counts
                foreach ($this->courses as $course) {
                    $totalDocuments = TrainingDocument::where('course_id', $course->id)
                        ->count();


                    $readDocuments = TraineeAssignedTrainingDocument::where('training_id', $result['training_id'])
                        ->where('user_id', $user->id)
                        ->where('course_id', $course->id)
                        ->where('status', 1)
                        ->count();

                    $row[] = "Total Documents: $totalDocuments\nRead Documents: $readDocuments";

                    $cumulativeTotal += $totalDocuments;
                    $cumulativeRead += $readDocuments;
                }
                $status = ($cumulativeTotal === $cumulativeRead && $cumulativeTotal > 0) ? 'Completed' : 'Pending';

                $row[] = $cumulativeTotal;
                $row[] = $cumulativeRead;
                $row[] = $status;

                $data[] = $row;
            }
        }

        return collect($data);
    }

    public function headings(): array
    {
        $headings = [
            'Training Title',
            'Employee OLMS ID',
            'Employee Name',
        ];

        foreach ($this->courses as $course) {
            $headings[] = ' Course - ' . $course->title . ' - Document Progress';
        }
        $headings[] = 'Total Documents Count';
        $headings[] = 'Read Documents Count';
        $headings[] = 'Status';

        return $headings;
    }

    public function styles(Worksheet $sheet)
    {
        // Bold entire first row
        $sheet->getStyle('1')->getFont()->setSize(13)->setBold(true);

        // Yellow fill for header
        $sheet->getStyle('A1:Z1')->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFF00');

        // Enable wrap text for all columns from D onward (where courses start)
        $columnCount = 3 + count($this->courses); // 3 = title, olms_id, name

        for ($i = 4; $i <= $columnCount + 2; $i++) {
            $columnLetter = Coordinate::stringFromColumnIndex($i);
            $sheet->getStyle($columnLetter)->getAlignment()->setWrapText(true);
        }
    }
}
