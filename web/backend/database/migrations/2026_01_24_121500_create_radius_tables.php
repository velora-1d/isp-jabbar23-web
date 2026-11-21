<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Implementing Standard FreeRADIUS Schema
     */
    public function up(): void
    {
        // 1. NAS (Network Access Server) - Router MikroTik
        Schema::create('nas', function (Blueprint $table) {
            $table->id();
            $table->string('nasname', 128)->index(); // IP Router
            $table->string('shortname', 32)->nullable();
            $table->string('type', 30)->default('other');
            $table->integer('ports')->nullable();
            $table->string('secret', 60); // Radius Secret
            $table->string('server', 64)->nullable();
            $table->string('community', 50)->nullable();
            $table->string('description', 200)->nullable();
            $table->timestamps();
        });

        // 2. RADCHECK (User Auth Info: Username, Password, Expiration)
        Schema::create('radcheck', function (Blueprint $table) {
            $table->id();
            $table->string('username', 64)->index();
            $table->string('attribute', 64);
            $table->string('op', 2)->default(':=');
            $table->string('value', 253);
            $table->timestamps();
            
            $table->index(['username', 'attribute']);
        });

        // 3. RADREPLY (User Specific Config: Static IP, simple queue)
        Schema::create('radreply', function (Blueprint $table) {
            $table->id();
            $table->string('username', 64)->index();
            $table->string('attribute', 64);
            $table->string('op', 2)->default('=');
            $table->string('value', 253);
            $table->timestamps();

            $table->index(['username', 'attribute']);
        });

        // 4. RADGROUPCHECK (Package Config Validation)
        Schema::create('radgroupcheck', function (Blueprint $table) {
            $table->id();
            $table->string('groupname', 64)->index();
            $table->string('attribute', 64);
            $table->string('op', 2)->default(':=');
            $table->string('value', 253);
            $table->timestamps();

            $table->index(['groupname', 'attribute']);
        });

        // 5. RADGROUPREPLY (Package Speed Limit, Pool, etc)
        Schema::create('radgroupreply', function (Blueprint $table) {
            $table->id();
            $table->string('groupname', 64)->index();
            $table->string('attribute', 64);
            $table->string('op', 2)->default('=');
            $table->string('value', 253);
            $table->timestamps();

            $table->index(['groupname', 'attribute']);
        });

        // 6. RADUSERGROUP (Assign User to Package)
        Schema::create('radusergroup', function (Blueprint $table) {
            $table->id();
            $table->string('username', 64)->index();
            $table->string('groupname', 64);
            $table->integer('priority')->default(1);
            $table->timestamps();

            $table->index(['username', 'groupname']);
        });

        // 7. RADACCT (Accounting / Traffic Logs)
        Schema::create('radacct', function (Blueprint $table) {
            $table->bigIncrements('radacctid');
            $table->string('acctsessionid', 64)->index();
            $table->string('acctuniqueid', 32)->unique();
            $table->string('username', 64)->index();
            $table->string('realm', 64)->nullable();
            $table->string('nasipaddress', 15);
            $table->string('nasportid', 15)->nullable();
            $table->string('nasporttype', 32)->nullable();
            $table->dateTime('acctstarttime')->nullable()->index();
            $table->dateTime('acctupdatetime')->nullable();
            $table->dateTime('acctstoptime')->nullable()->index();
            $table->integer('acctinterval')->nullable();
            $table->integer('acctsessiontime')->nullable();
            $table->string('acctauthentic', 32)->nullable();
            $table->string('connectinfo_start', 50)->nullable();
            $table->string('connectinfo_stop', 50)->nullable();
            $table->bigInteger('acctinputoctets')->nullable(); // Upload bytes
            $table->bigInteger('acctoutputoctets')->nullable(); // Download bytes
            $table->string('calledstationid', 50)->nullable();
            $table->string('callingstationid', 50)->nullable(); // MAC Address
            $table->string('acctterminatecause', 32)->nullable();
            $table->string('servicetype', 32)->nullable();
            $table->string('framedprotocol', 32)->nullable();
            $table->string('framedipaddress', 15)->nullable(); // User IP
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radacct');
        Schema::dropIfExists('radusergroup');
        Schema::dropIfExists('radgroupreply');
        Schema::dropIfExists('radgroupcheck');
        Schema::dropIfExists('radreply');
        Schema::dropIfExists('radcheck');
        Schema::dropIfExists('nas');
    }
};
