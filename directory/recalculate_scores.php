<?php

use App\Models\Project;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Recalculating scores for all projects...\n";

$projects = Project::all();
foreach ($projects as $project) {
    echo "Processing project: {$project->name}\n";
    $project->calculateScores();
}

echo "Done!\n";
