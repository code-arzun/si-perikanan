<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Master Spesies Ikan
        Schema::create('fish_species', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('latin_name')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Master Kategori/Jenis Pakan
        Schema::create('feed_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Master Jenis Konstruksi Kolam
        Schema::create('pond_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Master Kategori Arus Kas (Hybrid: Global SaaS + Custom Tenant)
        Schema::create('cash_flow_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['income', 'expense']);
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->index(['tenant_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_flow_categories');
        Schema::dropIfExists('pond_types');
        Schema::dropIfExists('feed_types');
        Schema::dropIfExists('fish_species');
    }
};