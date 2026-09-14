<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mortality_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('logged_by')->nullable()->constrained('users')->nullOnDelete();

            $table->date('log_date');
            $table->unsignedInteger('quantity_pcs')->comment('Jumlah mati (Ekor)');
            $table->decimal('total_weight_kg', 8, 2)->nullable()->comment('Total bobot mati (Kg)');
            $table->string('indication')->nullable()->comment('Dugaan penyakit / penyebab, misal: kanibalisme, jamur');
            $table->text('action_taken')->nullable()->comment('Tindakan penanganan yang dilakukan');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mortality_logs');
    }
};