<?php

namespace App\Models;

use Eloquent;

/**
 * Cms Model
 */
class Cms extends Eloquent
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */

	protected $table = 'cms_pages';

	/**
	 * hasMany  function for bind CmsDescription and get result acoording language
	 *
	 * @param null
	 * 
	 * @return query
	 */

	public function get_cms_details($slug = null)
	{
		$result = Cms::where('slug', $slug)->where('is_active', IS_ACTIVE)->first();
		return $result;
	}
}
