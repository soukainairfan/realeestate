<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Image extends Model
{
    protected $fillable = ['path', 'is_main'];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}