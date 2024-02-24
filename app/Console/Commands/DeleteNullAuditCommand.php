<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteNullAuditCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:null-audit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Null Audit';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::table('audits')->whereNull('user_id')->delete();
        return Command::SUCCESS;
    }
}
