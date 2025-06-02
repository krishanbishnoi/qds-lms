<?php

namespace App\Models;

use Eloquent, Session;

class Course extends Eloquent
{

	protected $table = 'courses';

	protected $fillable = [
		'title',
		'skip',
		'test_id',
		'training_id',
		'start_date_time',
		'end_date_time',
		'description',
		'thumbnail',
	];

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
}
