<?php

namespace App\Models;

use Eloquent, Session;

/**
 * Course Model
 */

class Course extends Eloquent
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'courses';


	public function getImageAttribute($value = "")
	{
		if (!empty($value) && file_exists(TRAINING_DOCUMENT_ROOT_PATH . $value)) {
			return TRAINING_DOCUMENT_URL . $value;
		}
	}
	public function CourseContentAndDocument()
	{
		return $this->hasMany(TrainingDocument::class);
	}

	public function training()
	{
		return $this->belongsTo(Training::class);
	}

	public function test()
	{
		return $this->belongsTo(Test::class);
	}
}// end Course class
