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
        Schema::table('users', function (Blueprint $table) {
            // is_active: false = off duty / tidak aktif
            $table->boolean('is_active')->default(true)->after('phone');
            
            // Remove the manual technician_status and current_location if they exist
            if (Schema::hasColumn('users', 'technician_status')) {
                $table->dropColumn('technician_status');
            }
            if (Schema::hasColumn('users', 'current_location')) {
                $table->dropColumn('current_location');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_active');
            $table->string('technician_status', 20)->nullable();
            $table->string('current_location')->nullable();
        });
    }
};
