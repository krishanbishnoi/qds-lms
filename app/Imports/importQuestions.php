<?php

namespace App\Imports;

use App\Models\Question;
use App\Models\QuestionAttribute;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;

class importQuestions implements ToModel, WithHeadingRow
{
    private $errors = [];
    private $test_id;

    public function __construct($test_id)
    {
        $this->test_id = $test_id;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function model(array $row)
    {
        $test_id = $this->test_id;

        $questionAlreadyExist = DB::table("questions")->where('question', $row['question'])->where('test_id', $test_id)->first();
        if (empty($questionAlreadyExist)) {
            if (!empty($row['q_type'])) {
                // Insert question into 'questions' table
                $question = new Question([
                    'test_id'            => $test_id,
                    'question_type'      => $row['q_type'],
                    'question'           => $row['question'],
                    'marks'              => $row['marks'],
                    'is_active'          => 1,
                    'count'              => 1,
                    'time_limit'         => 1,
                ]);
                $question->save();
            } else {
                $question = Question::latest('id')->first();

                $optionText = $row['question'];
                $optionText = trim($optionText);

                // Check if the option text ends with '(*)' to determine if it is correct or not
                $isCorrect = str_ends_with($optionText, '(*)');

                // Remove '(*)' from the option text
                $optionText = str_replace('(*)', '', $optionText);

                // Convert numeric values to percentages if they are between 0 and 1
                if (is_numeric($optionText) && $optionText > 0 && $optionText < 1) {
                    $optionText = ($optionText * 100) . '%';
                }

                $option = new QuestionAttribute([
                    'question_id' => $question->id,
                    'option' => $optionText,
                    'is_correct' => $isCorrect,
                ]);
                $option->save();
            }
        }
    }
}
