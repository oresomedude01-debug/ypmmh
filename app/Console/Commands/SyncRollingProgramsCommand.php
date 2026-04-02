<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AutoAssignChildService;

class SyncRollingProgramsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'programs:sync-rolling';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize student rolling program enrollments based on their current age.';

    /**
     * Execute the console command.
     */
    public function handle(AutoAssignChildService $syncService)
    {
        $this->info('Starting rolling programs synchronization...');

        $syncService->syncAllChildren();

        $this->info('Synchronization completed successfully.');
        return Command::SUCCESS;
    }
}
