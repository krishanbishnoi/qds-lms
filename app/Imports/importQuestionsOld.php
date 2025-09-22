<?php

namespace App\Imports;

use App\Model\Question;
use App\Model\QuestionAttribute;
use Carbon\Carbon;
use DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class importQuestions implements ToModel, WithHeadingRow
{
    private $errors = [];
    private $hasError = false; // Flag to indicate if any error occurred
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
        $requiredColumns = ['q_no', 'q_type', 'question', 'marks'];

        // Check for missing required columns
        foreach ($requiredColumns as $column) {
            if (!array_key_exists($column, $row)) {
                $this->errors[] = "The Excel file is missing the required column: '{$column}'. Please use the sample file's heading to organize your data and try uploading again.";
                $this->hasError = true; // Set error flag
                return null; // Skip processing
            }
        }

        $test_id = $this->test_id;

        // Trim the question text and convert it to a uniform case for comparison
        $questionText = trim(strtolower($row['question']));

        // Check if the question already exists in the test
        $questionAlreadyExist = DB::table('questions')
            ->whereRaw('LOWER(TRIM(question)) = ?', [$questionText]) // Case-insensitive and trimmed comparison
            ->where('test_id', $test_id)
            ->first();

        if (!empty($questionAlreadyExist)) {
            $this->errors[] = "This question already exists in this test. Please don't upload the same question again.";
            $this->hasError = true; // Set error flag
            return null; // Skip processing
        } else {
            // Ensure 'q_type' is provided
            if (!empty($row['q_type'])) {
                // Check if 'marks' is empty or null
                if (empty($row['marks'])) {
                    $this->errors[] = "Marks cannot be null for questions.";
                    $this->hasError = true; // Set error flag
                    return null; // Skip processing
                }

                // Only save question if no errors are found
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
                // Handle options for the question
                $question = Question::latest('id')->first();

                $optionText = $row['question'];

                // Check if the option text ends with '(*)' to determine if it is correct or not
                $isCorrect = str_ends_with($optionText, '(*)');

                // Remove '(*)' from the option text if it exists
                if ($isCorrect) {
                    $optionText = str_replace('(*)', '', $optionText);
                }

                // // Handle True/False values as strings (do not convert to 1/0)
                // if (strcasecmp($optionText, 'True') === 0 || strcasecmp($optionText, 'False') === 0) {
                //     $optionText = ucfirst(strtolower($optionText)); // Ensure correct capitalization ( True / False )
                // }

                // Check if this is a valid date; if it is, format it, otherwise leave the string as it is
                if ($this->isValidDateString($optionText)) {
                    $optionText = date('Y-m-d', strtotime($optionText));
                }

                // Only save option if no errors are found
                if (!$this->hasError) {
                    if ($question->question_type == 'T/F') {
                        if ($optionText == 0 || strtolower($optionText) == 'false') {
                            $optionText = 'False';
                        } elseif ($optionText == 1 || strtolower($optionText) == 'true') {
                            $optionText = 'True';
                        } else {
                            // This handles other cases where the text is neither '0', '1', 'false', nor 'true'
                            $optionText = ucfirst(strtolower($optionText));
                        }
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

    private function isValidDateString($value)
    {
        if (is_numeric($value)) {
            return false; // Do not treat numeric values as dates
        }

        try {
            $parsedDate = Carbon::parse($value);
            // Return true only if the parsed date matches the format of the original string
            return $parsedDate && $parsedDate->format('Y-m-d') === date('Y-m-d', strtotime($value));
        } catch (\Exception $e) {
            return false;
        }
    }
}
