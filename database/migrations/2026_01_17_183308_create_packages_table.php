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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // Nama paket, e.g. "Paket Hemat 10 Mbps"
            $table->integer('speed_down')->default(0);       // Download speed in Mbps
            $table->integer('speed_up')->default(0);         // Upload speed in Mbps
            $table->decimal('price', 12, 2)->default(0);     // Harga per bulan
            $table->text('description')->nullable();         // Deskripsi paket
            $table->boolean('is_active')->default(true);     // Status aktif/nonaktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
