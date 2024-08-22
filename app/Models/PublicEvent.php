<?php

namespace App\Models;

use App\Models\Event;
use App\Models\Scopes\PublicEventScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicEvent extends Event
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new PublicEventScope);
    }
}
