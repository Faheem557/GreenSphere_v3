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
            $table->string('season')->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->string('pot_size')->nullable();
            $table->string('maturity_time')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->json('tags')->nullable();
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
