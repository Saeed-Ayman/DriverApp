<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $fillable = [
        'image_id',
        'imageable',
    ];

    public function getUrlAttribute(): string
    {
        return \Cloudinary::getUrl($this->image_id);
    }
}
