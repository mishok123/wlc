<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Field;
use App\Models\Project;

$f = Field::where('key', 'age')->first();
if ($f) {
    echo "Field: " . $f->name . "\n";
    echo "Option Scores: " . json_encode($f->option_scores, JSON_PRETTY_PRINT) . "\n";
} else {
    echo "Age field not found\n";
}

$p = Project::first();
if ($p) {
    echo "Project: " . $p->name . "\n";
    echo "Launch Date: " . $p->getDynamicField('launch_date') . "\n";
    echo "Age Value: " . $p->getDynamicField('age') . "\n";
}
