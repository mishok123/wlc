<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Project;

$projects = Project::whereHas('fieldValues', function($q) {
    $q->whereHas('field', function($f) {
        $f->where('name', 'Have Tor');
    })->where('value', 'Yes');
})->get();

echo "Projects with 'Have Tor' = 'Yes': " . $projects->count() . "\n";

foreach ($projects as $p) {
    echo "ID: {$p->id}, Name: {$p->name}\n";
    
    $getVal = function ($name) use ($p) {
        $v = $p->fieldValues->first(function ($v) use ($name) {
            return $v->field && strtolower($v->field->name) === strtolower($name);
        });
        return $v ? $v->value : null;
    };

    $hasTor = ($getVal('Have Tor') === 'Yes') || (!empty($p->supported_networks['tor']));
    $torUrl = $getVal('Tor URLs') ?? $p->supported_networks['tor'] ?? null;
    
    echo "  - hasTor logic result: " . ($hasTor ? 'TRUE' : 'FALSE') . "\n";
    echo "  - torUrl logic result: " . ($torUrl ?: 'NULL') . "\n";
    echo "--------------------------\n";
}
