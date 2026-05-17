<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Project;

$p = Project::find(11);
if (!$p) {
    echo "Project not found\n";
    exit;
}

echo "Project 11 (" . $p->name . "):\n";
echo "Model ownership_verified column (bool): " . ($p->ownership_verified ? 'TRUE' : 'FALSE') . "\n";
echo "Dynamic field 'ownership_verified' value: " . $p->getDynamicField('ownership_verified') . "\n";
