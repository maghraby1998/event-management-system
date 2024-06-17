<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;


    protected $fillable = [
        'created_by',
        'name',
        'from',
        'to',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, "created_by");
    }

    public function users() {
        return $this->belongsToMany(User::class, "user_events");
    }
}
