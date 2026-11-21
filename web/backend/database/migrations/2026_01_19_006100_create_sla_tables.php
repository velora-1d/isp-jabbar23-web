<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sla_policies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->integer('first_response_hours');
            $table->integer('resolution_hours');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('sla_breaches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sla_policy_id')->constrained()->onDelete('cascade');
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->enum('breach_type', ['first_response', 'resolution']);
            $table->timestamp('due_at');
            $table->timestamp('breached_at')->nullable();
            $table->boolean('is_breached')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sla_breaches');
        Schema::dropIfExists('sla_policies');
    }
};
