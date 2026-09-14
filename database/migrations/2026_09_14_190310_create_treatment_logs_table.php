<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('treatment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('logged_by')->nullable()->constrained('users')->nullOnDelete();

            $table->date('treatment_date');
            $table->string('product_name')->comment('Nama bahan, misal: Probiotik A, Kapur Dolomit, Molase');
            $table->decimal('dosage_amount', 8, 2)->comment('Jumlah/Dosis');
            $table->string('dosage_unit', 20)->comment('Satuan dosis, misal: Liter, Kg, Gram, ppm');
            $table->string('purpose')->nullable()->comment('Tujuan treatment, misal: penumbuhan plankton, sterilisasi air');
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treatment_logs');
    }
};