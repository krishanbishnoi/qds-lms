<?php

namespace App\Exports;

use App\Http\Controllers\BaseController;
use App\Model\User;
use App\Model\UserAssignedTrainingProgress;
use App\Models\Course;
use App\Models\Test;
use App\Models\TraineeAssignedTrainingDocument;
use App\Models\TrainingDocument;
use App\Models\TrainingTestResult;
use App\Models\User as ModelsUser;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TrainingWithTestResultsExport extends BaseController implements FromCollection, ShouldAutoSize, WithHeadings, WithStyles
{
    protected $trainingTitle;
    protected $userDetails;

    public function __construct($trainingTitle, $userDetails)
    {
        $this->trainingTitle = $trainingTitle;
        $this->userDetails   = $userDetails; // This is now an array of user details
    }
    public function collection()
    {
        $data        = [];
        $summaryRows = [];
        $currentRow  = 2;
        $processedUsers = []; // To track users already processed
        $processedTests = []; // Track shown test-training pairs

        foreach ($this->userDetails as $result) {
            if (isset($processedUsers[$result['user_id']])) {
                continue; // Skip processing if user is already handled
            }

            $user = ModelsUser::find($result['user_id']);

            if ($user) {
                $trainingTestResults = TrainingTestResult::where('user_id', $user->id)
                    ->where('training_id', $result['training_id'])
                    ->get();

                $totalMinimumMark = 0;
                $totalObtainMarks = 0;
                $totalTestCount   = count($trainingTestResults);

                foreach ($trainingTestResults as $trainingTestResult) {
                    $test       = Test::find($trainingTestResult->test_id);
                    $courseName = $test ? Course::find($trainingTestResult->course_id)->title ?? '' : '';

                    if ($test) {
                        $totalMinimumMark += $test->minimum_marks;
                        $totalObtainMarks += $trainingTestResult->percentage;

                        $testKey = $test->id . '_' . $result['training_id'];
                        $attemptedCount = '';
                        
                        if (!isset($processedTests[$testKey])) {
                            $attemptedCount = $this->getTestAttemptSummary($test->id, $result['training_id']);
                            $processedTests[$testKey] = true;
                        }
                        
                        $data[] = [
                            $this->trainingTitle,
                            $user->olms_id,
                            $user->fullname,
                            $courseName,
                            $test->title,
                            number_format($test->minimum_marks, 2),
                            number_format($trainingTestResult->percentage, 2),
                            '',
                            $attemptedCount, // only once per test/training
                        ];
                        
                        $currentRow++;
                    }
                }

                if ($totalTestCount > 0) {
                    $averageMinimumMark = $totalMinimumMark / $totalTestCount;
                    $averageObtainMarks = $totalObtainMarks / $totalTestCount;
                    $status             = ($averageObtainMarks >= $averageMinimumMark) ? 'Passed' : 'Failed';
                
                    $totalDocuments = TrainingDocument::where('training_id', $result['training_id'])
                        ->count();
                // dd($user->id,$totalDocuments);
                    $readDocuments = TraineeAssignedTrainingDocument::where('training_id', $result['training_id'])
                        ->where('user_id', $user->id)
                        ->where('status', 1)
                        ->count();
                
                    $data[] = [
                        '',
                        $user->olms_id,
                        $user->fullname,
                        '',
                        'Overall Summary',
                        number_format($averageMinimumMark, 2),
                        number_format($averageObtainMarks, 2),
                        $status,
                        '',
                        $totalDocuments,
                        $readDocuments,
                    ];
                    $summaryRows[] = $currentRow;
                    $currentRow++;
                }

                // Mark user as processed
                $processedUsers[$result['user_id']] = true;
            }
        }

        session(['summaryRows' => $summaryRows]);
        return collect($data);
    }
    private function getTestAttemptSummary($testId, $trainingId)
    {
        $results = TrainingTestResult::where('test_id', $testId)
            ->where('training_id', $trainingId)
            ->get();

        $total = $results->count();
        $passed = 0;
        $failed = 0;

        foreach ($results as $result) {
            $test = Test::find($result->test_id);
            if ($test && $result->percentage >= $test->minimum_marks) {
                $passed++;
            } else {
                $failed++;
            }
        }

        return "{$total}";
        // return "{$total} / {$passed} / {$failed}";
    }
    public function headings(): array
    {
        return [
            'Training Title',
            'Employee ID',
            'Employee Name',
            'Course Name',
            'Test Name',
            'Test Minimum Passing Percentage',
            'Test Obtained Percentage',
            'Overall Test Result (Pass/Fail)',
            'Total Test Attempted Users',
            'Total Documents Count',
            'Read Documents Count',
        ];
    }
    

    public function styles(Worksheet $sheet)
    {
        // Header Styling
        $sheet->getStyle('1')->getFont()->setSize(13)->setBold(true);
        $sheet->getStyle('A1:K1')->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFF00');

        // $statusColumn = 'H';
        // $lastRow      = $sheet->getHighestRow();

        // $statusColors = [
        //     'Passed' => Color::COLOR_GREEN,
        //     'Failed' => Color::COLOR_RED,
        // ];

        // for ($row = 2; $row <= $lastRow; $row++) {
        //     $statusCellValue = $sheet->getCell($statusColumn . $row)->getValue();
        //     if (isset($statusColors[$statusCellValue])) {
        //         $sheet->getStyle($statusColumn . $row)
        //             ->getFill()
        //             ->setFillType(Fill::FILL_SOLID)
        //             ->getStartColor()
        //             ->setARGB($statusColors[$statusCellValue]);
        //     }
        // }

        // Darken "Overall Summary" rows
        $summaryRows = session('summaryRows', []);
        foreach ($summaryRows as $row) {
            $sheet->getStyle("A{$row}:K{$row}")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('D9D9D9'); // Dark Gray Background
        }
    }
}
