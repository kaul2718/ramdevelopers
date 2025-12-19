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
            // Remover columnas innecesarias si existen
            if (Schema::hasColumn('developments', 'devt_asset_type')) {
                $table->dropColumn('devt_asset_type');
            }
            if (Schema::hasColumn('developments', 'devt_commercial_status')) {
                $table->dropColumn('devt_commercial_status');
            }
            if (Schema::hasColumn('developments', 'devt_approval_status')) {
                $table->dropColumn('devt_approval_status');
            }
            
            // Agregar columnas que faltan
            if (!Schema::hasColumn('developments', 'commSta_id')) {
                $table->foreignId('commSta_id')
                    ->nullable()
                    ->after('busiSta_id')
                    ->constrained('commercial_statuses', 'commSta_id')
                    ->nullOnDelete();
            }
            
            if (!Schema::hasColumn('developments', 'devt_active')) {
                $table->boolean('devt_active')->default(true)->after('devt_is_featured');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('developments', function (Blueprint $table) {
            if (Schema::hasColumn('developments', 'commSta_id')) {
                $table->dropForeign(['commSta_id']);
                $table->dropColumn('commSta_id');
            }
            if (Schema::hasColumn('developments', 'devt_active')) {
                $table->dropColumn('devt_active');
            }
        });
    }
};
