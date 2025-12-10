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
        Schema::create('developments', function (Blueprint $table) {
            $table->id('devt_id');
            $table->string('devt_title');
            $table->string('devt_slug')->unique();
            $table->string('devt_address')->nullable();
            $table->string('devt_asset_type');
            $table->string('devt_short_description');
            $table->text('devt_long_description');
            $table->decimal('devt_price_from', 12, 2)->nullable();
            $table->decimal('devt_price_to', 12, 2)->nullable();
            $table->string('devt_commercial_status');
            $table->integer('devt_delivery_year')->nullable();
            $table->decimal('devt_estimated_profit', 12, 2)->nullable();
            $table->boolean('devt_is_featured')->default(false);
            $table->string('devt_approval_status');

            // FK Developer
            $table->foreignId('devr_id')
                ->nullable()
                ->constrained('developers', 'devr_id')
                ->nullOnDelete();

            // FK País
            $table->foreignId('ctry_id')
                ->nullable()
                ->constrained('countries', 'ctry_id')
                ->nullOnDelete();

            // FK Ciudad
            $table->foreignId('city_id')
                ->nullable()
                ->constrained('cities', 'city_id')
                ->nullOnDelete();

            // FK Approval Status
            $table->foreignId('apvSta_id')
                ->nullable()
                ->constrained('approval_statuses', 'apvSta_id')
                ->nullOnDelete();

            // FK Business State
            $table->foreignId('busiSta_id')
                ->nullable()
                ->constrained('business_state', 'busiSta_id')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('developments');
    }
};
