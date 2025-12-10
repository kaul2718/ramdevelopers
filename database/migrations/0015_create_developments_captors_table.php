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
        Schema::create('developments_captors', function (Blueprint $table) {
            $table->unsignedBigInteger('devt_id');
            $table->unsignedBigInteger('user_id');

            $table->boolean('devtUsr_is_main')->default(false);

            $table->timestamps();

            // PK compuesta
            $table->primary(['devt_id', 'user_id']);

            // FK development
            $table->foreign('devt_id')
                ->references('devt_id')->on('developments')
                ->cascadeOnDelete();

            // FK users
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->cascadeOnDelete();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('developments_captors');
    }
};
