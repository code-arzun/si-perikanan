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
        Schema::create('cashflow_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: 'Pakan', 'Gaji Staf', 'Hasil Panen'
            $table->enum('type', ['pemasukan', 'pengeluaran']); 
            $table->enum('keterangan', [
                'pembelian', 
                'pembayaran', 
                'penjualan', 
                'modal', 
                'lainnya'
            ])->default('pembelian'); // Keterangan tambahan untuk kategori
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Index untuk mempercepat query filtering berdasarkan type dan keterangan
            $table->index(['type', 'keterangan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashflow_categories');
    }
};