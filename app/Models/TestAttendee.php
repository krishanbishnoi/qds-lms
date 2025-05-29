<?php

namespace App\Models;

use Eloquent, Session;

/**
 * TrainingParticipants Model
 */

class TestAttendee extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'test_attendees';
    protected $primaryKey = 'id';

    protected $fillable = ['test_id', 'email', 'status'];

    public function participant()
    {
        return $this->hasOne(TestParticipants::class, 'trainee_id', 'link_id');
    }
    protected static function booted()
    {
        static::creating(function ($model) {
            $lastId = self::max('id');
            $newId = $lastId + 1;
            $model->link_id = '10007' . $newId;
        });
    }
}// end TrainingParticipants class
