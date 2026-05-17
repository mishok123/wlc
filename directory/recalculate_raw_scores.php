<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Project;
use Illuminate\Support\Facades\DB;

echo "Starting category-scoped score recalculation...\n";

// Pass 1: Update raw reputation and privacy scores for all projects
echo "Pass 1: Calculating raw scores...\n";
Project::chunk(100, function ($projects) {
    foreach ($projects as $project) {
        $project->calculateScores(); // This saves both raw and (currently) scaled WLC
    }
});

// Pass 2: Recalculate WLC Scores specifically to ensure scaling uses updated raw maximums
echo "Pass 2: Finalizing scaling against new category maximums...\n";
Project::chunk(100, function ($projects) {
    foreach ($projects as $project) {
        $project->calculateScores(); // Second pass ensures scaling is against the most up-to-date DB maxes
    }
});

echo "All scores updated successfully.\n";
