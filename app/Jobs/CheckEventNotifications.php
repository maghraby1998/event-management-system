<?php

namespace App\Jobs;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckEventNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $now = Carbon::now()->addHours(3);

        $tenMinutesFromNow = Carbon::now()->addHours(3)->addMinutes(10);

        $events = Event::where("from", ">", $now)->where("from", "<=", $tenMinutesFromNow)->get();

        foreach ($events as $event) {
            $emails = array_merge($event->users()->pluck("email")->toArray(), [$event->user->email]);
            info("should notify these emails");
            info($emails);
        }


    }
}
