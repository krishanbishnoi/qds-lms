<?php

namespace App\Models;

use Eloquent, Session;
use App\Models\QuestionAttribute;
use App\Models\Question;

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

	protected $fillable = ['test_id', 'question_id', 'answer_id', 'user_id', 'free_text_answer', 'valid_answer'];

	public function question()
	{
		return $this->belongsTo(Question::class);
	}
	public function isCorrectAnswer()
	{
		return $this->answer_id === $this->valid_answer;
	}
}// end Question class
