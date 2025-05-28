<?php

namespace App\Models;

use Eloquent, Session;

/**
 * TrainingParticipants Model
 */

class UserDetail  extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_details';


    protected $fillable = [
        'user_id',
        'olms_id',
        'skill_set_1',
        'skill_set_2',
        'skill_set_3',
        'internal_qa',
        'internal_qa_olms',
        'supervisor_name',
        'supervisor_olms',
        'business_manager_airtel',
        'batch_code',
        'certification_date',
        'final_certification_score',
        'final_certification_status',
        'floor_hit_date',
        'days',
        'bucket',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
