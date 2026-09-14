<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('harvest_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('logged_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->date('harvest_date');
            $table->enum('harvest_type', ['partial', 'total'])->default('partial');
            $table->decimal('weight_kg', 10, 2);
            $table->integer('total_pcs')->nullable();
            $table->integer('fish_size')->nullable(); // Ekor per Kg (cth: size 10)
            $table->decimal('price_per_kg', 12, 2)->nullable();
            $table->decimal('total_revenue', 15, 2)->nullable();
            $table->string('buyer_name')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('harvest_logs');
    }
};