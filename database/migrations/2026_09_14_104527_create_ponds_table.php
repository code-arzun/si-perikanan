<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ponds', function (Blueprint $table) {
            $table->id();
            // Isolasi Tenant
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();

            // Identitas Kolam
            $table->string('code')->comment('Kode Unik Kolam, misal: K-01');
            $table->string('name')->comment('Nama Kolam, misal: Kolam Bioflok 1');
            // $table->enum('shape', ['circle', 'rectangle', 'oval', 'other'])->default('circle');
            $table->enum('shape', ['bulat', 'persegi', 'persegi panjang', 'lainnya'])->default('bulat');

            // Dimensi (Meter)
            $table->decimal('length', 8, 2)->nullable()->comment('Panjang (m) jika persegi');
            $table->decimal('width', 8, 2)->nullable()->comment('Lebar (m) jika persegi');
            $table->decimal('diameter', 8, 2)->nullable()->comment('Diameter (m) jika bulat');
            $table->decimal('depth', 8, 2)->comment('Kedalaman air rata-rata (m)');
            
            // Volume Air (m3 atau Liter)
            $table->decimal('volume_m3', 10, 2)->comment('Kapasitas Volume Air dalam m3');

            // Master data jenis kolam & Status
            $table->foreignId('pond_type_id')->nullable()->constrained('pond_types')->nullOnDelete();
            // $table->enum('status', ['idle', 'active', 'maintenance'])->default('idle');
            $table->enum('status', ['kosong', 'aktif', 'perawatan'])->default('kosong');

            $table->timestamps();
            $table->softDeletes();

            // Indexing & Uniqueness per tenant
            $table->unique(['tenant_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ponds');
    }
};