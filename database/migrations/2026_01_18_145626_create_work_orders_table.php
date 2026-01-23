<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Work Orders (Surat Perintah Kerja)
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique(); // WO-202401-001
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            
            // Tipe Pekerjaan
            $table->enum('type', ['installation', 'repair', 'dismantling', 'survey', 'maintenance'])->default('installation');
            
            // Status Workflow
            $table->enum('status', ['pending', 'scheduled', 'on_way', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            
            // Jadwal & Teknisi
            $table->dateTime('scheduled_date')->nullable();
            $table->dateTime('completed_date')->nullable();
            $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete(); // Lead Tech
            
            // Detail
            $table->text('description')->nullable();
            $table->text('technician_notes')->nullable(); // Catatan teknisi setelah kerja
            $table->json('photos')->nullable(); // Foto sebelum/sesudah (array paths)
            
            $table->timestamps();
        });

        // 2. Work Order Items (Material Usage) - Barang yang dipakai
        Schema::create('work_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->restrictOnDelete();
            $table->decimal('quantity', 10, 2);
            $table->string('unit');
            $table->text('notes')->nullable(); // SN Modem, dll
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_order_items');
        Schema::dropIfExists('work_orders');
    }
};
