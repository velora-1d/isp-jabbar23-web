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
            // Employee Identification
            $table->string('nik', 50)->nullable()->after('email')->comment('Nomor Induk Karyawan');
            
            // Personal Information
            $table->text('address')->nullable()->after('phone')->comment('Alamat Lengkap');
            $table->date('date_of_birth')->nullable()->after('address')->comment('Tanggal Lahir');
            $table->enum('gender', ['male', 'female'])->nullable()->after('date_of_birth')->comment('Jenis Kelamin');
            
            // Employment Information
            $table->string('position', 100)->nullable()->after('gender')->comment('Jabatan/Posisi');
            $table->string('department', 100)->nullable()->after('position')->comment('Divisi/Departemen');
            $table->date('join_date')->nullable()->after('department')->comment('Tanggal Bergabung');
            
            // Contact Information
            $table->string('emergency_contact_name', 100)->nullable()->after('join_date')->comment('Nama Kontak Darurat');
            $table->string('emergency_contact_phone', 20)->nullable()->after('emergency_contact_name')->comment('No HP Kontak Darurat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nik',
                'address',
                'date_of_birth',
                'gender',
                'position',
                'department',
                'join_date',
                'emergency_contact_name',
                'emergency_contact_phone'
            ]);
        });
    }
};
