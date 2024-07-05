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
        Schema::create('shop_bills', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->unsigned()->nullable();
            $table->foreign('order_id')->references('id')->on('shop_orders')->onDelete('set null');
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->foreign('customer_id')->references('id')->on('shop_customers')->onDelete('set null');
            $table->json('customer_data')->nullable();
            $table->string('number')->unique();
            $table->date('bill_date');
            $table->date('deadline');
            $table->json('strings')->nullable();
            $table->integer('sum');
            $table->integer('discount')->default(0);
            $table->integer('total');
            $table->integer('tax')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->boolean('is_cancelled')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_bills');
    }
};
