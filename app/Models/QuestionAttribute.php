<?php

namespace App\Models;

use Eloquent, Session;
use App\Models\Question;

/**
 * QuestionAttribute Model
 */

class QuestionAttribute extends Eloquent
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'question_attributes';
	protected $fillable = ['question_id', 'option', 'is_correct'];


	public function question()
	{
		return $this->belongsTo(Question::class);
	}
}// end QuestionAttribute class
