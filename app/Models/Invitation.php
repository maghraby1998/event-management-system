<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $table = "invitations";

    protected $fillable = [
        "event_id",
        "sender_id",
        "receiver_id",
        "status",
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, "sender_id");
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, "receiver_id");
    }

    public function event()
    {
        return $this->belongsTo(Event::class, "event_id");
    }

    public function canAccept()
    {
        return $this->status == "pending" && $this->receiver_id == auth()->id();
    }

    public function canReject()
    {
        return $this->status == "pending" && $this->receiver_id == auth()->id();
    }

    public function canCancel()
    {
        return $this->status == "pending" && $this->sender_id == auth()->id();
    }
}
