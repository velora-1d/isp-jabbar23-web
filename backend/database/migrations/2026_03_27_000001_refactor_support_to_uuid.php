<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Drop Foreign Keys first
        Schema::table('sla_breaches', function (Blueprint $table) {
            $table->dropForeign(['sla_policy_id']);
            $table->dropForeign(['ticket_id']);
        });

        // 2. Add UUID and Tenant columns
        Schema::table('knowledge_base_articles', function (Blueprint $table) {
            $table->uuid('uuid_id')->after('id')->nullable();
            $table->uuid('tenant_id')->after('uuid_id')->nullable()->index();
        });

        Schema::table('sla_policies', function (Blueprint $table) {
            $table->uuid('uuid_id')->after('id')->nullable();
            $table->uuid('tenant_id')->after('uuid_id')->nullable()->index();
        });

        Schema::table('sla_breaches', function (Blueprint $table) {
            $table->uuid('uuid_id')->after('id')->nullable();
            $table->uuid('tenant_id')->after('uuid_id')->nullable()->index();
            $table->uuid('uuid_sla_policy_id')->after('sla_policy_id')->nullable();
            $table->uuid('uuid_ticket_id')->after('ticket_id')->nullable();
        });

        // Populate UUIDs (using gen_random_uuid() from pgcrypto)
        DB::statement('UPDATE knowledge_base_articles SET uuid_id = gen_random_uuid() WHERE uuid_id IS NULL');
        DB::statement('UPDATE sla_policies SET uuid_id = gen_random_uuid() WHERE uuid_id IS NULL');
        DB::statement('UPDATE sla_breaches SET uuid_id = gen_random_uuid() WHERE uuid_id IS NULL');

        // 4. Swap IDs for knowledge_base_articles
        Schema::table('knowledge_base_articles', function (Blueprint $table) {
            $table->dropColumn('id');
        });
        Schema::table('knowledge_base_articles', function (Blueprint $table) {
            $table->renameColumn('uuid_id', 'id');
        });
        DB::statement('ALTER TABLE knowledge_base_articles ADD PRIMARY KEY (id)');

        // 5. Swap IDs for sla_policies
        Schema::table('sla_policies', function (Blueprint $table) {
            $table->dropColumn('id');
        });
        Schema::table('sla_policies', function (Blueprint $table) {
            $table->renameColumn('uuid_id', 'id');
        });
        DB::statement('ALTER TABLE sla_policies ADD PRIMARY KEY (id)');

        // 6. Swap IDs and FKs for sla_breaches
        Schema::table('sla_breaches', function (Blueprint $table) {
            $table->dropColumn(['id', 'sla_policy_id', 'ticket_id']);
        });
        Schema::table('sla_breaches', function (Blueprint $table) {
            $table->renameColumn('uuid_id', 'id');
            $table->renameColumn('uuid_sla_policy_id', 'sla_policy_id');
            $table->renameColumn('uuid_ticket_id', 'ticket_id');
        });
        DB::statement('ALTER TABLE sla_breaches ADD PRIMARY KEY (id)');

        // 7. Re-add Foreign Keys (Optional but recommended if tables use UUID)
        // Note: For now we'll just index them as the targets (tickets) might still be bigint.
        Schema::table('sla_breaches', function (Blueprint $table) {
            $table->index('sla_policy_id');
            $table->index('ticket_id');
        });
    }

    public function down(): void
    {
    }
};
