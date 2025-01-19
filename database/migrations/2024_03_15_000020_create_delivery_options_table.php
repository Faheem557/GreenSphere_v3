<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('delivery_options', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 8, 2);
            $table->integer('estimated_days');
            $table->boolean('is_available')->default(true);
            $table->json('coverage_area');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_options');
    }
}; 