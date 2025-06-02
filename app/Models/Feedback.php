<?php

namespace App\Models;

use Eloquent, Session;

/**
 * Test Model
 */

class Feedback extends Eloquent
{

    protected $table = "feedback";

    protected $fillable = ['activity_type_id', 'type', 'feedback', 'user_id'];
}// end Test class
