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
        Schema::create('developers', function (Blueprint $table) {
            $table->id('devr_id');
            $table->string('devr_commercial_name');
            $table->string('devr_legal_name');
            $table->string('devr_email_contact');
            $table->string('devr_phone_contact');
            $table->string('devr_website')->nullable();
            $table->boolean('devr_active')->default(true);

            // FK País
            $table->unsignedBigInteger('ctry_id')->nullable();
            $table->foreign('ctry_id')
                ->references('ctry_id')->on('countries')
                ->nullOnDelete();

            // FK Usuario promotor/creador
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
        Schema::dropIfExists('developers');
    }
};
