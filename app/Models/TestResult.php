<?php

namespace App\Models;

use Eloquent, Session;
use App\Models\QuestionAttribute;
use App\Models\Question;

/**
 * TestResult Model
 */

class TestResult extends Eloquent
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'test_results';

	protected $fillable = [
		'test_id',
		'user_id',
		'attempt_number',
		'total_questions',
		'total_attemted_questions',
		'total_marks',
		'obtain_marks',
		'percentage',
		'result',
		'status',
		'user_attempts'
	];
	public function user_details()
	{
		return $this->hasOne(User::class, 'id', 'user_id');
	}
}// end TestResult class
