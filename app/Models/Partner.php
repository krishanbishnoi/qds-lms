<?php

namespace App\Models;

use Eloquent, Session;

/**
 * Partner Model
 */

class Partner extends Eloquent
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'partners';
	protected $fillable = ['name', 'location'];
}// end Partner class
