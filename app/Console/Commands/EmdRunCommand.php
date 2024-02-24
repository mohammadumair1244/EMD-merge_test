<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class EmdRunCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emd3:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'in this command execute these commands => migrate & db:seed';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Artisan::call('migrate');
        Artisan::call('db:seed');
        return Command::SUCCESS;
    }
}
