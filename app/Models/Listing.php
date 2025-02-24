<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Listing.php
class Listing extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'transaction_type',
        'property_type',
        'bedrooms',
        'bathrooms',
        'area',
        'location'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function mainImage()
    {
        return $this->hasOne(Image::class)->where('is_main', true);
    }
}
