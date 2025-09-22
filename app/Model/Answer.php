<?php

namespace App\Model;

use Eloquent;

/**
 * Question Model
 */
class Answer extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'answers';

    // protected $fillable = ['question', 'question_type', 'marks','time_limit','description','count'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
  

    public function isCorrectAnswer()
    {
        return $this->answer_id === $this->valid_answer;
    }
}// end Question class
