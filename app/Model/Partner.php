<?php

namespace App\Model;

use Eloquent;

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
