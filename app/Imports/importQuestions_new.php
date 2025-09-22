<?php

namespace App\Imports;

use App\Model\Question;
use App\Model\QuestionAttribute;
use DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

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
        // dd( (string)trim($row['question']));

        $questionAlreadyExist = DB::table('questions')->where('question', $row['question'])->where('test_id', $test_id)->first();
        if (empty($questionAlreadyExist)) {

            if (!empty($row['q_type'])) {
                $question = new Question([
                    'test_id' => $test_id,
                    'question_type' => $row['q_type'],
                    'question' => $row['question'],
                    'marks' => $row['marks'],
                    'is_active' => 1,
                    'count' => 1,
                    'time_limit' => 1,
                ]);
                $question->save();
            } else {
                $question = Question::latest('id')->first();

                $optionText = trim($row['question']);
                // Check if the option text ends with '(*)' to determine if it is correct or not
                $isCorrect = str_ends_with($optionText, '(*)');

                // Remove '(*)' from the option text
                if ($isCorrect) {
                    $optionText = str_replace('(*)', '', $optionText);
                }
                // Handle percentage values
                if (strpos($optionText, '%') !== false) {
                    $optionText = str_replace('%', '', $optionText);
                    $optionText = (float) $optionText / 100;
                }

                // Handle date values
                if ($this->isDate($optionText)) {
                    $optionText = date('Y-m-d', strtotime($optionText));
                }

                $option = new QuestionAttribute([
                    'question_id' => $question->id,
                    'option' => (string)$optionText,
                    'is_correct' => $isCorrect,
                ]);
                $option->save();
            }
        }
    }
    private function isDate($value)
    {
        if (!$value) {
            return false;
        }

        try {
            Carbon::parse($value);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
