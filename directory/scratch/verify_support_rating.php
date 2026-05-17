<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Project;

$projects = Project::whereHas('fieldValues', function($q) {
    $q->whereHas('field', function($f) {
        $f->where('key', 'customer_support_rating');
    });
})->get();

echo "Projects with Support Rating: " . $projects->count() . "\n";

foreach ($projects as $p) {
    echo "ID: {$p->id}, Name: {$p->name}\n";
    
    $getVal = function ($name) use ($p) {
        $v = $p->fieldValues->first(function ($v) use ($name) {
            return $v->field && strtolower($v->field->name) === strtolower($name);
        });
        return $v ? $v->value : null;
    };

    $supportRatingName = $getVal('Customer Support Rating');
    $supportRatingKey = $p->getDynamicField('customer_support_rating');
    
    echo "  - getVal('Customer Support Rating'): " . ($supportRatingName ?: 'NULL') . "\n";
    echo "  - getDynamicField('customer_support_rating'): " . ($supportRatingKey ?: 'NULL') . "\n";
    echo "--------------------------\n";
}
