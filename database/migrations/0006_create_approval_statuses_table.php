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
        Schema::create('approval_statuses', function (Blueprint $table) {
            $table->id('apvSta_id');
            $table->string('apvSta_name');           // Ejemplo: Pending, Approved, Rejected
            $table->string('apvSta_code')->unique(); // Ej: PEND, APRV, RJCT
            $table->string('apvSta_description')->nullable();
            $table->boolean('apvSta_active')->default(true);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_statuses');
    }
};
