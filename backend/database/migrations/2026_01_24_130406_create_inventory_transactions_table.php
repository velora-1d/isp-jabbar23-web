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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_item_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('inventory_serial_id')->nullable();
            $table->enum('type', ['in', 'out', 'transfer', 'adjustment']);
            $table->decimal('quantity', 15, 2);
            $table->string('reference_no')->nullable(); // PO Number, Sales Invoice, etc.
            $table->foreignId('user_id')->constrained(); // Who performed the action
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('inventory_serial_id')->references('id')->on('inventory_serials')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
