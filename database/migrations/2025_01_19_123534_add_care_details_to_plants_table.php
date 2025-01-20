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
            $table->enum('care_level', ['easy', 'moderate', 'expert'])
                  ->default('moderate')
                  ->after('specifications');
                  
            $table->enum('water_needs', ['low', 'medium', 'high'])
                  ->default('medium')
                  ->after('care_level');
                  
            $table->enum('light_needs', ['low', 'medium', 'bright', 'direct'])
                  ->default('medium')
                  ->after('water_needs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plants', function (Blueprint $table) {
            $table->dropColumn('care_level');
            $table->dropColumn('water_needs');
            $table->dropColumn('light_needs');
        });
    }
};
