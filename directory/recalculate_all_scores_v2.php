<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Project;

$projects = Project::all();
echo "Recalculating scores for " . $projects->count() . " projects...\n";

foreach ($projects as $project) {
    echo "Processing: " . $project->name . "... ";
    $scores = $project->calculateScores();
    echo "Scaled WLC: " . $project->trust_score . "\n";
}

echo "Done.\n";
