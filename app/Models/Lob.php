<?php

namespace App\Models;

use Eloquent, Session;

/**
 * Lob Model
 */

class Lob extends Eloquent
{

	protected $fillable = [
		'id',        
		'lob',
		'is_active',
	];
	protected $table = 'lobs';
}
