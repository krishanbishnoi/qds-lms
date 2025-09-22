<?php

namespace App\Model;

use Eloquent;

/**
 * TrainingParticipants Model
 */
class UserAssignedTrainingProgress extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_assigned_training_progress';

    protected $fillable = ['user_id', 'training_id', 'course_id','document_id','is_read','status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}// end userAssignedTrainingProgress class
