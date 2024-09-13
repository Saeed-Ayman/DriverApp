<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Location extends Model
{
    use HasFactory, HasSlug;

    const DEFAULT_LOGO = 'logos/default-logo';

    protected $fillable = [
        'name',
        'excerpt',
        'image_id',
        'description',
        'whatsapp',
        'phone',
        'landline',
        'services',
        'location',
    ];

    protected $casts= [
        'services' => 'array',
        'location' => 'array',
    ];

    public static function getSlugColumn(): string
    {
        return 'name';
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getLogoAttribute(): string
    {
        return \Cloudinary::getUrl($this->image_id ?? self::DEFAULT_LOGO);
    }

    public function scopeWithReviewsStatus(Builder $builder): Builder
    {
        return $builder
            ->withAvg('reviews', 'stars')
            ->withCount('reviews');
    }

    public function loadWithReviewsStatus(): self
    {
        return $this
            ->loadCount('reviews')
            ->loadAvg('reviews', 'stars');
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function favorite(): MorphOne
    {
        return $this->morphOne(Favorite::class, 'favoriteable');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function category(): HasOne
    {
        return $this->hasOne(LocationCategory::class);
    }
}
