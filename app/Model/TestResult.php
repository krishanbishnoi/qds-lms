<?php

namespace App\Model;

use Eloquent;

/**
 * TestResult Model
 */
class TestResult extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'test_results';

    protected $fillable = ['test_id', 'attempt_number', 'user_id', 'total_questions', 'total_attemted_questions', 'total_marks', 'obtain_marks', 'percentage', 'result', 'status', 'user_attempts'];

    public function user_details()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function test()
    {
        return $this->belongsTo(Test::class, 'test_id')->withDefault();
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
}// end TestResult class
