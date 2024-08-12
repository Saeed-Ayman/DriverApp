<?php

namespace App\Traits;

use App\Casts\SlugCast;

trait HasSlug
{
    /**
     * Column that's slug gets value from it.
     * - Default (title | email | name)
     */
    protected static string|null $primary_column = null;
    /**
     * Max length of slug
     * - Note: cutting made in end of word nearest to max_char
     */
    protected static int $max_char = -1;
    /**
     * Additional char if slug equal to slug in db
     */
    protected static int $additional_char = 5;
    /**
     * Column that's slug search on to be unique
     * - Default (slug | username)
     */
    protected static string $slug_column = 'slug';

    /**
     * set config casting slug
     */
    public static function slugCastConfig(): array
    {
        return [
            'primary_column' => self::$primary_column,
            'max_char' => self::$max_char,
            'additional_char' => self::$additional_char
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        // to trigger cast
        self::creating(fn($model) => $model[self::$slug_column] = '');
    }

    protected function casts(): array
    {
        return [
            self::$slug_column => SlugCast::class.':'.self::class
        ];
    }
}
