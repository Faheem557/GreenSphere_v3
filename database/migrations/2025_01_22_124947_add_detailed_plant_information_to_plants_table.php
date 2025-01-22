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
        Schema::table('plants', function (Blueprint $table) {
            $table->string('soil_type')->nullable();
            $table->string('temperature_range')->nullable();
            $table->string('humidity_requirements')->nullable();
            $table->string('fertilizer_needs')->nullable();
            $table->string('blooming_season')->nullable();
            $table->string('mature_height')->nullable();
            $table->string('growth_rate')->nullable();
            $table->boolean('pet_friendly')->default(false);
            $table->string('maintenance_level')->nullable();
            $table->string('propagation_method')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plants', function (Blueprint $table) {
            //
        });
    }
};
