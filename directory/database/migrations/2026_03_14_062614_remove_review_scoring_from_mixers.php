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
            $fieldKeys = ['positive_review_scoring', 'negative_review_scoring'];
            $fieldIds = \App\Models\Field::whereIn('key', $fieldKeys)->pluck('id');
            $category->fields()->detach($fieldIds);
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
