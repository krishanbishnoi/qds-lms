<?php

namespace App\Model;

use Eloquent;

/**
 * TrainingParticipants Model
 */
class UserAssignedTestQuestion extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_assigned_test_questions';

    protected $fillable = ['test_id', 'trainee_id', 'questions_id'];
}// end TrainingParticipants class
