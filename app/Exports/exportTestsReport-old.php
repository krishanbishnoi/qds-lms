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

class exportTestsReport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $participants;
    protected $questions;
    protected $answers;
    protected $testResults;

    public function __construct($participants, $questions, $answers, $testResults)
    {
        $this->participants = $participants;
        $this->questions = $questions;
        $this->answers = $answers;
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

    public function headings(): array
    {
        $headings = ['SN', 'User Name', 'Employee Id', 'Email'];

        // Add question headings
        foreach ($this->questions as $question) {
            $headings[] = $question->question;
        }

        $headings[] = 'Total Marks';
        $headings[] = 'Obtain Marks';
        $headings[] = 'Percentage';
        $headings[] = 'Result';

        return $headings;
    }

    public function map($row): array
    {
        foreach ($this->testResults as $user) {

            $data = [
                $user->user_details['id'] ?? '',
                $user->user_details['fullname'] ?? '',
                $user->user_details['employee_id'] ?? '',
                $user->user_details['email'] ?? '',
            ];




            foreach ($this->questions as $question) {
                $userAnswers = $this->getUserAnswer($user->user_details['id'], $question->id);
                $optionsWithIndicators = [];
                foreach ($userAnswers as $userAnswer) {
                    $option = isset($userAnswer['option']) ? $userAnswer['option'] : '';
                    $isCorrect = isset($userAnswer['is_correct']) ? $userAnswer['is_correct'] : false;
                    $optionsWithIndicators[] = $option . ' [' . ($isCorrect ? '1' : '0') . '/1]';
                }

                $data[] = implode(", ", $optionsWithIndicators);
            }

            $result = $this->testResults->where('user_id', $user->user_details['id'] ?? null)->first();
            $data[] = $result['total_marks'] ?? '';
            $data[] = $result['obtain_marks'] ?? '';
            $data[] = $result['percentage'] . '%' ?? '';
            $data[] = $result['result'] ?? '';

            return $data;
        }
    }


    protected function getUserAnswer($userId, $questionId)
    {
        $userAnswer = $this->answers
            ->where('user_id', $userId)
            ->where('question_id', $questionId)
            ->first();

        if ($userAnswer) {
            $answerIds = explode(',', $userAnswer['answer_id']);
            $validAnswerIds = explode(',', $userAnswer['valid_answer']);

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

        return [];
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'FFFF00'], // Yellow color
            ],
        ]);

        foreach ($sheet->getRowIterator() as $row) {
            $resultColumn = $sheet->getCell('R' . $row->getRowIndex())->getValue();

            if ($resultColumn == 'Failed') {
                $sheet->getStyle('R' . $row->getRowIndex())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'FF0000'], // Red color
                    ],
                ]);
            } elseif ($resultColumn == 'Passed') {
                $sheet->getStyle('R' . $row->getRowIndex())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => '00FF00'], // Green color
                    ],
                ]);
            }
        }
    }
}
