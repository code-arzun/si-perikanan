<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Usaha / Pemilik
            $table->string('phone_or_email')->unique(); // Untuk registrasi cepat
            $table->enum('status', ['trial', 'active', 'suspended'])->default('trial');
            
            // Profil Detail Usaha (Nullable untuk perorangan)
            $table->enum('tenant_type', ['individual', 'corporate'])->default('individual');
            $table->string('identity_number')->nullable(); // NIK (Perorangan) / NPWP (Perusahaan)
            $table->text('address')->nullable();
            $table->string('logo_path')->nullable();
            
            // Info Rekening Bank
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_name')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};