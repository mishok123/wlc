<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$a = \App\Models\ProjectFieldValue::whereHas('field', function($q) { $q->whereIn('key', ['supported_coin', 'supported_cryptos', 'cryptocurrency']); })->with('field')->get()->map(function($i) { return ['key'=>$i->field->key, 'val'=>$i->value]; })->toArray();

file_put_contents('output_crypto2.txt', json_encode($a));
