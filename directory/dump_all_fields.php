<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request = Illuminate\Http\Request::capture());

$fields = \App\Models\Field::all();
foreach ($fields as $field) {
    echo "ID: {$field->id} | Name: {$field->name} | Type: {$field->type} | Options: " . json_encode($field->options) . "\n";
}
