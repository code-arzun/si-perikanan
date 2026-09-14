<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update Tabel Users: Tambah username, ubah email jadi nullable, phone jadi required
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('tenant_id');
            $table->string('email')->nullable()->change();
            $table->string('phone')->unique()->change();
        });

        // 2. Update Tabel Tenants: Nama usaha (name) & phone_or_email jadi nullable
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('phone_or_email')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->string('email')->nullable(false)->change();
            $table->string('phone')->nullable()->change();
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
            $table->string('phone_or_email')->nullable(false)->change();
        });
    }
};