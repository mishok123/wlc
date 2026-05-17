<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Field;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $keepKeys = [
            'age', 
            'community', 
            'guarantee_fund', 
            'privacy_kyc', 
            'fee', 
            'log_verifiable', 
            'have_tor', 
            'no_reg_policy', 
            'no_log_policy_field', 
            'own_liquidity_field', 
            'code_audited', 
            'ownership_verified', 
            'potential_risk', 
            'supported_coin', 
            'launch_date', 
            'fee_min', 
            'fee_max', 
            'fee_fixed', 
            'tor_urls', 
            'liquidity_amount', 
            'liquidity_proof_url', 
            'audit_url', 
            'cryptocurrency', 
            'user_avg_rating_scoring', 
            'list_status_scoring', 
            'ownership_verified_scoring', 
            'positive_review_scoring', 
            'negative_review_scoring'
        ];

        // Delete fields not in the keep list
        Field::whereNotIn('key', $keepKeys)->delete();

        // Cleanup project_field_values for deleted fields
        // First get existing field IDs
        $validFieldIds = Field::pluck('id')->toArray();
        DB::table('project_field_values')->whereNotIn('field_id', $validFieldIds)->delete();
        
        // Final cleanup of category_field just in case
        DB::table('category_field')->whereNotIn('field_id', $validFieldIds)->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse for pruning
    }
};
