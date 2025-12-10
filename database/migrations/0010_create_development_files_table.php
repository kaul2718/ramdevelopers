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
        Schema::create('development_files', function (Blueprint $table) {
            $table->id('devFile_id');
            $table->string('devFile_code')->nullable();
            $table->string('devFile_name');
            $table->string('devFile_url');
            $table->boolean('devFile_isPublic')->default(false);

            // FK hacia developments
            $table->foreignId('devt_id')
                ->nullable()
                ->constrained('developments', 'devt_id')
                ->nullOnDelete();

            // FK hacia document_types
            $table->foreignId('docTyp_id')
                ->nullable()
                ->constrained('document_types', 'docTyp_id')
                ->nullOnDelete();

            // FK hacia usuario
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('development_files');
    }
};
