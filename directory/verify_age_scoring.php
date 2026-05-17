<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Project;
use App\Models\Field;
use Illuminate\Support\Carbon;

$project = Project::first();
if (!$project) {
    die("No project found for testing.\n");
}

$launchDateField = Field::where('key', 'launch_date')->first();
$ageField = Field::where('key', 'age')->first();

if (!$launchDateField || !$ageField) {
    die("Required fields (launch_date or age) not found in database.\n");
}

echo "Testing Project: {$project->name}\n";

function setLaunchDate($project, $field, $date) {
    $project->fieldValues()->updateOrCreate(
        ['field_id' => $field->id],
        ['value' => $date]
    );
}

// Ensure age field value is cleared to avoid manual interference (though we added a skip in the loop)
$project->fieldValues()->where('field_id', $ageField->id)->delete();

// Case 1: 6 years ago (Age 5 and over)
echo "\nSetting launch date to 6 years ago...\n";
setLaunchDate($project, $launchDateField, now()->subYears(6)->format('Y-m-d'));
$project->calculateScores();
echo "Calculated Reputation Score: {$project->reputation_score}\n";
// We expect a certain score. Since there are other fields, we just check the bracket calculation indirectly.
// Actually, we can check the returned scores from calculateScores() if it returned the break down, but it returns total.
// Let's check if it's consistent.

// Case 2: 2.5 years ago (Age 2+ year)
echo "\nSetting launch date to 2.5 years ago...\n";
setLaunchDate($project, $launchDateField, now()->subMonths(30)->format('Y-m-d')); // 2.5 years
$project->calculateScores();
echo "Calculated Reputation Score: {$project->reputation_score}\n";

// Case 3: 0.5 years ago (Age 0-1year)
echo "\nSetting launch date to 0.5 years ago...\n";
setLaunchDate($project, $launchDateField, now()->subMonths(6)->format('Y-m-d'));
$project->calculateScores();
echo "Calculated Reputation Score: {$project->reputation_score}\n";

echo "\nVerification complete. Please check if scores changed as expected based on seeder points:\n";
echo "Age 5 and over: 100\n";
echo "Age 2+ year: 60\n";
echo "Age 0-1year: 35\n";
