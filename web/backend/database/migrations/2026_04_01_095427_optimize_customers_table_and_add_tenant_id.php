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
            // Add tenant_id if not exists (Section 9.2 Rules)
            if (!Schema::hasColumn('customers', 'tenant_id')) {
                $table->uuid('tenant_id')->after('customer_id')->default('00000000-0000-0000-0000-000000000000');
                $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            }

            // Database Indexing Strategy (Section 8.1 Rules)
            $table->index('tenant_id', 'idx_customers_tenant_id');
            $table->index('status', 'idx_customers_status');
            $table->index('package_id', 'idx_customers_package_id');
            $table->index('kelurahan', 'idx_customers_kelurahan');
            $table->index('kecamatan', 'idx_customers_kecamatan');
            $table->index('assigned_to', 'idx_customers_assigned_to');
            $table->index('partner_id', 'idx_customers_partner_id');
            $table->index('router_id', 'idx_customers_router_id');
            $table->index('olt_id', 'idx_customers_olt_id');
            $table->index('created_at', 'idx_customers_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
            
            $table->dropIndex('idx_customers_tenant_id');
            $table->dropIndex('idx_customers_status');
            $table->dropIndex('idx_customers_package_id');
            $table->dropIndex('idx_customers_kelurahan');
            $table->dropIndex('idx_customers_kecamatan');
            $table->dropIndex('idx_customers_assigned_to');
            $table->dropIndex('idx_customers_partner_id');
            $table->dropIndex('idx_customers_router_id');
            $table->dropIndex('idx_customers_olt_id');
            $table->dropIndex('idx_customers_created_at');
        });
    }
};
