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
        dump("EMD Model V3");
        dump("Version (" . config("constants.version") . ")");
        dump("Commit No (" . config("constants.commit_no") . ")");
        return Command::SUCCESS;
    }
}
