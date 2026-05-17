<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$col = collect(DB::select('DESCRIBE projects'))->firstWhere('Field', 'ownership_verified');
echo "Schema for 'ownership_verified':\n";
print_r($col);

$p = App\Models\Project::find(11);
echo "\nProject 11 Current Logic:\n";
echo "ownership_verified_field value: " . var_export($p->ownership_verified, true) . "\n";
echo "getDynamicField('ownership_verified') value: " . var_export($p->getDynamicField('ownership_verified'), true) . "\n";
