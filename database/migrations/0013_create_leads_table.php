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
        Schema::create('leads', function (Blueprint $table) {
            $table->id('lead_id');

            $table->string('lead_client_name');
            $table->string('lead_client_email')->nullable();
            $table->string('lead_client_phone')->nullable();
            $table->text('lead_message')->nullable();
            $table->string('lead_language')->nullable();
            $table->timestamp('lead_created')->useCurrent();
            $table->timestamp('lead_updated')->nullable();

            // FK Development
            $table->unsignedBigInteger('devt_id')->nullable();
            $table->foreign('devt_id')
                ->references('devt_id')->on('developments')
                ->nullOnDelete();

            // FK Country
            $table->unsignedBigInteger('ctry_id')->nullable();
            $table->foreign('ctry_id')
                ->references('ctry_id')->on('countries')
                ->nullOnDelete();

            // FK User (captador responsable)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // FK Lead Source
            $table->unsignedBigInteger('leadSou_id')->nullable();
            $table->foreign('leadSou_id')
                ->references('leadSou_id')->on('lead_sources')
                ->nullOnDelete();

            // FK Lead Status
            $table->unsignedBigInteger('leadSta_id')->nullable();
            $table->foreign('leadSta_id')
                ->references('leadSta_id')->on('lead_statuses')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
