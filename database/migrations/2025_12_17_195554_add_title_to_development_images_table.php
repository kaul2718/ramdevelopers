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
        Schema::table('development_images', function (Blueprint $table) {
            if (!Schema::hasColumn('development_images', 'devImg_title')) {
                $table->string('devImg_title')->nullable()->after('devImg_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('development_images', function (Blueprint $table) {
            if (Schema::hasColumn('development_images', 'devImg_title')) {
                $table->dropColumn('devImg_title');
            }
        });
    }
};
