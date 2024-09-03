<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Event;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function scopeFilter($query, $filters)
    {
        if (isset($filters['status'])) {
            $query->where("status", $filters['status']);
        }

        return $query;
    }

    public function canAcceptOrReject()
    {
        if ($this->status === "pending") {
            $eventCreatorId = $this->event->user->id;

            if ($eventCreatorId == auth()->id()) {
                return true;
            }
        }

        return false;
    }

    public function canCancel()
    {
        if ($this->status === "pending") {
            if ($this->user_id == auth()->id()) {
                return true;
            }
        }

        return false;
    }

}
