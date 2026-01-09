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
            $table->boolean('devt_estimated_profit_is_percentage')->default(false)->after('devt_estimated_profit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('developments', function (Blueprint $table) {
            $table->dropColumn('devt_estimated_profit_is_percentage');
        });
    }
};
