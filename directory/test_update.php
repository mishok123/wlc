<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Project;

$p = Project::find(11);
echo "Before: ownership_verified = " . var_export($p->ownership_verified, true) . "\n";

$p->update(['ownership_verified' => true]);

$p->refresh();
echo "After: ownership_verified = " . var_export($p->ownership_verified, true) . "\n";
