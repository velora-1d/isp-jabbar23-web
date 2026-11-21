<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('installation_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('technician_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
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
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installation_reports');
    }
};
