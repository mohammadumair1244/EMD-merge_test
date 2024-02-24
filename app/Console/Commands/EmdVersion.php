<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class EmdVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emd -v';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Emd Version';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        dump("EMD V3 (" . config("constants.version") . ")");
        return Command::SUCCESS;
    }
}
