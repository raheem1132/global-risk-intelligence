<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'country_id',
        'title',
        'description',
        'url',
        'image',
        'source',
        'published_at',
    ];

    /**
     * Relasi ke Country
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}