<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name',
        'code',
        'capital',
        'region',
        'subregion',
        'population',
        'currency',
        'flag',
        'latitude',
        'longitude',
        'risk_score',
    ];

    public function weather()
    {
        return $this->hasOne(Weather::class);
    }

    public function economy()
    {
        return $this->hasOne(Economy::class);
    }
}