<?php

namespace App\Model;

use Eloquent;

/**
 * Region Model
 */
class Center extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'centers';

    public function users()
    {
        return $this->hasMany(User::class, 'center', 'center');
    }
}// end Region class
