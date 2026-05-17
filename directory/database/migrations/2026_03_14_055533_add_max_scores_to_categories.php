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
        Schema::table('categories', function (Blueprint $table) {
            $table->decimal('max_wlc', 8, 2)->default(10.00)->after('description');
            $table->decimal('max_reputation', 8, 2)->default(100.00)->after('max_wlc');
            $table->decimal('max_privacy', 8, 2)->default(100.00)->after('max_reputation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['max_wlc', 'max_reputation', 'max_privacy']);
        });
    }
};
