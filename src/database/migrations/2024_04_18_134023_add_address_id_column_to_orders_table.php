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
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->bigInteger('customer_address_id')->unsigned()->nullable()->after('delivery_id');
            $table->foreign('customer_address_id')->references('id')->on('shop_customer_addresses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->dropForeign(['customer_address_id']);
            $table->dropColumn('customer_address_id');
        });
    }
};
