<?php

namespace App\Models;

use Eloquent, Session;

/**
 * Test Model
 */

class Test extends Eloquent
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tests';
	protected $fillable = [
		'user_id',
		'category_id',
		'type',
		'minimum_marks',
		'title',
		'number_of_attempts',
		'description',
		'is_active',
		'start_date_time',
		'end_date_time',
		'time_of_test',
		'status',
		'region',
		'lob',
		'circle',
		'number_of_questions',
		'regipublish_resulton',
		'thumbnail',
	];


	public function getImageAttribute($value = "")
	{
		if (!empty($value) && file_exists(TRAINING_DOCUMENT_ROOT_PATH . $value)) {
			return TRAINING_DOCUMENT_URL . $value;
		}
	}
	public function test_participants()
	{
		return $this->hasMany(TestParticipants::class);
	}
	public function test_results()
	{
		return $this->hasMany(TestResult::class);
	}
}// end Test class
