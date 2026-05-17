<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Project;
use App\Models\Category;

$projects = Project::with('category')->whereNotNull('category_id')->take(10)->get();

echo str_pad("Name", 20) . " | " . str_pad("Rep", 7) . " | " . str_pad("Priv", 7) . " | " . str_pad("Total", 7) . " | " . str_pad("MaxT", 7) . " | " . str_pad("Scaled", 7) . " | " . str_pad("Stored", 7) . "\n";
echo str_repeat("-", 80) . "\n";

foreach ($projects as $p) {
    if (!$p->category) continue;
    
    $maxRep = $p->category->max_reputation ?: 100;
    $maxPriv = $p->category->max_privacy ?: 100;
    $maxTotal = $maxRep + $maxPriv;
    
    $rawRep = $p->reputation_score ?? 0;
    $rawPriv = $p->privacy_score ?? 0;
    $totalRaw = $rawRep + $rawPriv;
    
    $scaledWLC = ($maxTotal > 0) ? ($totalRaw / $maxTotal) * 10 : 0;
    
    echo str_pad(substr($p->name, 0, 20), 20) . " | " . 
         str_pad($rawRep, 7) . " | " . 
         str_pad($rawPriv, 7) . " | " . 
         str_pad($totalRaw, 7) . " | " . 
         str_pad($maxTotal, 7) . " | " . 
         str_pad(number_format($scaledWLC, 2), 7) . " | " . 
         str_pad($p->trust_score, 7) . "\n";
}

echo "\nCategory Limits:\n";
$categories = Category::all();
foreach ($categories as $cat) {
    echo "{$cat->name} (slug: {$cat->slug}): Max Rep: {$cat->max_reputation}, Max Priv: {$cat->max_privacy}, Max WLC: {$cat->max_wlc}\n";
}
