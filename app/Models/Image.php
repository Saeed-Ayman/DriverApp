<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Psr\SimpleCache\InvalidArgumentException;

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

    /**
     * @throws InvalidArgumentException
     */
    public static function getUrl(string $image_id): string
    {
        if (\Cache::driver('database')->has($image_id)) {
            return \Cache::driver('database')->get($image_id);
        }

        $url = \Cloudinary::getUrl($image_id);

        \Cache::driver('database')->put($image_id, $url);

        cache()->put($image_id, $url, 86400);

        return $url;
    }

    public static function deleteImage(string $image_id): void
    {
        cache()->forget($image_id);

        \Cloudinary::destroy($image_id);
    }
}
