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
        $tables = ['users', 'packages', 'olts', 'routers', 'partners'];
        $defaultTenantId = '00000000-0000-0000-0000-000000000000';

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName, $defaultTenantId) {
                if (!Schema::hasColumn($tableName, 'tenant_id')) {
                    $table->uuid('tenant_id')->after('id')->default($defaultTenantId);
                    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
                    $table->index('tenant_id', "idx_{$tableName}_tenant_id");
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['users', 'packages', 'olts', 'routers', 'partners'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->dropForeign(['tenant_id']);
                $table->dropColumn('tenant_id');
            });
        }
    }
};
