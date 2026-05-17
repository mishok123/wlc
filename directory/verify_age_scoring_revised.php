<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Project;
use App\Models\Field;

$project = Project::first();
$launchDateField = Field::where('key', 'launch_date')->first();

function testCase($project, $field, $months, $label) {
    $date = now()->subMonths($months)->format('Y-m-d');
    $project->fieldValues()->updateOrCreate(['field_id' => $field->id], ['value' => $date]);
    $project->calculateScores();
    echo "[$label] Date: $date | Score: {$project->reputation_score}\n";
    return $project->reputation_score;
}

echo "Testing Project: {$project->name}\n";
$s1 = testCase($project, $launchDateField, 72, "6 Years Ago");
$s2 = testCase($project, $launchDateField, 30, "2.5 Years Ago");
$s3 = testCase($project, $launchDateField, 6, "0.5 Years Ago");

echo "\nScore logic check:\n";
echo "Difference (6y vs 2.5y): " . ($s1 - $s2) . " (Expected: 100 - 60 = 40)\n";
echo "Difference (2.5y vs 0.5y): " . ($s2 - $s3) . " (Expected: 60 - 35 = 25)\n";

if (($s1 - $s2) == 40 && ($s2 - $s3) == 25) {
    echo "\nSUCCESS: Age scoring is working correctly!\n";
} else {
    echo "\nFAILURE: Score differences do not match expectations.\n";
}
