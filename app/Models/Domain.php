<?php

namespace App\Models;

use Eloquent, Session;

/**
 * Domain Model
 */

class Domain extends Eloquent
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $fillable = [
		'id',        
		'domain',
		'is_active',
	];
	protected $table = 'domains';
}// end Domain class
