<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id('curr_id');
            $table->string('curr_code', 3)->unique();
            $table->string('curr_symbol', 10);
            $table->string('curr_name')->unique();
            $table->boolean('curr_symbol_first')->default(false);
            $table->boolean('curr_active')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
