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
        Schema::create('shop_invoices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('shop_orders')->onDelete('restrict');
            $table->bigInteger('invoice_status_id')->unsigned();
            $table->foreign('invoice_status_id')->references('id')->on('shop_invoice_statuses')->onDelete('restrict');
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
        Schema::dropIfExists('shop_invoices');
    }
};
