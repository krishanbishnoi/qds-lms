<?php

namespace App\Model;

use Eloquent;

/**
 * TrainingParticipants Model
 */
class TrainingTestParticipants extends Eloquent
{
    /**
     * The database table used by the model.

     *

     * @var string
     */
    protected $table = 'training_test_participants';

    protected $fillable = ['training_id', 'course_id', 'test_id', 'trainee_id', 'status','user_attempts','created_by'];
    public function user()
    {
        return $this->belongsTo(User::class, 'trainee_id')->withDefault();;
    }
    public function test_details()
    {
        return $this->hasOne(Test::class, 'id', 'test_id');
    }
    public function training_details()
    {
        return $this->hasOne(Training::class, 'id', 'training_id');
    }

}// end TrainingParticipants class
