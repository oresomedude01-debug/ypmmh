<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('programs:sync-rolling')->daily();
Schedule::command('users:remind-verification')->dailyAt('10:00');
Schedule::command('premium:check-subscriptions')->daily();
