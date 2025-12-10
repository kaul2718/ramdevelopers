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
        Schema::create('document_types', function (Blueprint $table) {
            $table->id('docTyp_id');
            $table->string('docTyp_code')->unique();
            $table->string('docTyp_name');
            $table->string('docTyp_description')->nullable();
            $table->boolean('docTyp_isInternal')->default(false);
            $table->boolean('docTyp_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_types');
    }
};
