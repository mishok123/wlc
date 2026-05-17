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
        Schema::table('project_requests', function (Blueprint $table) {
            $table->dropUnique(['project_id', 'ip_address', 'request_type']);
            $table->unique(['project_id', 'user_id', 'request_type']);
        });
    }

    public function down(): void
    {
        Schema::table('project_requests', function (Blueprint $table) {
            $table->dropUnique(['project_id', 'user_id', 'request_type']);
            $table->unique(['project_id', 'ip_address', 'request_type']);
        });
    }
};
