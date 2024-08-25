<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB; // Import the DB facade
use Illuminate\Console\Command;

class SeedIsEmailVerifiedFalse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed-is-email-verified-false';

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
        DB::update("update users set is_email_verified = 0");
        $this->info('is email verified colun updated to false (0)');
    }
}
