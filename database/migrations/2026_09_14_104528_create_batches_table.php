<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            // Isolasi Tenant
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            
            // Relasi ke Kolam & Spesies Ikan
            $table->foreignId('pond_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fish_species_id')->constrained('fish_species')->cascadeOnDelete();

            // Identitas Batch / Siklus
            $table->string('batch_code')->comment('Kode Siklus, misal: BATCH-2026-001');
            $table->date('start_date')->comment('Tanggal Tebar Bibit');
            $table->date('estimated_harvest_date')->nullable()->comment('Estimasi Tanggal Panen');
            $table->date('actual_harvest_date')->nullable()->comment('Tanggal Realisasi Panen');

            // Data Tebar Awal
            $table->unsignedInteger('initial_seed_count')->comment('Jumlah Tebar (Ekor)');
            $table->decimal('initial_avg_weight_g', 8, 2)->comment('MBW / Bobot Rata-rata Awal (Gram)');
            $table->decimal('initial_total_weight_kg', 10, 2)->comment('Total Biomasa Awal (Kg)');
            $table->decimal('seed_price_per_unit', 10, 2)->default(0)->comment('Harga Beli Per Ekor (Rp)');

            // Target Performa
            $table->decimal('target_fcr', 4, 2)->default(1.20)->comment('Target Feed Conversion Ratio');
            $table->decimal('target_survival_rate', 5, 2)->default(85.00)->comment('Target SR (%)');

            // Status Siklus
            // $table->enum('status', ['active', 'harvested', 'failed', 'cancelled'])->default('active');
            $table->enum('status', ['aktif', 'panen', 'gagal', 'dibatalkan', 'selesai'])->default('aktif');
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Uniqueness kode batch per tenant
            $table->unique(['tenant_id', 'batch_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};