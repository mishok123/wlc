<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the score refresh to run hourly
Schedule::command('projects:refresh-scores')->hourly();

// Schedule the health check to run every 30 minutes
Schedule::command('monitor:health')->everyThirtyMinutes();
