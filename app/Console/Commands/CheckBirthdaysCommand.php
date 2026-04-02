<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\BirthdayNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class CheckBirthdaysCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-birthdays';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for upcoming student birthdays (2 days in advance) and notify admins.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for upcoming birthdays...');

        $targetDate = Carbon::now()->addDays(2);
        $month = $targetDate->month;
        $day = $targetDate->day;

        $children = User::role('Child')
            ->whereNotNull('date_of_birth')
            ->whereMonth('date_of_birth', $month)
            ->whereDay('date_of_birth', $day)
            ->get();

        if ($children->isEmpty()) {
            $this->info('No upcoming birthdays found for ' . $targetDate->format('M d'));
            return Command::SUCCESS;
        }

        $admins = User::role('Admin')->get();

        foreach ($children as $child) {
            $this->info("Notifying admins about {$child->first_name}'s birthday on " . $targetDate->format('M d'));
            Notification::send($admins, new BirthdayNotification($child, 2));
        }

        $this->info('Notification process completed.');
        return Command::SUCCESS;
    }
}
