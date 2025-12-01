<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;

    protected $table = 'agencies';

    protected $fillable = [
        'name',
        'email',
        'mobile_no',
        'agency_code',
        'region_id',
        'state_id',
        'city_id',
        'address',
    ];

    // Relationships (optional)
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
