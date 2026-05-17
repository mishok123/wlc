<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (App\Models\Field::all() as $f) {
    echo $f->id . ": " . $f->name . " (Key: " . $f->key . ")\n";
}
