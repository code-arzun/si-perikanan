<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_feed_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('feed_type_id')->nullable()->constrained('feed_types')->nullOnDelete();
            $table->foreignId('logged_by')->nullable()->constrained('users')->nullOnDelete();

            $table->date('feed_date');
            // $table->time('feed_time')->comment('Waktu pencatatan/pemberian pakan terakhir');
            
            // Rincian Kuantitas Pakan Harian
            $table->decimal('amount_per_feed_g', 10, 2)->nullable()->comment('Jumlah pakan per sekali pemberian (Gram)');
            $table->unsignedInteger('feeding_frequency')->default(1)->comment('Frekuensi pemberian pakan dalam sehari');
            $table->decimal('amount_kg', 10, 2)->comment('Total pakan harian (Kg)');
            
            $table->enum('appetite_response', ['sangat_baik', 'baik', 'kurang', 'buruk'])->default('baik');
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_feed_logs');
    }
};