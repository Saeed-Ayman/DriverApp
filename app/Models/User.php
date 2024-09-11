<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasSlug;

    const DEFAULT_AVATAR = 'avatars/default-avatar';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'username',
        'password',
    ];

    protected $appends = [
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'email_verified_at',
        'image_id',
    ];

    /**
     * attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function __construct(array $attributes = [])
    {
        self::$slug_column = 'username';
        parent::__construct($attributes);
    }

    /**
     * Define the type column to every Item object instance
     *
     * @return string
     */
    public function getAvatarAttribute(): string
    {
        return Image::getUrl($this->attributes['image_id'] ?? self::DEFAULT_AVATAR);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
