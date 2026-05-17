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
                'name' => 'Liquidity Amount (USD)',
                'key' => 'liquidity_amount',
                'type' => 'number',
            ],
            [
                'name' => 'Liquidity Proof URL',
                'key' => 'liquidity_proof_url',
                'type' => 'text',
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
        $keys = ['liquidity_amount', 'liquidity_proof_url'];
        $fields = Field::whereIn('key', $keys)->get();

        foreach ($fields as $field) {
            $field->categories()->detach();
            $field->delete();
        }
    }
};
