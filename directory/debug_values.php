<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$kyc = \App\Models\ProjectFieldValue::whereHas('field', function($q) { $q->where('key', 'privacy_kyc'); })->select('value')->distinct()->get()->pluck('value');
$crypto = \App\Models\ProjectFieldValue::whereHas('field', function($q) { $q->where('key', 'cryptocurrency'); })->select('value')->distinct()->get()->pluck('value');

echo "privacy_kyc: " . json_encode($kyc) . "\n";
echo "cryptocurrency: " . json_encode($crypto) . "\n";
