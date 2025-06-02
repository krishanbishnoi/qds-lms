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
	protected $fillable = [
		'category_id',
		'title',
		'type',
		'minimum_marks',
		'number_of_attempts',
		'skip',
		'test_id',
		'start_date_time',
		'end_date_time',
		'thumbnail',
		'description',
		'user_id',
		// Add any other columns you want to be mass assignable here
	];

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
	public function training_participants_count()
	{
		return $this->hasMany(TrainingParticipants::class);
	}
}// end Training class
