<?php

namespace App\Imports;

use App\Model\Question;
use App\Model\QuestionAttribute;
use DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class importQuestionsCsv implements ToModel, WithHeadingRow, WithCustomCsvSettings
{
    private $errors = [];
    private $hasError = false;
    private $test_id;

    public function __construct($test_id)
    {
        $this->test_id = $test_id;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    // Implement custom CSV settings if needed (e.g., different delimiter)
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',', // Adjust the delimiter if your CSV uses something else, e.g., ';'
        ];
    }

    public function model(array $row)
    {
        $requiredColumns = ['q_no', 'q_type', 'question', 'marks'];

        foreach ($requiredColumns as $column) {
            if (!array_key_exists($column, $row)) {
                $this->errors[] = "The CSV file is missing the required column: '{$column}'.";
                $this->hasError = true;
                return null;
            }
        }

        $test_id = $this->test_id;

        $questionAlreadyExist = DB::table('questions')
            ->where('question', $row['question'])
            ->where('test_id', $test_id)
            ->first();

        if (!empty($questionAlreadyExist)) {
            $this->errors[] = "This question already exists in this test.";
            $this->hasError = true;
            return null;
        } else {
            if (!empty($row['q_type'])) {
                if (empty($row['marks'])) {
                    $this->errors[] = "Marks cannot be null for questions.";
                    $this->hasError = true;
                    return null;
                }

                if (!$this->hasError) {
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
                }
            } else {
                $question = Question::latest('id')->first();

                $optionText = $row['question'];
                $isCorrect = str_ends_with($optionText, '(*)');

                if ($isCorrect) {
                    $optionText = str_replace('(*)', '', $optionText);
                }

                // Handle specific cases for True/False without converting 1/0
                if (strcasecmp($optionText, 'True') === 0 && !is_numeric($optionText)) {
                    $optionText = "True";
                } elseif (strcasecmp($optionText, 'False') === 0 && !is_numeric($optionText)) {
                    $optionText = "False";
                }
            

                if (!$this->hasError) {
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
}
