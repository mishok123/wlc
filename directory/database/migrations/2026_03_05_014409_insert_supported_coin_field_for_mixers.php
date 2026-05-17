<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $field = \App\Models\Field::create([
            'name' => 'Supported Coin',
            'key' => 'supported_coin',
            'type' => 'text',
        ]);

        $category = \App\Models\Category::where('slug', 'mixers')->first();
        if ($category) {
            $category->fields()->attach($field->id, ['is_visible_in_card' => true, 'order' => 0]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $field = \App\Models\Field::where('key', 'supported_coin')->first();
        if ($field) {
            $field->categories()->detach();
            $field->delete();
        }
    }
};
