<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cash_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->onDelete('set null');
            $table->foreignId('cashflow_category_id')->constrained('cashflow_categories')->onDelete('restrict');
            
            $table->enum('type', ['income', 'expense']); // Pemasukan vs Pengeluaran
            
            // Detail Rincian Kuantitas & Harga
            $table->decimal('unit_price', 15, 2)->default(0); // Harga Satuan
            $table->decimal('quantity', 10, 2)->default(1);   // Jumlah (support desimal misal 1.5 kg)
            $table->string('unit', 20)->default('pcs');       // Satuan (kg, liter, sak, pcs, dll)
            $table->decimal('amount', 15, 2);                 // Total Akhir (unit_price * quantity)
            
            $table->date('transaction_date');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_transactions');
    }
};