<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'welcome-user {username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this is just for testing purposes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("welcome {$this->argument('username')}");
    }
}
