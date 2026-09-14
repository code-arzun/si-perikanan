<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feed_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('brand')->nullable()->comment('Merk pabrikan, misal: Matahari Sakri, JPP, CP Petfood');
            $table->decimal('protein_percentage', 5, 2)->nullable()->comment('Kadar Protein (%)');
            $table->string('pellet_size')->nullable()->comment('Ukuran pelet, misal: -1, -2, 2mm, 3mm');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feed_types');
    }
};