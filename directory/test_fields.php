<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (App\Models\Field::get() as $f) {
    if (str_contains($f->key, 'rating') || str_contains(strtolower($f->name), 'rating') || str_contains($f->key, 'support') || str_contains(strtolower($f->name), 'support')) {
        echo $f->id . '. ' . $f->name . ' (' . $f->key . ")\n";
    }
}
