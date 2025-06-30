<?php

namespace App\Models;

use Eloquent, Session;

/**
 * TrainingType Model
 */

class TrainingType extends Eloquent
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'training_types';
	protected $fillable = [
		'type',
		'is_active',
	];
}// end TrainingType class
