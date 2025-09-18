<?php

namespace App\Model;

use Eloquent; /**
 * TestResult Model
 */
class TrainingTestResult extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'training_test_results';

    protected $fillable = ['test_id', 'user_id', 'training_id', 'course_id','attempt_number', 'total_questions', 'total_attemted_questions', 'total_marks', 'obtain_marks', 'percentage', 'result', 'status','user_attempts'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();;
    }
    public function test()
    {
        return $this->belongsTo(Test::class, 'test_id');
    }
    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function user_details()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function training_test_participants()
    {
        return $this->hasMany(TrainingTestParticipants::class, 'test_id', 'test_id');
    }
   
}// end TestResult class
