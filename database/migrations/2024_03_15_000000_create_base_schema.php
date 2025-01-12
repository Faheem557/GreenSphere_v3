<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        // Only create tables if they don't exist
        if (!Schema::hasTable('plants')) {
            Schema::create('plants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('name');
                $table->string('category');
                $table->string('sub_category')->nullable();
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2);
                $table->integer('quantity')->default(0);
                $table->string('image')->nullable();
                $table->json('specifications')->nullable();
                $table->json('delivery_info')->nullable();
                $table->json('care_instructions')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['user_id', 'is_active']);
                $table->index('category');
                $table->index(['category', 'sub_category']);
                $table->index('price');
            });
        }
        Schema::enableForeignKeyConstraints();
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('plants');
        Schema::enableForeignKeyConstraints();
    }
}; 