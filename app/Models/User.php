<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Event;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Request;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function myEvents(): HasMany
    {
        return $this->hasMany(Event::class, "created_by");
    }

    public function joinedEvents()
    {
        return $this->belongsToMany(Event::class, "user_events")->withTimestamps();
    }

    public function favouriteEvents()
    {
        return $this->belongsToMany(Event::class, "user_favourite_events")->withTimestamps();
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function emailToken()
    {
        return $this->hasOne(UserEmailVerificationTokens::class);
    }
}
