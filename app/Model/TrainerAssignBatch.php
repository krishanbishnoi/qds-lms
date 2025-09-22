<?php

namespace App\Model;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrainerAssignBatch extends Eloquent
{
    use HasFactory;
    protected $table = 'trainer_assign_batches';
    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id', 'id');
    }
}
