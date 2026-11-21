<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('referrer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('referred_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->enum('status', ['pending', 'qualified', 'rewarded', 'expired'])->default('pending');
            $table->decimal('reward_amount', 12, 2)->default(0);
            $table->boolean('reward_paid')->default(false);
            $table->timestamp('qualified_at')->nullable();
            $table->timestamp('rewarded_at')->nullable();
            $table->timestamps();
            
            $table->index('code');
            $table->index('referrer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
