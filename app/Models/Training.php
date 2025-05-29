<?php

namespace App\Models;

use Eloquent, Session;

/**
 * Training Model
 */

class Training extends Eloquent
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'trainings';


	public function getImageAttribute($value = "")
	{
		if (!empty($value) && file_exists(TRAINING_DOCUMENT_ROOT_PATH . $value)) {
			return TRAINING_DOCUMENT_URL . $value;
		}
	}
	public function training_courses()
	{
		return $this->hasMany(Course::class);
	}
	public function training_participants()
	{
		return $this->hasMany(TrainingTestParticipants::class);
	}
	public function training_results()
	{
		return $this->hasMany(TrainingTestResult::class);
	}
}// end Training class
