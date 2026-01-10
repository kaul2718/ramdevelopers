<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('developments', function (Blueprint $table) {
            $table->unsignedBigInteger('curr_id')->nullable()->after('houTyp_id');
            $table->foreign('curr_id')->references('curr_id')->on('currencies')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('developments', function (Blueprint $table) {
            $table->dropForeign(['curr_id']);
            $table->dropColumn('curr_id');
        });
    }
};
