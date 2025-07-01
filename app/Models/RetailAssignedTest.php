<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetailAssignedTest extends Model
{
    protected $table = 'retail_assigned_tests';


    protected $fillable = [
        'test_id',
        'client_id',
        'campaign_id',
        'store_code',
        'assginTo',
        'validity',
    ];
}
