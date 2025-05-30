<?php

namespace App\Models;

use Eloquent, Session;

/**
 * Circle Model
 */

class Circle extends Eloquent
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $fillable = [
		'id',
		'circle',
		'region_id',
		'is_active',
	];
	protected $table = 'circles';
}// end Circle class
