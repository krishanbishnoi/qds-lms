<?php

namespace App\Exports;

use App\Models\QuestionAttribute;
use App\Models\UserAssignedTestQuestion;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

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
    // new map function for get MCQ (Multichoice questions logic is not working properly, logic to be relooked)
    // public function map($row): array
    // {
    //     $data = [
    //         ++$this->snCounter,
    //         $row->email ?? '',
    //     ];

    //     foreach ($this->questions as $question) {
    //         $assignedQuestions = UserAssignedTestQuestion::where('test_id', $question->test_id)
    //             ->where('trainee_id', $row instanceof \App\Models\User ? $row->id : $row->link_id)
    //             ->where('questions_id', $question->id)->first();

    //         if ($assignedQuestions != null) {
    //             $userAnswers = $this->getUserAnswer($row instanceof \App\Models\User ? $row->id : $row->link_id, $question->id);

    //             $optionsWithIndicators = [];
    //             $totalObtainedMarks = 0;

    //             foreach ($userAnswers as $userAnswer) {
    //                 $option = $userAnswer['option'] ?? '';
    //                 $isCorrect = $userAnswer['is_correct'] ?? false;
    //                 $obtainedMarks = $userAnswer['obtained_marks'] ?? 0;
    //                 $totalObtainedMarks += $obtainedMarks;
    //                 $optionsWithIndicators[] = $option . ' [' . ($isCorrect ? '1' : '0') . '/1]';
    //             }

    //             $data[] = implode(", ", $optionsWithIndicators);
    //             $data[] = $totalObtainedMarks; // Add obtained marks to the export
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
        // dd($row);
        $data = [
            ++$this->snCounter,
            // $row instanceof \App\Models\User ? $row->id : $row->link_id,
            $row->email ?? '',
        ];
        $hasFreeTextQuestion = false; // Flag to check if there's a FreeText question

        // dd($this->questions);
        foreach ($this->questions as $question) {

            $assignedQuestions = UserAssignedTestQuestion::where('test_id', $question->test_id)
                ->where('trainee_id', $row instanceof \App\Models\User  ? $row->id : $row->link_id)
                ->where('questions_id', $question->id)->first();

            if ($question->question_type === 'FreeText') {
                $hasFreeTextQuestion = true; // Mark the flag

                $userAnswer = $this->getFreeTextAnswer($row instanceof \App\Models\User  ? $row->id : $row->link_id, $question->id);
                $data[] = $userAnswer;
            } else {
                if ($assignedQuestions != null) {
                    $userAnswers = $this->getUserAnswer($row instanceof \App\Models\User  ? $row->id : $row->link_id, $question->id);

                    $optionsWithIndicators = [];

                    foreach ($userAnswers as $userAnswer) {
                        $option = isset($userAnswer['option']) ? $userAnswer['option'] : '';
                        $isCorrect = isset($userAnswer['is_correct']) ? $userAnswer['is_correct'] : false;
                        $optionsWithIndicators[] = $option . ' [' . ($isCorrect ? '1' : '0') . '/1]';
                    }

                    $data[] = implode(", ", $optionsWithIndicators);
                } else {
                    $data[] = '';
                }
            }
        }

        // Include result fields only if there's no FreeText question
        if (!$hasFreeTextQuestion) {
            $result = $this->testResults->where(
                'user_id',
                $row instanceof \App\Models\User  ? $row->id : $row->link_id
            )->first();

            $data[] = $result['total_marks'] ?? '';
            $data[] = $result['obtain_marks'] ?? '';
            $data[] = $result['percentage'] ?? '';
            $data[] = $result['result'] ?? '';
        }

        return $data;
    }
    protected function getFreeTextAnswer($userId, $questionId)
    {
        $userAnswer = DB::table('answers')
            ->where('user_id', $userId)
            ->where('question_id', $questionId)
            ->first();

        return $userAnswer ? $userAnswer->free_text_answer : '';
    }
    // //Calculate Partial Marks for MCQ: Check if the question type is MCQ, and if so, determine how many correct options were selected by the user and award marks proportionally.
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
            $correctCount = 0;

            foreach ($answerIds as $answerId) {
                $option = QuestionAttribute::where('question_id', $userAnswer->question_id)
                    ->where('id', $answerId)
                    ->value('option');

                $isCorrect = in_array($answerId, $validAnswerIds);
                if ($isCorrect) {
                    $correctCount++;
                }

                if ($option !== null) {
                    $options[] = [
                        'option' => $option,
                        'is_correct' => $isCorrect,
                    ];
                }
            }

            // Calculate partial marks
            $totalCorrectOptions = count($validAnswerIds);
            $totalMarks = DB::table('questions')->where('id', $questionId)->value('marks');
            $obtainedMarks = ($totalMarks / $totalCorrectOptions) * $correctCount;

            // Append obtained marks as part of the options array
            foreach ($options as &$option) {
                $option['obtained_marks'] = $obtainedMarks;
            }

            return $options;
        }

        return [];
    }

    // protected function getUserAnswer($userId, $questionId)
    // {

    //     $userAnswer = DB::table('answers')
    //         ->where('user_id', $userId)
    //         ->where('question_id', $questionId)
    //         ->first();
    //     // dd($userAnswer);

    //     if ($userAnswer) {
    //         $answerIds = explode(',', $userAnswer->answer_id);
    //         $validAnswerIds = explode(',', $userAnswer->valid_answer);

    //         $options = [];
    //         foreach ($answerIds as $answerId) {

    //             $option = QuestionAttribute::where('question_id', $userAnswer->question_id)
    //                 ->where('id', $answerId)
    //                 ->value('option');

    //             if ($option !== null) {
    //                 $options[] = [
    //                     'option' => $option,
    //                     'is_correct' => in_array($answerId, $validAnswerIds),
    //                 ];
    //             }
    //         }

    //         return $options;
    //     }

    //     return [];
    // }
    public function headings(): array
    {
        $headings = ['SN', 'Email'];

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
