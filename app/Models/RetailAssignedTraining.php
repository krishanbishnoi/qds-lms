<?php

namespace App\Models;

use Eloquent, Session;

/**
 * TrainingParticipants Model
 */

class RetailAssignedTraining extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'retail_assigned_trainings';


    protected $fillable = [
        'training_id',
        'client_id',
        'campaign_id',
        'store_code',
    ];

    public function training()
    {
        return $this->belongsTo(Training::class);
    }
}// end TrainingParticipants class
