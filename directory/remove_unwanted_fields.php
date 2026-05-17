<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Field;

echo "Cleaning up unwanted fields...\n";

$keysToRemove = [
    'tor_urls',
    'api',
    'bot',
    'code_source'
];

$deletedCount = Field::whereIn('key', $keysToRemove)->delete();

echo "Successfully deleted $deletedCount fields from the database.\n";
