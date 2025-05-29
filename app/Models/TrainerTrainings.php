<?php

namespace App\Models;

use Eloquent, Session;

/**
 * TrainerTrainings Model
 */

class TrainerTrainings extends Eloquent
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'trainer_assign_training';


	// protected $fillable = ['training_id','trainee_id'];

}// end TrainerTrainings class
