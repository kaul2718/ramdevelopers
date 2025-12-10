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
        Schema::create('lead_notes', function (Blueprint $table) {
            $table->id('leadNot_id');
            $table->string('leadNot_title', 150)->nullable();
            $table->text('leadNot_description')->nullable();
            $table->dateTime('leadNot_date')->nullable();
            $table->boolean('leadNot_active')->default(true);

            // FK Lead
            $table->unsignedBigInteger('lead_id')->nullable();
            $table->foreign('lead_id')
                ->references('lead_id')->on('leads')
                ->nullOnDelete();

            // FK Usuario
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
        Schema::dropIfExists('lead_notes');
    }
};
