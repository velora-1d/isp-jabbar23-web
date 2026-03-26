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
            // Technician availability status
            $table->string('technician_status', 20)->nullable()->after('remember_token');
            
            // Phone number for technicians
            $table->string('phone', 20)->nullable()->after('email');
            
            // Current location/notes 
            $table->string('current_location')->nullable()->after('technician_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['technician_status', 'phone', 'current_location']);
        });
    }
};
