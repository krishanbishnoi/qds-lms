<?php

namespace App\Model;

use Eloquent;

/**
 * Test Model
 */
class Test extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tests';
    protected $fillable = ['status'];

    public function getImageAttribute($value = '')
    {
        if (! empty($value) && file_exists(config('TRAINING_DOCUMENT_ROOT_PATH').$value)) {
            return config('TRAINING_DOCUMENT_URL').$value;
        }
    }

    public function test_participants()
    {
        return $this->hasMany(TestParticipants::class);
    }

    public function test_results()
    {
        return $this->hasMany(TestResult::class);
    }
    public function tarining_test_participants()
    {
        return $this->hasMany(TrainingTestParticipants::class);
    }
    public function training_test_participants()
    {
        return $this->hasMany(TrainingTestParticipants::class);
    }

    public function training_test_results()
    {
        return $this->hasMany(TrainingTestResult::class);
    }

}// end Test class
