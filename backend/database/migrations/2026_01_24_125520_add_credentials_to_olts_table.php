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
        Schema::table('olts', function (Blueprint $table) {
            $table->string('username')->nullable()->after('ip_address');
            $table->string('password')->nullable()->after('username');
            $table->integer('port')->default(23)->after('password');
            $table->string('community')->nullable()->after('port'); // For SNMP
            $table->string('server_profile')->default('simulation')->after('community'); // simulation, zte, huawei
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('olts', function (Blueprint $table) {
            //
        });
    }
};
