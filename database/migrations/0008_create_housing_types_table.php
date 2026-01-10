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
        Schema::create('housing_types', function (Blueprint $table) {
            $table->id('houTyp_id');
            $table->string('houTyp_name');           // Ej: Piso, Apartamento, Chalet Pareado, Chalet Independiente
            $table->string('houTyp_code')->unique(); // Ej: FLAT, APAR, CHAL_PAR, CHAL_IND
            $table->string('houTyp_description')->nullable();
            $table->boolean('houTyp_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('housing_types');
    }
};
