<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add phone column if it doesn't exist
            if (!Schema::hasColumn('orders', 'phone')) {
                $table->string('phone')->nullable()->after('shipping_address');
            }
            
            // Add delivery fields if they don't exist
            if (!Schema::hasColumn('orders', 'delivery_date')) {
                $table->timestamp('delivery_date')->nullable()->after('phone');
            }
            
            if (!Schema::hasColumn('orders', 'delivery_slot')) {
                $table->string('delivery_slot')->nullable()->after('delivery_date');
            }
            
            if (!Schema::hasColumn('orders', 'delivery_instructions')) {
                $table->text('delivery_instructions')->nullable()->after('delivery_slot');
            }
            
            // Change shipping_address to text if it's currently json
            $table->text('shipping_address')->change();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'delivery_date',
                'delivery_slot',
                'delivery_instructions'
            ]);
            
            // Revert shipping_address back to json if needed
            $table->json('shipping_address')->change();
        });
    }
}; 