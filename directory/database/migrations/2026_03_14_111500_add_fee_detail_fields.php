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
        $fields = [
            [
                'name' => 'Fees Min',
                'key' => 'fee_min',
                'type' => 'number',
            ],
            [
                'name' => 'Fees Max',
                'key' => 'fee_max',
                'type' => 'number',
            ],
            [
                'name' => 'Fees Fixed',
                'key' => 'fee_fixed',
                'type' => 'number',
            ],
        ];

        $category = Category::where('slug', 'mixers')->first();

        foreach ($fields as $fieldData) {
            $field = Field::updateOrCreate(
                ['key' => $fieldData['key']],
                $fieldData
            );

            if ($category) {
                if (!$category->fields()->where('field_id', $field->id)->exists()) {
                    $category->fields()->attach($field->id, ['is_visible_in_card' => true, 'order' => 0]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $keys = ['fee_min', 'fee_max', 'fee_fixed'];
        $fields = Field::whereIn('key', $keys)->get();

        foreach ($fields as $field) {
            $field->categories()->detach();
            $field->delete();
        }
    }
};
