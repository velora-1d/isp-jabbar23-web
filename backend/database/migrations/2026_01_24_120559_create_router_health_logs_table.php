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
        Schema::create('router_health_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('router_id')->constrained()->cascadeOnDelete();
            $table->integer('cpu_load')->default(0);      // Percentage 0-100
            $table->bigInteger('free_memory')->default(0); // Bytes
            $table->bigInteger('total_memory')->default(0); // Bytes
            $table->bigInteger('uptime_seconds')->default(0);
            $table->integer('active_pppoe')->default(0);
            $table->integer('active_hotspot')->default(0);
            $table->integer('temperature')->nullable();    // Celsius
            $table->decimal('voltage', 5, 2)->nullable();  // Volts
            $table->json('interface_traffic')->nullable(); // Snapshot TX/RX key intefaces
            $table->timestamps();

            // Indexing for graph queries
            $table->index(['router_id', 'created_at']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('router_health_logs');
    }
};
