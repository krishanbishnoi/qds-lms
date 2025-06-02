<?php

namespace App\Models;

use Eloquent, Session;

/**
 * TraineeAssignedTrainingDocument Model
 */

class TraineeAssignedTrainingDocument extends Eloquent
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'trainee_assigned_training_documents';

	protected $fillable = ['user_id', 'training_id', 'course_id', 'document_id', 'type', 'status'];
}// end TraineeAssignedTrainingDocument class
