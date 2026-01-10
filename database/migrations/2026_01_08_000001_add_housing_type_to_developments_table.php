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
            // FK Housing Type
            $table->foreignId('houTyp_id')
                ->nullable()
                ->after('commSta_id')
                ->constrained('housing_types', 'houTyp_id')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('developments', function (Blueprint $table) {
            $table->dropForeignIdFor('HousingType');
            $table->dropColumn('houTyp_id');
        });
    }
};
