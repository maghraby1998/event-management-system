<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Request;

class Event extends Model
{
    use HasFactory;

    protected $table = "events";


    protected $fillable = [
        'created_by',
        'name',
        'from',
        'to',
        'description',
        'extra_description',
        'is_public',
        'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "created_by");
    }

    public function users()
    {
        return $this->belongsToMany(User::class, "user_events")->withTimestamps();
    }

    public function favouriteByUsers()
    {
        return $this->belongsToMany(User::class, "user_favourite_events")->withTimestamps();
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function scopeFilter($query, $filters)
    {
        if (isset($filters["search"]) && $filters["search"] != "") {
            $query->where("name", "LIKE", "%" . $filters["search"] . "%");
        }
    }

    public function canTakeActions()
    {
        return $this->user->id == auth()->user()->id;
    }

    public function isFavourite()
    {
        $isFavouriteEventByAuth = auth()->user()->favouriteEvents()->find($this->id);

        return !!$isFavouriteEventByAuth;
    }

    public function isAuthJoined()
    {
        $alreadyJoined = auth()->user()->joinedEvents()->find($this->id);
        return $alreadyJoined;
    }

    public function canJoin()
    {
        return $this->created_by->id != auth()->id() && $this->is_public && !$this->isAuthJoined();
    }

    public function canRequestToJoin()
    {
        return $this->created_by->id != auth()->id() && !$this->is_public && !$this->isAuthJoined();
    }

    public function joiningStatus()
    {
        if ($this->isAuthJoined()) {
            return "JOINED";
        } else if ($this->canjoin()) {
            return "CAN_JOIN";
        } else if ($this->canRequestToJoin()) {
            return "CAN_REQUEST_TO_JOIN";
        } else {
            return "UNABLE";
        }
    }


}
