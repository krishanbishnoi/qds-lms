<?php

namespace App\Exports;

use App\Model\User;
use App\Model\Test;
use App\Model\UserAssignedTestQuestion;
use App\Model\Answer;
use App\Model\QuestionAttribute;
use App\Model\TestResult;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Color;
use DB;

class TestReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
        $data = collect();

        foreach ($this->participants as $participant) {
            $row = $this->map($participant);
            $data->push($row);
        }
        return $data;
    }
    public function map($row): array
    {
        $data = [
            ++$this->snCounter,
            $row->email ?? '',
        ];

        foreach ($this->questions as $question) {
            $assignedQuestions = UserAssignedTestQuestion::where('test_id', $question->test_id)
                ->where('trainee_id', $row->id)
                ->where('questions_id', $question->id)->first();

            if ($assignedQuestions != null) {
                $userAnswers = $this->getUserAnswer($row->id, $question->id, $question->question_type);
                $optionsWithIndicators = [];
                foreach ($userAnswers as $userAnswer) {
                    if (isset($userAnswer['free_text_answer'])) {
                        $optionsWithIndicators[] = $userAnswer['free_text_answer'];
                    } else {
                        $option = isset($userAnswer['option']) ? $userAnswer['option'] : '';
                        $isCorrect = isset($userAnswer['is_correct']) ? $userAnswer['is_correct'] : false;
                        $optionsWithIndicators[] = $this->formatOptionWithIndicator($option, $isCorrect);
                    }
                }

                $data[] = implode(", ", $optionsWithIndicators);
            } else {
                $data[] = '';
            }
        }

        $result = $this->testResults->where('user_id', $row->id)->first();

        $data[] = $result['total_marks'] ?? '';
        $data[] = $result['obtain_marks'] ?? '';
        $data[] = $result['percentage']  ?? '';
        $data[] = $result['result'] ?? '';

        return $data;
    }

    protected function getUserAnswer($userId, $questionId, $questionType)
    {
        $userAnswer = DB::table('answers')
            ->where('user_id', $userId)
            ->where('question_id', $questionId)
            ->first();
        if ($userAnswer) {
            if ($questionType == 'FreeText') {
                return [
                    [
                        'free_text_answer' => $userAnswer->free_text_answer
                    ]
                ];
            } else {
                $answerIds = explode(',', $userAnswer->answer_id);
                $validAnswerIds = explode(',', $userAnswer->valid_answer);

                $options = [];
                foreach ($answerIds as $answerId) {

                    $option = QuestionAttribute::where('question_id', $questionId)
                        ->where('id', $answerId)
                        ->value('option');

                    if ($option !== null) {
                        $options[] = [
                            'option' => $option,
                            'is_correct' => in_array($answerId, $validAnswerIds),
                        ];
                    }
                }

                return $options;
            }
        }

        return [];
    }

    protected function formatOptionWithIndicator($option, $isCorrect)
    {
        $text = $isCorrect ? 'True' : 'False';
        $color = $isCorrect ? '0000FF' : 'FF0000'; // Blue for True, Red for False

        return "<span style='color:#{$color}'>{$option} ({$text})</span>";
    }

    public function headings(): array
    {
        $headings = ['SN', 'Email'];

        foreach ($this->questions as $question) {
            $headings[] = $question->question;
        }

        $headings[] = 'Total Marks';
        $headings[] = 'Obtain Marks';
        $headings[] = 'Percentage';
        $headings[] = 'Result';

        return $headings;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'FFFF00'],
            ],
        ]);
    }
}
