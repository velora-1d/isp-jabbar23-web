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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->unique();              // ID Pelanggan (auto-generated)
            $table->string('name');                               // Nama lengkap
            $table->string('phone')->nullable();                  // Nomor HP
            $table->string('email')->nullable();                  // Email (optional)
            $table->text('address');                              // Alamat lengkap
            $table->string('rt_rw')->nullable();                  // RT/RW
            $table->string('kelurahan')->nullable();              // Kelurahan
            $table->string('kecamatan')->nullable();              // Kecamatan
            $table->decimal('latitude', 10, 8)->nullable();       // Koordinat GPS
            $table->decimal('longitude', 11, 8)->nullable();      // Koordinat GPS
            $table->foreignId('package_id')->constrained()->onDelete('restrict'); // Paket yang dipilih
            $table->enum('status', ['pending', 'active', 'suspended', 'terminated'])->default('pending');
            $table->date('installation_date')->nullable();        // Tanggal pemasangan
            $table->date('billing_date')->nullable();             // Tanggal tagihan
            $table->text('notes')->nullable();                    // Catatan tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
