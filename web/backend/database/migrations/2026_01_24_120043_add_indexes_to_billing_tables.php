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
        Schema::table('invoices', function (Blueprint $table) {
            $table->index('status');
            $table->index('due_date');
            $table->index(['customer_id', 'status']); // For finding unpaid invoices for specific customer
            $table->index(['period_start', 'customer_id']); // For preventing duplicate generation
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->index('status');
            $table->index('paid_at');
            $table->index(['invoice_id', 'status']); // For checking payment status of invoice
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['due_date']);
            $table->dropIndex(['customer_id', 'status']);
            $table->dropIndex(['period_start', 'customer_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['paid_at']);
            $table->dropIndex(['invoice_id', 'status']);
        });
    }
};
