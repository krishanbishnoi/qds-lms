<?php

namespace App\Models;

use Eloquent, Session;

/**
 * Region Model
 */

class Region extends Eloquent
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'regions';
	protected $fillable = [
		'id',
		'region',
		'is_active',
	];
}// end Region class
