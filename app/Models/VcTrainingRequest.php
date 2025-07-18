<?php

namespace App\Models;

use Eloquent, Session;

/**
 * TrainingType Model
 */

class VcTrainingRequest extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'vc_training_requests';
    protected $fillable = ['training_id', 'user_id', 'requested_at', 'status', 'remarks'];
}// end TrainingType class
