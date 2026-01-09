<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            ['curr_code' => 'USD', 'curr_symbol' => '$', 'curr_name' => 'Dólar estadounidense', 'curr_symbol_first' => true, 'curr_active' => true],
            ['curr_code' => 'EUR', 'curr_symbol' => '€', 'curr_name' => 'Euro', 'curr_symbol_first' => true, 'curr_active' => true],
            ['curr_code' => 'GBP', 'curr_symbol' => '£', 'curr_name' => 'Libra esterlina', 'curr_symbol_first' => true, 'curr_active' => true],
            ['curr_code' => 'MXN', 'curr_symbol' => '$', 'curr_name' => 'Peso mexicano', 'curr_symbol_first' => true, 'curr_active' => true],
            ['curr_code' => 'ARS', 'curr_symbol' => '$', 'curr_name' => 'Peso argentino', 'curr_symbol_first' => true, 'curr_active' => true],
            ['curr_code' => 'COP', 'curr_symbol' => '$', 'curr_name' => 'Peso colombiano', 'curr_symbol_first' => true, 'curr_active' => true],
            ['curr_code' => 'CLP', 'curr_symbol' => '$', 'curr_name' => 'Peso chileno', 'curr_symbol_first' => true, 'curr_active' => true],
        ];

        foreach ($currencies as $currency) {
            Currency::create($currency);
        }
    }
}
