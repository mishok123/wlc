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
            $fieldKeys = ['withdrawal_delay', 'phone_required', 'legally_registered'];
            $fieldIds = \App\Models\Field::whereIn('key', $fieldKeys)->pluck('id');
            $category->fields()->detach($fieldIds);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No easy reverse for detaching fields manually, but we can re-attach if needed.
        // For now, keep empty as it's a cleanup.
    }
};
