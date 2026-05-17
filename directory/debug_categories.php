<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$categories = App\Models\Category::with('fields')->get()->map(function($c) {
    return [
        'id' => $c->id,
        'name' => $c->name,
        'slug' => $c->slug,
        'field_count' => $c->fields->count()
    ];
});

file_put_contents('categories_debug.json', json_encode($categories, JSON_PRETTY_PRINT));
echo "Dumped to categories_debug.json";
