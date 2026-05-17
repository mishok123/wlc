<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Project;
use App\Models\Field;

$ageField = Field::where('key', 'age')->first();

echo "Verifying Privacy score for 'Age 2+ year' bracket...\n";

// Find or create a project with Age 2+ year
$project = Project::first();
$date2YearsAgo = now()->subMonths(30)->format('Y-m-d');
$project->fieldValues()->updateOrCreate(['field_id' => $ageField->id], ['value' => 'Age 2+ year']); // Manually setting bracket for verification
// Wait, my automate logic uses launch_date.
$launchDateField = Field::where('key', 'launch_date')->first();
$project->fieldValues()->updateOrCreate(['field_id' => $launchDateField->id], ['value' => $date2YearsAgo]);

$project->calculateScores();

echo "Project: {$project->name}\n";
echo "Launch Date: $date2YearsAgo\n";
echo "Calculated Privacy Score: {$project->privacy_score}\n";

// In my seeder check, Privacy was 0 for 6y and 0.5y.
// So if privacy_score > 0, it means the 20 points were added (assuming no other privacy-giving fields)
// Let's check the score diff.

$date05YearsAgo = now()->subMonths(6)->format('Y-m-d');
$project->fieldValues()->updateOrCreate(['field_id' => $launchDateField->id], ['value' => $date05YearsAgo]);
$project->calculateScores();
$p1 = $project->privacy_score;
echo "Privacy Score (0.5y): $p1\n";

$project->fieldValues()->updateOrCreate(['field_id' => $launchDateField->id], ['value' => $date2YearsAgo]);
$project->calculateScores();
$p2 = $project->privacy_score;
echo "Privacy Score (2.5y): $p2\n";

echo "Difference: " . ($p2 - $p1) . " (Expected: 20)\n";
