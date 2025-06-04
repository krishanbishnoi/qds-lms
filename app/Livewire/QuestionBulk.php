<?php

namespace App\Livewire;

use App\Models\Question;
use App\Models\QuestionAttribute;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class QuestionBulk extends Component
{
    use WithFileUploads;
    public $roles;
    public $test_id;
    public $file;
    public $isLoading = false;  // Add this property to track the loader state
    public $uploadedData = [];
    public $selectedData = [];
    public $isModalOpen = false;
    public $headers = [];
    public $rowErrors = []; // Store errors for specific rows and columns


    public function mount($test_id = 0)
    {
        $this->test_id = $test_id;
    }



    /**
     * Check if a column should be treated as a date
     */
    private function isDateColumn($columnName)
    {
        $dateColumns = ['Question']; // Add expected date column names
        return in_array($columnName, $dateColumns);
    }

    public function convertToSnakeCase($string)
    {
        $string = preg_replace('/\s+/', '_', $string);
        $string = str_replace('*', '', $string);
        $string = strtolower($string);
        return $string;
    }


    private function convertRowKeys(array $row): array
    {
        $converted = [];
        foreach ($row as $key => $value) {
            $converted[$this->convertToSnakeCase($key)] = $value;
        }
        return $converted;
    }
    public function uploadUsers()
    {
        $this->resetImportState();

        $this->isLoading = true;
        if ($this->file) {

            try {
                // Convert the Excel file into an array
                $data = Excel::toArray([], $this->file)[0];
                // dd($data);
                if (empty($data) || !isset($data[0])) {
                    session()->flash('errorMsg', ['The file is empty or has invalid formatting.']);
                    return;
                }


                // Extract headers dynamically and remove empty ones
                $this->headers = array_values(array_filter($data[0], function ($header) {
                    return !empty($header);
                }));

                // Process the remaining rows dynamically
                $this->uploadedData = array_filter(array_map(function ($row) {
                    // Skip rows that are truly empty (not ones with false)
                    if (collect($row)->every(fn($cell) => is_null($cell) || $cell === '')) {
                        return null;
                    }

                    // Match row with header count
                    $row = array_pad($row, count($this->headers), null);
                    $row = array_slice($row, 0, count($this->headers));

                    // Convert Excel date fields dynamically
                    foreach ($this->headers as $index => $columnName) {
                        // Convert boolean false in "Question" column to string "false"
                        if ($columnName === 'Question' && $row[$index] === false) {
                            $row[$index] = 'FALSE';
                        }

                        // Date conversion
                        if ($this->isDateColumn($columnName) && isset($row[$index]) && is_numeric($row[$index])) {
                            $row[$index] = Date::excelToDateTimeObject($row[$index])->format('d/m/Y');
                        }
                    }

                    return array_combine($this->headers, $row);
                }, array_slice($data, 1)));



                // Remove null rows
                $this->uploadedData = array_values($this->uploadedData);

                // Display the modal with data
                $this->isModalOpen = true;
                $this->isLoading = false;
            } catch (\Exception $e) {
                session()->flash('errorMsg', ['Error processing the file. Please check the format and try again.']);
            }
        } else {
            session()->flash('errorMsg', ['No file uploaded.']);
        }
    }
    public function processSelectedData()
    {
        if (empty($this->selectedData)) {
            session()->flash('errorMsg', 'No data selected for processing.');
            return;
        }

        $this->rowErrors = [];
        $successCount = 0;
        DB::beginTransaction();

        try {
            // === FIRST PASS: Validate all selected rows ===
            $questionMap = [];
            $currentQuestionRowKey = null;

            foreach ($this->uploadedData as $rowKey => $row) {
                if (!in_array($rowKey, $this->selectedData)) continue;

                $convertedRow = $this->convertRowKeys($row);

                if (!empty($convertedRow['q_type'])) {
                    // It's a question row
                    $currentQuestionRowKey = $rowKey;
                    $questionMap[$rowKey] = [
                        'question_data' => $convertedRow,
                        'options' => []
                    ];
                } else {
                    // It's an option row
                    if ($currentQuestionRowKey !== null) {
                        foreach ($convertedRow as $value) {
                            if (!empty(trim($value))) {
                                $questionMap[$currentQuestionRowKey]['options'][] = trim($value);
                            }
                        }
                    }
                }
            }

            // dd($questionMap, $currentQuestionRowKey);
            foreach ($questionMap as $rowKey => $data) {
                $this->validateRowWithOptions($data['question_data'], $data['options'], $rowKey);
            }

            // === SECOND PASS: Save all valid rows ===
            foreach ($questionMap as $rowKey => $data) {
                $this->saveData($data['question_data'], $data['options'], $rowKey);
                $successCount++;
            }

            DB::commit();

            if ($successCount > 0) {
                $this->file = null;
                $this->isModalOpen = false;
                session()->flash('success', "{$successCount} row(s) successfully saved!");
                return redirect(request()->header('Referer'));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->handleImportError($e);
        }
    }

    // In your import class:

    private function validateRowWithOptions(array $data, array $options, string $rowKey)
    {
        $test_id = $this->test_id;

        $validator = Validator::make($data, [
            'question' => 'required|max:1000',
        ]);

        if ($validator->fails()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => $validator->errors()->toArray()
            ]));
        }

        $exists = Question::where('question', $data['question'])
            ->where('test_id', $test_id)
            ->exists();

        if ($exists) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['question' => ['This question already exists in this test']]
            ]));
        }
        // dd($options);
        if ($data['q_type'] === 'MCQ' || $data['q_type'] === 'SCQ') {
            if (count($options) < 2) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['options' => ['Question requires at least 2 options']]
                ]));
            }

            $correct = collect($options)->filter(fn($o) => str_ends_with($o, '(*)'))->count();

            if ($correct === 0) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['options' => ['At least one option must be marked as correct (*)']]
                ]));
            }

            if ($data['q_type'] === 'SCQ' && $correct > 1) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['options' => ['SCQ can have only one correct answer']]
                ]));
            }
        }

        if ($data['q_type'] === 'T/F' && count($options) !== 2) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['options' => ['T/F questions must have exactly 2 options']]
            ]));
        }
    }



    private function saveData(array $data, array $options, string $rowKey)
    {
        $test_id = $this->test_id;

        // Save the question
        $question = Question::create([
            'test_id' => $test_id,
            'question_type' => $data['q_type'],
            'question' => $data['question'],
            'marks' => $data['marks'],
            'is_active' => 1,
            'count' => 1,
            'time_limit' => 1,
        ]);

        foreach ($options as $optionTextRaw) {
            $isCorrect = str_ends_with($optionTextRaw, '(*)');
            $optionText = str_replace('(*)', '', $optionTextRaw);
            $optionText = trim($optionText);

            // Normalize boolean-like values from Excel
            if ($optionText === '1') {
                $optionText = 'TRUE';
            } elseif ($optionText === '0') {
                $optionText = 'FALSE';
            }

            // Handle fractional numbers (e.g., 0.25 → 25%)
            if (is_numeric($optionText) && $optionText > 0 && $optionText < 1) {
                $optionText = ($optionText * 100) . '%';
            }

            QuestionAttribute::create([
                'question_id' => $question->id,
                'option' => $optionText,
                'is_correct' => $isCorrect ? 1 : 0,
            ]);
        }
    }


    private function handleImportError(\Exception $e)
    {
        $errorData = json_decode($e->getMessage(), true);

        if (json_last_error() === JSON_ERROR_NONE && isset($errorData['row'])) {
            $this->rowErrors[$errorData['row']] = $errorData['errors'];

            // Build readable error summary
            $errorMessages = collect($errorData['errors'])
                ->map(function ($messages, $field) {
                    return $field . ': ' . implode(', ', $messages);
                })
                ->implode(' | ');

            // Add +1 to show human-readable row number
            $displayRow = (int) $errorData['row'] + 1;

            session()->flash(
                'errorMsg',
                "Error in Row #{$displayRow}: {$errorMessages}"
            );
        } else {
            session()->flash('errorMsg', 'Error during import: ' . $e->getMessage());
        }
    }


    /**
     * Save data based on the role type.
     */



    public function closeModal()
    {
        $this->resetImportState();

        $this->isLoading = false;
        $this->file = null;
    }
    private function resetImportState()
    {
        $this->rowErrors = [];
        $this->uploadedData = [];
        $this->selectedData = [];
        $this->headers = [];
        $this->isModalOpen = false;
    }

    public function render()
    {
        return view('livewire.question-bulk');
    }
}
