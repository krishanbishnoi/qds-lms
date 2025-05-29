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


	protected $fillable = ['training_id', 'course_id', 'test_id', 'trainee_id', 'status', 'number_of_attempts', 'user_attempts'];
}// end TrainingParticipants class
