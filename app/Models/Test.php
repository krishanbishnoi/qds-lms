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
