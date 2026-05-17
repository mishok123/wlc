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
            $sourceField = \App\Models\Field::where('key', 'code_source')->first();
            $auditedField = \App\Models\Field::where('key', 'code_audited')->first();

            if ($sourceField) {
                $category->fields()->detach($sourceField->id);
            }

            if ($auditedField) {
                // Attach if not already attached
                if (!$category->fields()->where('fields.id', $auditedField->id)->exists()) {
                    $category->fields()->attach($auditedField->id);
                }
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
