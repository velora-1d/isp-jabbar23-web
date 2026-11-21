<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('router_id')->nullable()->after('package_id')->constrained('routers')->onDelete('set null');
            $table->string('pppoe_username')->nullable()->unique()->after('router_id');
            $table->string('pppoe_password')->nullable()->after('pppoe_username');
            $table->ipAddress('mikrotik_ip')->nullable()->after('pppoe_password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['router_id']);
            $table->dropColumn(['router_id', 'pppoe_username', 'pppoe_password', 'mikrotik_ip']);
        });
    }
};
