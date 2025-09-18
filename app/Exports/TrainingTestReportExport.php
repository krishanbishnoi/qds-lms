<?php
namespace App\Exports;

use App\Model\QuestionAttribute;
use App\Model\TrainingTestResult;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TrainingTestReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $participants;
    protected $questions;
    protected $testResults;
    private $snCounter = 0;

    public function __construct($participants, $questions, $testResults)
    {
        $this->participants = $participants;
        $this->questions    = $questions;
        $this->testResults  = $testResults;
    }

    public function collection()
    {
        $data                  = [];
        $previousParticipantId = null;

        foreach ($this->participants as $participant) {
            $attempts = TrainingTestResult::where('test_id', $this->questions->first()->test_id)
                ->where('user_id', $participant->id)
                ->get();
// dd($attempts);
            foreach ($attempts as $attempt) {
                // Set trainee details only if this is a new trainee
                $row = [];
                if ($previousParticipantId !== $participant->id) {
                    $row = [
                        ++$this->snCounter,
                        $participant->olms_id ?? '',
                        $participant->fullname ?? '',
                        $attempt->attempt_number ?? '',
                        // $attempts->total_attemted_questions ?? '',
                    ];
                    $previousParticipantId = $participant->id;
                } else {
                    $row = [
                        '',
                        '',
                        '',
                        $attempt->attempt_number ?? '',
                        // $attempts->total_attemted_questions ?? '',
                    ];
                }

                foreach ($this->questions as $question) {
                    $userAnswers = $this->getUserAnswer($participant->id, $question->id, $question->question_type, $attempt->attempt_number);
                    $options = [];
                    foreach ($userAnswers as $userAnswer) {
                        if (isset($userAnswer['free_text_answer'])) {
                            $options[] = $userAnswer['free_text_answer'];
                        } else {
                            $options[] = isset($userAnswer['option']) ? $userAnswer['option'] : '';
                        }
                    }
                    $row[] = implode(", ", $options);
                }

                // Append total marks, obtained marks, percentage, and result
                $row[] = $attempt->total_marks ?? '';
                $row[] = $attempt->obtain_marks ?? '';
                $row[] = $attempt->percentage ?? '';
                $row[] = $attempt->result ?? '';

                $data[] = $row;
            }
        }

        return collect($data);
    }
    public function headings(): array
    {
        $questionHeadings = [];
        foreach ($this->questions as $question) {
            $questionHeadings[] = $question->question;
        }

        return array_merge(
            ['SN', 'OLMS Id', 'Full name', 'Attempt no.'],
            $questionHeadings,
            ['Total marks', 'Obtain marks', 'Percentage', 'Result Status']
        );
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
                $options   = [];
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
                'color'    => ['argb' => 'FFFF00'],
            ],
        ]);

        return [];
    }
}
