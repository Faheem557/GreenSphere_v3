<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('delivery_option_id')->nullable()->constrained();
            $table->date('preferred_delivery_date')->nullable();
            $table->text('delivery_instructions')->nullable();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['delivery_option_id']);
            $table->dropColumn([
                'delivery_option_id',
                'preferred_delivery_date',
                'delivery_instructions'
            ]);
        });
    }
}; 