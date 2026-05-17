<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
foreach(App\Models\Category::all() as $c) {
    echo $c->name . ": " . App\Models\Project::where('category_id', $c->id)->max('reputation_score') . "/" . App\Models\Project::where('category_id', $c->id)->max('privacy_score') . "\n";
}
echo "GLOBAL: " . App\Models\Project::max('reputation_score') . "/" . App\Models\Project::max('privacy_score') . "\n";
