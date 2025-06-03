<?php

namespace App\Livewire;

use App\Models\Question;
use Livewire\Component;

class TestQuestionForm extends Component
{
    public $questionTypes;
    public $question, $question_type, $marks, $description, $options = [];
    public $questionId, $testId;
    public $selected_scq = null; // Track SCQ selection
    public $selected_tf = null;
    protected $listeners = ['descriptionUpdated'];


    public function mount($testId, $questionId = null)
    {
        $this->testId = $testId;
        $this->questionTypes = config('constants.QuestionType');
        if ($questionId) {
            $this->questionId = $questionId;
            $question = Question::with('questionAttributes')->findOrFail($questionId);

            $this->question = $question->question;
            $this->question_type = $question->question_type;
            $this->marks = $question->marks;
            $this->description = $question->description;

            $this->options = $question->questionAttributes->map(function ($attr) {
                return [
                    'option' => $attr->option,
                    'right_answer' => $attr->is_correct ? true : false
                ];
            })->toArray();

            if ($this->question_type === 'SCQ' || $this->question_type === 'T/F') {
                foreach ($this->options as $index => $option) {
                    if ($option['right_answer']) {
                        $this->{"selected_" . strtolower(str_replace('/', '', $this->question_type))} = $index;
                        break;
                    }
                }
            }
        } else {
            $this->question = '';
            $this->question_type = '';
            $this->marks = '';
            $this->description = '';
            $this->options = [];
        }
        if (empty($this->options)) {
            $this->options = [['option' => '', 'right_answer' => false]];
        }
    }

    public function updatedQuestionType()
    {
        $this->selected_scq = null;
        $this->selected_tf = null;

        if ($this->question_type === 'T/F') {
            $this->options = [
                ['option' => 'True', 'right_answer' => false],
                ['option' => 'False', 'right_answer' => false]
            ];
        } elseif (in_array($this->question_type, ['MCQ', 'SCQ'])) {
            $this->options = [['option' => '', 'right_answer' => false]];
        } else {
            $this->options = [];
        }
    }

    public function addOption()
    {
        $this->options[] = ['option' => '', 'right_answer' => false];
    }

    public function removeOption($index)
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options);
    }

    public function updatedSelectedTf($value)
    {
        foreach ($this->options as $index => $option) {
            $this->options[$index]['right_answer'] = false;
        }

        $this->options[$value]['right_answer'] = true;
    }

    public function descriptionUpdated($content)
    {
        $this->description = $content;
    }

    public function render()
    {
        return view('livewire.test-question-form');
    }
}
