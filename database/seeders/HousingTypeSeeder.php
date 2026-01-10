<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HousingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('housing_types')->insert([
            [
                'houTyp_name' => 'Piso',
                'houTyp_code' => 'FLAT',
                'houTyp_description' => 'Vivienda en un edificio, sin acceso directo al terreno',
                'houTyp_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'houTyp_name' => 'Apartamento',
                'houTyp_code' => 'APAR',
                'houTyp_description' => 'Unidad de vivienda generalmente más compacta',
                'houTyp_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'houTyp_name' => 'Chalet Pareado',
                'houTyp_code' => 'CHAL_PAR',
                'houTyp_description' => 'Vivienda individual compartiendo una pared con otra',
                'houTyp_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'houTyp_name' => 'Chalet Independiente',
                'houTyp_code' => 'CHAL_IND',
                'houTyp_description' => 'Vivienda individual con terreno propio sin compartir paredes',
                'houTyp_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
