<?php

namespace App\Model;

use Eloquent;

/**
 * ManagerTrainings Model
 */
class ManagerTrainings extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'manager_assign_training';

    // protected $fillable = ['training_id','trainee_id'];

}// end ManagerTrainings class
