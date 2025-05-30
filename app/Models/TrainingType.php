<?php

namespace App\Models;

use Eloquent, Session;

/**
 * TrainingType Model
 */

class TrainingType extends Eloquent
{
	protected $fillable = [
		'id',        
		'type',
		'is_active',
	];
	protected $table = 'training_types';
}
