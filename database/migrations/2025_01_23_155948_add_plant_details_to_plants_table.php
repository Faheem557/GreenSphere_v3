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
        // Schema::table('plants', function (Blueprint $table) {
        //     $table->string('soil_type')->nullable()->after('image');
        //     $table->string('temperature_range')->nullable()->after('soil_type');
        //     $table->string('humidity_requirements')->nullable()->after('temperature_range');
        //     $table->string('fertilizer_needs')->nullable()->after('humidity_requirements');
        //     $table->string('blooming_season')->nullable()->after('fertilizer_needs');
        //     $table->string('mature_height')->nullable()->after('blooming_season');
        //     $table->string('growth_rate')->nullable()->after('mature_height');
        //     $table->string('maintenance_level')->nullable()->after('growth_rate');
        //     $table->boolean('pet_friendly')->default(false)->after('maintenance_level');
        //     $table->string('propagation_method')->nullable()->after('pet_friendly');
        //     $table->string('care_level')->nullable()->after('propagation_method');
        //     $table->string('water_needs')->nullable()->after('care_level');
        //     $table->string('light_needs')->nullable()->after('water_needs');
            
        //     // Add JSON columns for detailed care information
        //     $table->json('care_guide')->nullable()->after('care_instructions');
        //     $table->json('maintenance')->nullable()->after('care_guide');
        // });
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
