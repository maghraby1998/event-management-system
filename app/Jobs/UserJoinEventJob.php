<?php

namespace App\Jobs;

use App\Mail\JoinEventRequestMail;
use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class UserJoinEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    private $userId;
    private $eventId;

    public function __construct($userId, $eventId)
    {
        $this->userId = $userId;
        $this->eventId = $eventId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::find($this->userId);

        $user->joinedEvents()->attach($this->eventId);

        $event = Event::find($this->eventId);

        $eventOwnerName = $event->user->name;

        $eventRequesterName = $user->name;

        $eventName = $event->name;

        Mail::to($event->user)->send(new JoinEventRequestMail($eventOwnerName, $eventRequesterName, $eventName));
    }
}
