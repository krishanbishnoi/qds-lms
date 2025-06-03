<?php

namespace App\Models;

use Eloquent;

/**
 * EmailTemplate Model
 */

class EmailTemplate extends Eloquent
{


	/**
	 * The database table used by the model.
	 */
	protected $table = 'email_templates';
	protected $fillable = [
		'id',
		'name',
		'subject',
		'action',
		'body'
	];
}// end EmailTemplate class
