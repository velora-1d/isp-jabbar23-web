<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop in reverse order of dependency
        Schema::dropIfExists('installation_reports');
        Schema::dropIfExists('work_order_items');
        Schema::dropIfExists('work_orders');

        // 1. Work Orders
        Schema::create('work_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->string('ticket_number')->unique();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            
            $table->enum('type', ['installation', 'repair', 'dismantling', 'survey', 'maintenance'])->default('installation');
            $table->enum('status', ['pending', 'scheduled', 'on_way', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            
            $table->dateTime('scheduled_date')->nullable();
            $table->dateTime('completed_date')->nullable();
            $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('odp_id')->nullable()->constrained('odps')->nullOnDelete();
            
            $table->text('description')->nullable();
            $table->text('technician_notes')->nullable();
            $table->json('photos')->nullable();
            
            $table->timestamps();
            
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });

        // 2. Work Order Items
        Schema::create('work_order_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->uuid('work_order_id');
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->restrictOnDelete();
            $table->decimal('quantity', 10, 2);
            $table->string('unit');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('work_order_id')->references('id')->on('work_orders')->onDelete('cascade');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });

        // 3. Installation Reports
        Schema::create('installation_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->uuid('work_order_id');
            $table->foreignId('technician_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            
            $table->date('installation_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->enum('status', ['completed', 'partial', 'failed', 'rescheduled'])->default('completed');
            $table->text('work_performed');
            $table->json('equipment_used')->nullable();
            $table->text('issues_found')->nullable();
            $table->text('resolution')->nullable();
            $table->string('customer_signature')->nullable();
            $table->integer('customer_rating')->nullable();
            $table->text('customer_feedback')->nullable();
            $table->json('photos')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();

            $table->foreign('work_order_id')->references('id')->on('work_orders')->onDelete('cascade');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installation_reports');
        Schema::dropIfExists('work_order_items');
        Schema::dropIfExists('work_orders');
    }
};
