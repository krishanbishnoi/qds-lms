<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAttempt extends Model
{
    use HasFactory;
    protected $fillable = ['test_id', 'user_id', 'obtain_marks', 'result', 'total_marks', 'user_attempts'];

}
