<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;
use App\Models\Field;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Identify the Mixers category
        $mixerCategory = Category::where('slug', 'mixers')->first();

        if (!$mixerCategory) {
            return; // Safety first
        }

        $mixerId = $mixerCategory->id;

        // 2. Clear out projects NOT in Mixers category
        Project::where('category_id', '!=', $mixerId)->delete();

        // 3. Clear out categories NOT Mixers
        Category::where('id', '!=', $mixerId)->delete();

        // 4. Identify field IDs associated with Mixers
        $mixerFieldIds = DB::table('category_field')
            ->where('category_id', $mixerId)
            ->pluck('field_id')
            ->toArray();

        // 5. Delete fields NOT in that list
        // Note: We might want to keep some "global" fields if they aren't linked but used...
        // but based on "keep only mixers and its fix fields", we prune.
        Field::whereNotIn('id', $mixerFieldIds)->delete();

        // 6. Cleanup orphaned pivot data (though deletion of Category/Field should handle it if cascade is on)
        DB::table('category_field')->whereNotIn('category_id', [$mixerId])->delete();
        DB::table('category_field')->whereNotIn('field_id', $mixerFieldIds)->delete();
        
        // 7. Cleanup orphaned field values
        DB::table('project_field_values')->whereNotIn('field_id', $mixerFieldIds)->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse for cleanup
    }
};
