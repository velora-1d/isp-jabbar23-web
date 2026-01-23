<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->enum('payment_method', ['cash', 'bank_transfer', 'qris', 'va', 'ewallet', 'cc', 'other'])->default('cash');
            $table->string('reference_number')->nullable();
            $table->timestamp('paid_at');
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['pending', 'verified', 'confirmed', 'rejected', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->string('proof_file')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
