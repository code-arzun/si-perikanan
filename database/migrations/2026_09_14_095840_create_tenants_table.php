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
            $table->enum('status', ['uji coba', 'aktif', 'masa tenggang', 'diblokir'])->default('uji coba');
            // $table->boolean('is_active')->default(true); // Status aktif/inaktif tenant
            
            // Profil Detail Usaha (Nullable untuk perorangan)
            $table->enum('tenant_type', ['perorangan', 'perusahaan'])->default('perorangan');
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