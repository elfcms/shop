<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_order_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('shop_orders')->onDelete('restrict');
            $table->bigInteger('item_id')->unsigned()->nullable();
            $table->foreign('item_id')->references('id')->on('shop_items')->onDelete('set null');
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('net_price');
            $table->unsignedInteger('discount_price')->nullable()->default(0);
            $table->unsignedInteger('price');
            $table->unsignedInteger('start_sum');
            $table->unsignedInteger('discount_sum')->nullable()->default(0);
            $table->unsignedInteger('result_sum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_order_items');
    }
};
