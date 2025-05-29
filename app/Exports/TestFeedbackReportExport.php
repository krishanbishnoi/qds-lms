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
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Color;
use DB;

class TestFeedbackReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
    // public function map($row): array
    // {
    //     $data = [
    //         ++$this->snCounter,
    //         // $row instanceof \App\Models\User ? $row->id : $row->link_id,
    //         $row->email ?? '',
    //     ];

    //     foreach ($this->questions as $question) {
    //         $assignedQuestions = UserAssignedTestQuestion::where('test_id', $question->test_id)
    //             ->where('trainee_id', $row instanceof \App\Models\User ? $row->id : $row->link_id)
    //             ->where('questions_id', $question->id)->first();

    //         if ($assignedQuestions != null) {
    //             $userAnswers = $this->getUserAnswer($row instanceof \App\Models\User ? $row->id : $row->link_id, $question->id);
    //             $optionsWithIndicators = [];
    //             foreach ($userAnswers as $userAnswer) {
    //                 $option = isset($userAnswer['option']) ? $userAnswer['option'] : '';
    //                 $isCorrect = isset($userAnswer['is_correct']) ? $userAnswer['is_correct'] : false;
    //                 $optionsWithIndicators[] = $option . ' [' . ($isCorrect ? '1' : '0') . '/1]';
    //             }

    //             $data[] = implode(", ", $optionsWithIndicators);
    //         } else {
    //             $data[] = '';
    //         }
    //     }

    //     $result = $this->testResults->where('user_id', $row instanceof \App\Models\User ? $row->id : $row->link_id)->first();


    //     $data[] = $result['total_marks'] ?? '';
    //     $data[] = $result['obtain_marks'] ?? '';
    //     $data[] = $result['percentage']  ?? '';
    //     $data[] = $result['result'] ?? '';

    //     return $data;
    // }

    public function map($row): array
    {
        $data = [
            ++$this->snCounter,
            $row->email ?? '',
        ];

        foreach ($this->questions as $question) {
            if ($question->question_type === 'FreeText') {
                $userAnswer = $this->getFreeTextAnswer($row instanceof \App\Models\User ? $row->id : $row->link_id, $question->id);
                $data[] = $userAnswer;
            } else {
                $assignedQuestions = UserAssignedTestQuestion::where('test_id', $question->test_id)
                    ->where('trainee_id', $row instanceof \App\Models\User ? $row->id : $row->link_id)
                    ->where('questions_id', $question->id)->first();

                if ($assignedQuestions != null) {
                    $userAnswers = $this->getUserAnswer($row instanceof \App\Models\User ? $row->id : $row->link_id, $question->id);
                    $optionsGiven = [];
                    foreach ($userAnswers as $userAnswer) {
                        $option = isset($userAnswer['option']) ? $userAnswer['option'] : '';
                        $optionsGiven[] = $option;
                    }

                    $data[] = implode(", ", $optionsGiven);
                } else {
                    $data[] = '';
                }
            }
        }

        $result = $this->testResults->where('user_id', $row instanceof \App\Models\User ? $row->id : $row->link_id)->first();

        // $data[] = $result['total_marks'] ?? '';
        // $data[] = $result['obtain_marks'] ?? '';
        // $data[] = $result['percentage']  ?? '';
        // $data[] = $result['result'] ?? '';

        return $data;
    }


    protected function getUserAnswer($userId, $questionId)
    {

        $userAnswer = DB::table('answers')
            ->where('user_id', $userId)
            ->where('question_id', $questionId)
            ->first();
        if ($userAnswer) {
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

        return [];
    }
    protected function getFreeTextAnswer($userId, $questionId)
    {
        $userAnswer = DB::table('answers')
            ->where('user_id', $userId)
            ->where('question_id', $questionId)
            ->first();

        return $userAnswer ? $userAnswer->free_text_answer : '';
    }
    public function headings(): array
    {
        $headings = ['SN', 'Email'];

        // Add question headings
        foreach ($this->questions as $question) {
            $headings[] = $question->question;
        }

        // $headings[] = 'Total Marks';
        // $headings[] = 'Obtain Marks';
        // $headings[] = 'Percentage';
        // $headings[] = 'Result';

        return $headings;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'FFFF00'], // Yellow color
            ],
        ]);
    }
}
