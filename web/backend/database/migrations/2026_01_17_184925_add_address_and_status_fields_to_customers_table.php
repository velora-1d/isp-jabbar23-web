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
        Schema::table('customers', function (Blueprint $table) {
            // Add more address fields
            $table->string('kabupaten')->nullable()->after('kecamatan');
            $table->string('provinsi')->nullable()->after('kabupaten');
            $table->string('kode_pos', 10)->nullable()->after('provinsi');
        });

        // Update status enum - need to drop and recreate
        // First, change status to string temporarily
        Schema::table('customers', function (Blueprint $table) {
            $table->string('status', 20)->default('registered')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['kabupaten', 'provinsi', 'kode_pos']);
        });
    }
};
