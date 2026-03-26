<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('direction', ['inbound', 'outbound'])->default('inbound');
            $table->enum('channel', ['whatsapp', 'sms', 'email', 'web'])->default('whatsapp');
            $table->text('content');
            $table->enum('status', ['sent', 'delivered', 'read', 'failed'])->default('sent');
            $table->string('external_id')->nullable(); // For gateway reference
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index('customer_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
