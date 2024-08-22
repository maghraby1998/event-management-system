<?php

namespace App\Models;

use App\Models\Event;
use App\Models\Scopes\PrivateEventScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivateEvent extends Event
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new PrivateEventScope);
    }
}
