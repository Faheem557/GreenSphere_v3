<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('plant_care_guides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_id')->constrained()->onDelete('cascade');
            $table->string('watering_schedule');
            $table->string('light_requirements');
            $table->json('temperature_range');
            $table->string('humidity_level');
            $table->string('fertilizing_schedule');
            $table->text('pruning_tips');
            $table->json('common_problems');
            $table->json('seasonal_care');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('plant_care_guides');
    }
}; 