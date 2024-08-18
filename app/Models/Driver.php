<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Driver extends Model
{
    use HasFactory, HasSlug;

//    protected $primaryKey = 'slug';

    protected $fillable = [
        'name',
        'phone',
        'image_id',
        'images',
        'description',
        'phone',
        'whatsapp',
        'country',
        'government',
        'slug'
    ];

    /**
     * @return string
     */
    public static function getSlugColumn(): string
    {
        return 'name';
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }


    /**
     * Define the type column to every Item object instance
     *
     * @return string
     */
    public function getAvatarAttribute(): string
    {
        return \Cloudinary::getUrl($this->image_id ?? User::DEFAULT_AVATAR);
    }

    public function scopeWithReviewsStatus(Builder $builder): Builder
    {
        return $builder
            ->withAvg('reviews', 'stars')
            ->withCount('reviews');
    }

    public function loadWithReviewsStatus(): Driver
    {
        return $this
            ->loadCount('reviews')
            ->loadAvg('reviews', 'stars');
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function Images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function Image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
