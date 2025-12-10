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
        Schema::create('development_images', function (Blueprint $table) {
            $table->id('devImg_id');
            $table->string('devImg_url');
            $table->boolean('devImg_is_cover')->default(false);
            $table->integer('devImg_order')->nullable();

            // FK hacia developments
            $table->unsignedBigInteger('devt_id')->nullable();
            $table->foreign('devt_id')
                ->references('devt_id')->on('developments')
                ->nullOnDelete();
                
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('development_images');
    }
};
