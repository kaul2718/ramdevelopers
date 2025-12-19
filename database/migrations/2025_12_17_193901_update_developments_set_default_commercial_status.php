<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Asignar el primer estado comercial a los desarrollos que no lo tienen
        DB::statement('UPDATE developments SET commSta_id = 1 WHERE commSta_id IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a NULL los valores que se asignaron
        DB::statement('UPDATE developments SET commSta_id = NULL WHERE TRUE');
    }
};
