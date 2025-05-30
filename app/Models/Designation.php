<?php

namespace App\Models;

use Eloquent, Session;
use App\Models\QuestionAttribute;
use App\Models\Question;

/**
 * Designation Model
 */

class Designation extends Eloquent
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'designations';
	protected $fillable = [
		'id',        
		'designation',
		'is_active',
	];


	// public function question()
	// {
	// 	return $this->belongsTo(Question::class);
	// }
	// public function isCorrectAnswer()
	// {
	//     return $this->answer_id === $this->valid_answer;
	// }

}// end Designation class
