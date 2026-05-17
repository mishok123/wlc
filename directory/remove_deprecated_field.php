<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Field;

echo "Cleaning up deprecated fields...\n";

$deprecated = Field::where('key', 'user_avg_rating_scoring')->first();
if ($deprecated) {
    // This will delete it from category_field pivot because of cascade (or should we manually detach?)
    // Let's detach first to be safe
    $deprecated->categories()->detach();
    // Delete values from project_field_values if any exist for this field
    \DB::table('project_field_values')->where('field_id', $deprecated->id)->delete();
    $deprecated->delete();
    echo "Deleted 'user_avg_rating_scoring' field.\n";
} else {
    echo "'user_avg_rating_scoring' not found.\n";
}

echo "Done!\n";
