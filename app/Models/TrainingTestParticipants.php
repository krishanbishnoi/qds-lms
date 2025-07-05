<?php

namespace App\Models;

use Eloquent, Session;

/**
 * TrainingParticipants Model
 */

class TrainingTestParticipants extends Eloquent
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'training_test_participants';


	protected $fillable = [
		'training_id',
		'course_id',
		'test_id',
		'attempt_number',
		'trainee_id',
		'status',
		'number_of_attempts',
		'user_attempts'
	];
	public function test_details()
	{
		return $this->hasOne(Test::class, 'id', 'test_id');
	}
}// end TrainingParticipants class
