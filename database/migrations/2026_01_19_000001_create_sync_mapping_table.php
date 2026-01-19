<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sync_mapping')) {
            Schema::create('sync_mapping', function (Blueprint $table) {
                $table->id();
                $table->string('erp_customer_id', 100)->comment('ID Customer di ERPNext');
                $table->string('radius_username', 100)->comment('Username PPPoE di RADIUS');
                $table->string('inventory_device_sn', 100)->nullable()->comment('Serial Number Modem');
                $table->enum('status', ['ACTIVE', 'SUSPENDED', 'TERMINATED'])->default('ACTIVE');
                $table->datetime('last_synced_at')->nullable();
                $table->timestamps();
                
                $table->unique(['erp_customer_id', 'radius_username'], 'unique_map');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sync_mapping');
    }
};
