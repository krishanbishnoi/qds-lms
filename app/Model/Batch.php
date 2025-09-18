<?php

namespace App\Model;

use Eloquent;

/**
 * Batch Model
 */
class Batch extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'batches';
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function assigned_trainers()
    {
        return $this->hasMany(TrainerAssignBatch::class);
    }
} // end Batch class
