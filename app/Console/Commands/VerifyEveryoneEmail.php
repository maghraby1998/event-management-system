<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VerifyEveryoneEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:verify-everyone-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \App\Models\User::all()->each(function ($user) {
            $user->is_email_verified = true;
            $user->save();
        });
    }
}
