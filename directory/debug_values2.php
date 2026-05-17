<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$kyc = \App\Models\ProjectFieldValue::whereHas('field', function($q) { $q->where('key', 'privacy_kyc'); })->select('value')->distinct()->get()->pluck('value');
$crypto = \App\Models\ProjectFieldValue::whereHas('field', function($q) { $q->where('key', 'cryptocurrency'); })->select('value')->distinct()->get()->pluck('value');

$output = "privacy_kyc: " . json_encode($kyc) . "\n";
$output .= "cryptocurrency: " . json_encode($crypto) . "\n";
file_put_contents('output_values2.txt', $output);
