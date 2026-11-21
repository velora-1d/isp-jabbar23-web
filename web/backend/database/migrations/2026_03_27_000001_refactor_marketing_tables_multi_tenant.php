<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop existing to recreate with UUID and tenant_id (Tables are empty as verified)
        Schema::dropIfExists('referrals');
        Schema::dropIfExists('promotions');

        Schema::create('promotions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->string('name');
            $table->string('code');
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed', 'free_month'])->default('percentage');
            $table->decimal('value', 10, 2);
            $table->decimal('min_purchase', 12, 2)->nullable();
            $table->decimal('max_discount', 12, 2)->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('usage_count')->default(0);
            $table->integer('per_customer_limit')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->json('applicable_packages')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['tenant_id', 'code']);
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });

        Schema::create('referrals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->string('code');
            $table->unsignedBigInteger('referrer_id'); // Assuming customers still use BigInt id for now, otherwise change to uuid
            $table->unsignedBigInteger('referred_id')->nullable();
            $table->enum('status', ['pending', 'qualified', 'rewarded', 'expired'])->default('pending');
            $table->decimal('reward_amount', 12, 2)->default(0);
            $table->boolean('reward_paid')->default(false);
            $table->timestamp('qualified_at')->nullable();
            $table->timestamp('rewarded_at')->nullable();
            $table->timestamps();
            
            $table->unique(['tenant_id', 'code']);
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('referrer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('referred_id')->references('id')->on('customers')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
        Schema::dropIfExists('promotions');
    }
};
