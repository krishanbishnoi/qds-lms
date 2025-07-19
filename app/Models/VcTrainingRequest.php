<?php

namespace App\Models;

use Eloquent, Session;

/**
 * TrainingType Model
 */

class VcTrainingRequest extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'vc_training_requests';
    protected $fillable = ['training_id', 'user_id', 'requested_at', 'status', 'remarks','status_updated_by'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function training()
    {
        return $this->belongsTo(Training::class);
    }
    public function statususer()
    {
        return $this->belongsTo(User::class,'status_updated_by', 'id');
	}
}// end TrainingType class
