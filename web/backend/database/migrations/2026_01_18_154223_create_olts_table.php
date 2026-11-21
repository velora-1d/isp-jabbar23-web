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
        Schema::create('olts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // OLT-HQ-01
            $table->string('ip_address')->nullable();
            $table->string('brand')->nullable(); // Huawei, ZTE, Hioso
            $table->string('type')->default('EPON'); // EPON, GPON, XGPON
            $table->integer('total_pon_ports')->default(4); // 4, 8, 16
            $table->string('location')->nullable(); // Datacenter Room A
            $table->enum('status', ['active', 'offline', 'maintenance'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('olts');
    }
};
