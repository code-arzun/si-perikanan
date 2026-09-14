<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sampling_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('logged_by')->nullable()->constrained('users')->nullOnDelete();

            $table->date('sampling_date');
            $table->unsignedInteger('sample_count_pcs')->comment('Jumlah ikan yang di-sampling (Ekor)');
            $table->decimal('avg_weight_g', 8, 2)->comment('MBW / Bobot Rata-rata per Ekor (Gram)');
            $table->decimal('avg_length_cm', 6, 2)->nullable()->comment('Panjang Rata-rata (cm)');
            $table->decimal('estimated_biomass_kg', 10, 2)->nullable()->comment('Estimasi Total Biomasa Kolam (Kg)');
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sampling_logs');
    }
};