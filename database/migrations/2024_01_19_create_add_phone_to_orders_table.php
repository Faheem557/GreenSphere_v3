<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Schema::table('orders', function (Blueprint $table) {
        //     // $table->string('phone')->nullable()->after('shipping_address');
        //     $table->timestamp('delivery_date')->nullable()->after('phone');
        //     $table->string('delivery_slot')->nullable()->after('delivery_date');
        //     $table->text('delivery_instructions')->nullable()->after('delivery_slot');
        // });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['phone', 'delivery_date', 'delivery_slot', 'delivery_instructions']);
        });
    }
}; 