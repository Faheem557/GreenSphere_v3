<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('plant_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('watering_schedule');
            $table->json('fertilizing_schedule');
            $table->string('pruning_schedule')->nullable();
            $table->string('repotting_schedule')->nullable();
            $table->timestamp('last_watered_at')->nullable();
            $table->timestamp('last_fertilized_at')->nullable();
            $table->timestamp('next_maintenance_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('plant_maintenances');
    }
}; 