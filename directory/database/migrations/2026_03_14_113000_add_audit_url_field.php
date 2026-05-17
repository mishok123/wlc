<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Field;
use App\Models\Category;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $field = Field::updateOrCreate(
            ['key' => 'audit_url'],
            [
                'name' => 'Audit URL',
                'key' => 'audit_url',
                'type' => 'text',
            ]
        );

        $category = Category::where('slug', 'mixers')->first();
        if ($category) {
            if (!$category->fields()->where('field_id', $field->id)->exists()) {
                $category->fields()->attach($field->id, ['is_visible_in_card' => true, 'order' => 0]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $field = Field::where('key', 'audit_url')->first();
        if ($field) {
            $field->categories()->detach();
            $field->delete();
        }
    }
};
