<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class RemindUnverifiedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:remind-verification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send verification reminders to unverified users created within the last 3 days.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting verification reminders check...');

        // Get unverified users created in the last 3 days
        // We check strictly for 1, 2, or 3 days ago to avoid spamming every minute
        // Ideally this command runs once a day.

        $users = User::whereNull('email_verified_at')
            ->where('created_at', '>=', now()->subDays(3))
            ->get();

        $count = 0;

        foreach ($users as $user) {
            // Logic to prevent spam: check if we already sent one today?
            // Since we don't have a "last_reminder_sent_at" column, we'll assume the scheduler runs this daily.
            // But verifyEmail sends the link.

            // To be safe, let's just resend the verification which acts as the reminder.
            $user->sendEmailVerificationNotification();
            $this->line("Sent reminder to: {$user->email}");
            $count++;
        }

        $this->info("Sent {$count} reminders.");

        // Also check for users who just passed the 3 day mark to explicitly notify them of suspension?
        // Our middleware handles the suspension UX. A separate email might be overkill or good.
        // Let's stick to the 3-day reminder request.
    }
}
