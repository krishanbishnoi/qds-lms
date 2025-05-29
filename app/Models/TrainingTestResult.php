<?php

namespace App\Models;

use Eloquent, Session;
use App\Models\QuestionAttribute;
use App\Models\Question;

/**
 * TestResult Model
 */

class TrainingTestResult extends Eloquent
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'training_test_results';

	protected $fillable = ['test_id', 'user_id', 'training_id', 'course_id', 'total_questions', 'total_attemted_questions', 'total_marks', 'obtain_marks', 'percentage', 'result', 'status'];
}// end TestResult class
