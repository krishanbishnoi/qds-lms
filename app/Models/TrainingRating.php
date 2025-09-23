<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingRating extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'training_ratings';
    public $timestamps = false;

    // Specify the fields that can be mass-assigned
    protected $fillable = [
        'training_id',
        'user_id',
        'rating',
    ];
}
