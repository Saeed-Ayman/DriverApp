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
        return Image::getUrl($this->attributes['image_id']);
    }

    public static function getUrl(string $image_id): string
    {
//        if (cache()->has($image_id)) {
//            return cache()->get($image_id);
//        }

        $url = \Cloudinary::getUrl($image_id);

        cache()->put($image_id, $url, 86400);

        return $url;
    }

    public static function deleteImage(string $image_id): void
    {
        cache()->forget($image_id);

        \Cloudinary::destroy($image_id);
    }
}
