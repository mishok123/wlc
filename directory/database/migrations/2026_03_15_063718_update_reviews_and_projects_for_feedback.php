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
        Schema::table('reviews', function (Blueprint $table) {
            $table->enum('sentiment', ['positive', 'neutral', 'negative'])->after('rating')->nullable();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedInteger('positive_count')->default(0)->after('review_count');
            $table->unsignedInteger('neutral_count')->default(0)->after('positive_count');
            $table->unsignedInteger('negative_count')->default(0)->after('neutral_count');
        });

        // Data Migration: Map existing star ratings to sentiments
        $reviews = DB::table('reviews')->get();
        foreach ($reviews as $review) {
            $sentiment = 'neutral';
            if ($review->rating >= 4) {
                $sentiment = 'positive';
            } elseif ($review->rating <= 2) {
                $sentiment = 'negative';
            }

            DB::table('reviews')->where('id', $review->id)->update(['sentiment' => $sentiment]);
        }

        // Update Project aggregated counts
        $projects = DB::table('projects')->get();
        foreach ($projects as $project) {
            $pos = DB::table('reviews')->where('project_id', $project->id)->where('sentiment', 'positive')->count();
            $neu = DB::table('reviews')->where('project_id', $project->id)->where('sentiment', 'neutral')->count();
            $neg = DB::table('reviews')->where('project_id', $project->id)->where('sentiment', 'negative')->count();

            DB::table('projects')->where('id', $project->id)->update([
                'positive_count' => $pos,
                'neutral_count' => $neu,
                'negative_count' => $neg,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['positive_count', 'neutral_count', 'negative_count']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('sentiment');
        });
    }
};
