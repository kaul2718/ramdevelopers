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
        Schema::create('commercial_statuses', function (Blueprint $table) {
            $table->id('commSta_id');
            $table->string('commSta_name');           // Ej: Disponible, Vendido, Reservado
            $table->string('commSta_code')->unique(); // Ej: AVAI, SOLD, RESV
            $table->string('commSta_description')->nullable();
            $table->boolean('commSta_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commercial_statuses');
    }
};
