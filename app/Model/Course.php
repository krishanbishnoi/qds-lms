<?php

namespace App\Model;

use Eloquent;

/**
 * Course Model
 */
class Course extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'courses';

    public function getImageAttribute($value = '')
    {
        if (! empty($value) && file_exists(config('TRAINING_DOCUMENT_ROOT_PATH').$value)) {
            return config('TRAINING_DOCUMENT_URL').$value;
        }
    }

    public function CourseContentAndDocument()
    {
        return $this->hasMany(TrainingDocument::class);
    }

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
    public function testParticipants()
    {
        return $this->hasMany(TrainingTestParticipants::class, 'course_id');
    }
}// end Course class
