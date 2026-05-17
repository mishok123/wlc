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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('website_url')->nullable();
            
            // Relationships
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            
            // General Attributes
            $table->string('industry')->nullable();
            $table->string('online_status')->default('online');
            $table->json('supported_cryptos')->nullable();
            $table->json('supported_networks')->nullable();
            $table->json('features')->nullable();
            $table->json('discussion_channels')->nullable();
            $table->string('privacy_level')->nullable();
            $table->string('aml_risk_level')->nullable();
            $table->string('regulatory_status')->nullable();
            $table->string('jurisdiction')->nullable();
            $table->json('security_features')->nullable();
            
            // Ratings & Trust
            $table->integer('review_count')->default(0);
            $table->decimal('star_rating', 3, 2)->default(0.00);
            $table->integer('trust_score')->default(0);
            
            // Status & Verification
            $table->string('list_status')->default('proposed');
            $table->boolean('ownership_verified')->default(false);
            $table->boolean('registration_required')->default(false);
            $table->boolean('no_log_policy')->default(false);
            $table->boolean('source_code_availability')->default(false);
            $table->boolean('own_liquidity')->default(false);
            
            // Mixer & Exchange Specific
            $table->string('min_service_fee')->nullable(); // Changed to string to allow "%" or text
            $table->string('fixed_fee')->nullable();
            $table->integer('min_time_delay')->nullable();
            $table->boolean('letter_of_guarantee')->default(false);
            $table->string('withdrawal_fees')->nullable(); // Changed from withdrawal_fee decimal
            $table->string('liquidity_indicator')->nullable();
            
            // Wallet Specific
            $table->string('custodial_type')->nullable();
            $table->string('source_code_url')->nullable();
            
            // Scam Info
            $table->text('scam_reason')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
