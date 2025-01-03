<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SeedEmailVerifiedColumn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-email-verified-column';

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
        //
        echo "بحب ملك";
    }
}
