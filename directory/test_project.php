<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (\App\Models\Project::get() as $p) {
    echo "Recalculating for project {$p->id}...\n";
    $p->calculateScores();
}
echo "Done.\n";
