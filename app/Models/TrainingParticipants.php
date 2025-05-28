<?php

namespace App\Models;

use Eloquent, Session;

/**
 * TrainingParticipants Model
 */

class TrainingParticipants extends Eloquent
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'training_participants';


	protected $fillable = ['training_id', 'trainee_id'];
}// end TrainingParticipants class
