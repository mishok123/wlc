<?php

use App\Models\Field;
use App\Models\Category;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$mixerCategory = Category::where('slug', 'mixers')->first();

if (!$mixerCategory) {
    die("Mixer category not found\n");
}

$missingFields = [
    [
        'name' => 'Tor URLs',
        'key' => 'tor_urls',
        'type' => 'textarea',
    ],
    [
        'name' => 'Positive Review Scoring',
        'key' => 'positive_review_scoring',
        'type' => 'system',
        'option_scores' => [
            '0 to 2' => ['reputation' => 35, 'privacy' => 0],
            '0 to 5' => ['reputation' => 50, 'privacy' => 0],
            '0 to 10' => ['reputation' => 65, 'privacy' => 0],
            '0 to 20' => ['reputation' => 80, 'privacy' => 0],
            '20+' => ['reputation' => 100, 'privacy' => 0],
        ],
    ],
    [
        'name' => 'Negative Review Scoring',
        'key' => 'negative_review_scoring',
        'type' => 'system',
        'option_scores' => [
            '0 to 2' => ['reputation' => 100, 'privacy' => 0],
            '0 to 5' => ['reputation' => 65, 'privacy' => 0],
            '0 to 10' => ['reputation' => 35, 'privacy' => 0],
            '0 to 20' => ['reputation' => 20, 'privacy' => 0],
            '20+' => ['reputation' => 0, 'privacy' => 0],
        ],
    ],
];

foreach ($missingFields as $fieldData) {
    $field = Field::updateOrCreate(
        ['key' => $fieldData['key']],
        $fieldData
    );

    if (!$mixerCategory->fields()->where('field_id', $field->id)->exists()) {
        $mixerCategory->fields()->attach($field->id, ['is_visible_in_card' => true, 'order' => 0]);
        echo "Added and attached field: {$fieldData['key']}\n";
    } else {
        echo "Field already exists and attached: {$fieldData['key']}\n";
    }
}
