<?php

namespace App\Models;

use Eloquent, Session;

/**
 * TrainingCategory Model
 */

class TrainingCategory extends Eloquent
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'training_categories';

	protected $fillable = ['name', 'description', 'parent_id', 'keywords', 'is_active', 'order_priority'];
}// end TrainingCategory class
