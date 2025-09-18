<?php

namespace App\Model;

use Eloquent;

/**
 * Question Model
 */
class Question extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'questions';

    protected $fillable = ['test_id', 'question', 'question_type', 'marks', 'time_limit', 'description', 'count'];

    public function getImageAttribute($value)
    {
        if (! empty($value) && file_exists(CATEGORY_IMAGE_ROOT_PATH.$value)) {
            return CATEGORY_IMAGE_URL.$value;
        }
    }

    public function questionAttributes()
    {
        return $this->hasMany(QuestionAttribute::class);
    }

    public function questionAnswer()
    {
        return $this->hasMany(QuestionAttribute::class);
    }
    public function answer()
    {
        return $this->hasMany(Answer::class);
    }
}// end Question class
