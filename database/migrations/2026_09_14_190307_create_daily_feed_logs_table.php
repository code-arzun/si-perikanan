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
            $table->time('feed_time')->comment('Waktu pemberian pakan, misal 08:00');
            $table->decimal('amount_kg', 8, 2)->comment('Jumlah pakan (Kg)');
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