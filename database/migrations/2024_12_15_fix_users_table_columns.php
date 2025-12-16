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
        Schema::table('users', function (Blueprint $table) {
            // Cambiar idiomas de json a string
            $table->string('idiomas')->nullable()->change();
            
            // Agregar usr_active si no existe
            if (!Schema::hasColumn('users', 'usr_active')) {
                $table->boolean('usr_active')->default(true)->after('usr_id_ctry');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revertir idiomas a json
            $table->json('idiomas')->nullable()->change();
            
            // Eliminar usr_active
            if (Schema::hasColumn('users', 'usr_active')) {
                $table->dropColumn('usr_active');
            }
        });
    }
};
