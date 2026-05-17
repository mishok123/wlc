<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Field;
use App\Models\Project;

// 1. Update the Age field's option_scores in database
$ageField = Field::where('key', 'age')->first();
if ($ageField) {
    $scores = $ageField->option_scores;
    if (isset($scores['Age 2+ year'])) {
        $scores['Age 2+ year']['privacy'] = 20;
        $ageField->option_scores = $scores;
        $ageField->save();
        echo "Updated database scoring for 'Age 2+ year' (Privacy set to 20).\n";
    } else {
        echo "Could not find 'Age 2+ year' bracket in database.\n";
    }
} else {
    echo "Age field not found in database.\n";
}

// 2. Recalculate scores for all projects
echo "Recalculating scores for all projects...\n";
$projects = Project::all();
foreach ($projects as $project) {
    $project->calculateScores();
    echo "Recalculated scores for: {$project->name}\n";
}

echo "Done.\n";
