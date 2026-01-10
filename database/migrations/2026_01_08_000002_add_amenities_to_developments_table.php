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
        Schema::table('developments', function (Blueprint $table) {
            // Comodidades (cantidad)
            $table->integer('devt_storage_rooms')->default(0)->comment('Cantidad de trasteros');
            $table->integer('devt_parking_spaces')->default(0)->comment('Cantidad de parking');
            $table->integer('devt_terraces')->default(0)->comment('Cantidad de terrazas');
            $table->integer('devt_swimming_pools')->default(0)->comment('Cantidad de piscinas');
            $table->integer('devt_children_areas')->default(0)->comment('Cantidad de áreas infantiles');
            $table->integer('devt_green_zones')->default(0)->comment('Cantidad de zonas verdes');
            $table->integer('devt_elevators')->default(0)->comment('Cantidad de ascensores');
            $table->integer('devt_golf_courses')->default(0)->comment('Cantidad de campos de golf');
            
            // Dormitorios
            $table->integer('devt_bedrooms')->default(1)->comment('Número de dormitorios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('developments', function (Blueprint $table) {
            $table->dropColumn([
                'devt_storage_rooms',
                'devt_parking_spaces',
                'devt_terraces',
                'devt_swimming_pools',
                'devt_children_areas',
                'devt_green_zones',
                'devt_elevators',
                'devt_golf_courses',
                'devt_bedrooms'
            ]);
        });
    }
};
