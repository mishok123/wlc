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
        $category = \App\Models\Category::where('slug', 'mixers')->first();
        if ($category) {
            $field = \App\Models\Field::where('key', 'customer_support_rating')->first();
            if ($field) {
                $category->fields()->detach($field->id);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Detach is destructive.
    }
};
