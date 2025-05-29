<?php

namespace App\Models;

use Eloquent, Session;

/**
 * TrainingCategory Model
 */

class TestCategory extends Eloquent
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'test_categories';

	protected $fillable = ['name', 'description', 'parent_id', 'keywords', 'is_active', 'order_priority'];
}// end TrainingCategory class
