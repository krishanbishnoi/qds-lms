<?php

namespace App\Models;

use Eloquent, Session;

/**
 * TrainingParticipants Model
 */

class TestParticipants extends Eloquent
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'test_participants';


	protected $fillable = [
		'test_id',
		'trainee_id',
		'type',
		'attempt_number',
		'status',
		'number_of_attempts',
		'user_attempts'
	];

	public function test_details()
	{
		return $this->hasOne(Test::class, 'id', 'test_id');
	}
	public function user_test_results()
	{
		return $this->hasOne(TestResult::class, 'test_id', 'test_id');
	}
	public function attendee()
	{
		return $this->belongsTo(TestAttendee::class, 'trainee_id', 'link_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'trainee_id', 'id');
	}
}// end TrainingParticipants class
