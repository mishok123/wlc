<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Field;
use App\Models\Category;

// 1. Restore tor_urls
$torUrls = Field::updateOrCreate(
    ['key' => 'tor_urls'],
    [
        'name' => 'Tor URLs',
        'key' => 'tor_urls',
        'type' => 'textarea',
    ]
);
$mixerCategory = Category::where('slug', 'mixers')->first();
if ($mixerCategory && !$mixerCategory->fields()->where('field_id', $torUrls->id)->exists()) {
    $mixerCategory->fields()->attach($torUrls->id, ['is_visible_in_card' => true, 'order' => 0]);
}

// 2. Restore others (from FieldSeeder)
$otherFields = [
    [
        'name' => 'Code Source',
        'key' => 'code_source',
        'type' => 'select',
        'options' => 'Open Source,Closed Source',
        'industries' => ['mixers', 'cex', 'dex', 'instant-exchange-and-aggregator', 'sportsbook', 'casino', 'wallets', 'merchant', 'crypto-cards'],
    ],
    [
        'name' => 'BOT',
        'key' => 'bot',
        'type' => 'checkbox',
        'industries' => ['mixers', 'cex', 'dex', 'instant-exchange-and-aggregator', 'sportsbook', 'casino', 'wallets', 'merchant', 'crypto-cards'],
    ],
    [
        'name' => 'API',
        'key' => 'api',
        'type' => 'checkbox',
        'industries' => ['mixers', 'cex', 'dex', 'instant-exchange-and-aggregator', 'sportsbook', 'casino', 'wallets', 'merchant', 'crypto-cards'],
    ],
];

foreach ($otherFields as $data) {
    $industries = $data['industries'];
    unset($data['industries']);
    $field = Field::updateOrCreate(['key' => $data['key']], $data);
    foreach ($industries as $slug) {
        $cat = Category::where('slug', $slug)->first();
        if ($cat && !$cat->fields()->where('field_id', $field->id)->exists()) {
            $cat->fields()->attach($field->id, ['is_visible_in_card' => true, 'order' => 0]);
        }
    }
}
echo "Restored fields successfully.\n";
