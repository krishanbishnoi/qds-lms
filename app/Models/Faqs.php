<?php

namespace App\Models;

use Eloquent;

/**
 * Cms Model
 */
class Faqs extends Eloquent
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */

	protected $table = 'faqs';

	/**
	 * hasMany  function for bind CmsDescription and get result acoording language
	 *
	 * @param null
	 * 
	 * @return query
	 */
}
