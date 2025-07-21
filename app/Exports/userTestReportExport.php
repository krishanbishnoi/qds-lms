<?php

namespace App\Exports;

use App\Model\UserAssignedTestQuestion;
use App\Models\QuestionAttribute;
use App\Models\TestResult;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class userTestReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $participants;
    protected $questions;
    protected $testResults;
    private $snCounter = 0;

    public function __construct($participants, $questions, $testResults)
    {
        $this->participants = $participants;
        $this->questions = $questions;
        $this->testResults = $testResults;
    }

    public function collection()
    {
        $data = [];
        $previousParticipantId = null;
        $highestAttempt = $this->getHighestAttemptNumber();
        $this->snCounter = 0;

        foreach ($this->participants as $participant) {
            // Get all attempts ordered by attempt number
            $attempts = TestResult::where('test_id', $this->questions->first()->test_id)
                ->where('user_id', $participant->id)
                ->orderBy('attempt_number')
                ->get();

            // Skip participants with no attempts
            if ($attempts->isEmpty()) {
                continue;
            }

            $row = [];

            // Set participant details only for first row of each participant
            if ($previousParticipantId !== $participant->id) {
                $row = [
                    ++$this->snCounter,
                    $participant->olms_id ?? '',
                    $participant->fullname ?? '',
                    $participant->email ?? ''
                ];
                $previousParticipantId = $participant->id;
            } else {
                $row = ['', '', '']; // Empty SN, OLMS Id, Full name
            }

            // Check if participant passed in any attempt
            $hasPassed = $attempts->contains('result', 'Passed');
            $firstAttempt = $attempts->first();

            // Process each attempt
            foreach (range(1, $highestAttempt) as $index => $attemptNumber) {
                $attempt = $attempts->where('attempt_number', $attemptNumber)->first();

                // For first attempt (always shown)
                if ($attemptNumber === 1) {
                    $row = array_merge($row, [
                        '', // ' ' column
                        '1',
                        isset($firstAttempt->percentage) ? number_format($firstAttempt->percentage, 0) . '%' : '',
                        $firstAttempt->result === 'Passed' ? '1' : '0',
                        $firstAttempt->result === 'Failed' ? '1' : '0',
                        $firstAttempt->result === 'Passed' ? 'Pass' : 'Fail'
                    ]);

                    // If passed in first attempt, fill remaining with empty values
                    if ($firstAttempt->result === 'Passed') {
                        for ($j = 2; $j <= $highestAttempt; $j++) {
                            $row = array_merge($row, array_fill(0, 7, ''));
                        }
                        break;
                    }
                }
                // For subsequent attempts (only shown if not passed yet)
                elseif (!$hasPassed || $attemptNumber <= $attempts->count()) {
                    if ($attempt) {
                        $row = array_merge($row, [
                            '', // ' ' column
                            $index + 1,
                            isset($attempt->percentage) ? number_format($attempt->percentage, 0) . '%' : '',
                            $attempt->result === 'Passed' ? '1' : '0',
                            $attempt->result === 'Failed' ? '1' : '0',
                            $attempt->result === 'Passed' ? 'Pass' : 'Fail'
                        ]);
                    } else {
                        $row = array_merge($row, array_fill(0, 7, ''));
                    }
                } else {
                    $row = array_merge($row, array_fill(0, 7, ''));
                }
            }

            $data[] = $row;
        }

        return collect($data);
    }

    
    public function headings(): array
    {
        $fixedHeaders = ['SN', 'Employee Id', 'Full name','Email'];
        $attemptHeaders = [];

        $highestAttempt = $this->getHighestAttemptNumber();

        for ($i = 1; $i <= $highestAttempt; $i++) {


            // Add attempt-specific headers
            $attemptHeaders = array_merge(
                $attemptHeaders,
                [' ', "Attempted Number $i", "Score $i", 'Pass', 'Fail', 'Result']
            );
        }
   
        return array_merge($fixedHeaders, $attemptHeaders);
    }
    protected function getUserAnswer($userId, $questionId, $questionType, $attemptNumber)
    {
        $userAnswer = DB::table('answers')
            ->where('user_id', $userId)
            ->where('question_id', $questionId)
            ->where('attempt_number', $attemptNumber)
            ->first();

        if ($userAnswer) {
            if ($questionType == 'FreeText') {
                return [
                    [
                        'free_text_answer' => $userAnswer->free_text_answer,
                    ],
                ];
            } else {
                $answerIds = explode(',', $userAnswer->answer_id);
                $options = [];
                foreach ($answerIds as $answerId) {
                    $option = QuestionAttribute::where('question_id', $questionId)
                        ->where('id', $answerId)
                        ->value('option');

                    if ($option !== null) {
                        $options[] = [
                            'option' => $option,
                        ];
                    }
                }
                return $options;
            }
        }

        return [];
    }

    public function styles(Worksheet $sheet)
    {
        // Set first row to yellow
        $sheet->getStyle('A1:GZ1')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FFFF00'],
            ],
        ]);

        return [];
    }

    public function getHighestAttemptNumber(): int
    {
        return collect($this->testResults)
            ->pluck('attempt_number')
            ->flatten()
            ->max() ?? 0;
    }
}
