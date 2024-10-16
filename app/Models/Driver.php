<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Auth;

class Driver extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name',
        'phone',
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

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => Image::select('image_url')
                ->where('imageable_type', self::class . '\\avatar')
                ->where('imageable_id', $this->id)->value('image_url'),
            set: fn($value) => $value,
        );
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

    public function scopeWithFavorites(Builder $builder): Builder
    {
        $auth = Auth::guard('sanctum');

        return $builder->withExists([
            'favorite' => function (Builder $builder) use ($auth) {
                $builder->where('user_id', $auth->id());
            }
        ]);
    }

    public function loadWithFavorites(): self
    {
        $auth = Auth::guard('sanctum');

        return $this->loadExists([
            'favorite' => function (Builder $builder) use ($auth) {
                $builder->where('user_id', $auth->id());
            }
        ]);
    }

    public function favorite(): MorphOne
    {
        return $this->morphOne(Favorite::class, 'favoriteable');
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
