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
        Schema::create('shop_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id')->unsigned();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('restrict');
            $table->bigInteger('payment_method_id')->unsigned();
            $table->foreign('payment_method_id')->references('id')->on('shop_payment_methods')->onDelete('restrict');
            $table->bigInteger('delivery_id')->unsigned();
            $table->foreign('delivery_id')->references('id')->on('shop_deliveries')->onDelete('restrict');
            $table->bigInteger('order_status_id')->unsigned();
            $table->foreign('order_status_id')->references('id')->on('shop_order_statuses')->onDelete('restrict');
            $table->unsignedInteger('start_sum');
            $table->unsignedInteger('discount_sum')->nullable()->default(0);
            $table->unsignedInteger('clear_sum');
            $table->unsignedInteger('delivery_sum')->default(0);
            $table->unsignedInteger('result_sum');
            $table->unsignedInteger('item_quantity')->nullable();
            $table->unsignedInteger('total_quantity')->nullable();
            $table->string('tracking_code',500)->nullable();
            $table->text('tracking_link')->nullable();
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
        Schema::dropIfExists('shop_orders');
    }
};
