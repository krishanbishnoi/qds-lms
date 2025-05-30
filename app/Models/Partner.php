<?php

namespace App\Models;

use Eloquent, Session;

class Partner extends Eloquent
{

	protected $table = 'partners';
	protected $fillable = [
		'id',        
		'name',
		'location',
		'is_active',
	];
}
