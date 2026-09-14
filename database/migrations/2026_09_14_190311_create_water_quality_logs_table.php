<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('water_quality_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('logged_by')->nullable()->constrained('users')->nullOnDelete();

            $table->date('check_date');
            $table->enum('check_time_session', ['pagi', 'siang', 'sore', 'malam'])->default('pagi');

            // Parameter Fisika & Kimia Air
            $table->decimal('ph', 4, 2)->nullable()->comment('Derajat Keasaman pH');
            $table->decimal('do_mg_l', 4, 2)->nullable()->comment('Dissolved Oxygen / Oksigen Terlarut (mg/L)');
            $table->decimal('temperature_c', 4, 1)->nullable()->comment('Suhu Air (°C)');
            $table->decimal('salinity_ppt', 4, 1)->nullable()->comment('Salinitas / Kadar Garam (ppt)');
            $table->decimal('transparency_cm', 5, 1)->nullable()->comment('Kecerahan Air (cm)');
            $table->string('water_color')->nullable()->comment('Warna Air, misal: Hijau Pekat, Cokelat Bioflok');

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('water_quality_logs');
    }
};