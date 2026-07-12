<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Weather extends Model
{
    protected $table = 'weather';

    protected $fillable = [
        'country_id',
        'temperature',
        'wind_speed',
        'weather_code',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}