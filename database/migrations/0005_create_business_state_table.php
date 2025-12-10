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
        Schema::create('business_state', function (Blueprint $table) {
            $table->id('busiSta_id');
            $table->string('busiSta_name');           // Ej: En preventa, En construcción
            $table->string('busiSta_code')->unique(); // Ej: PRE, CONST, ENT
            $table->string('busiSta_description')->nullable();
            $table->boolean('busiSta_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_state');
    }
};
