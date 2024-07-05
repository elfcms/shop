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
        Schema::create('shop_testimonials', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->foreign('customer_id')->references('id')->on('shop_customers')->onDelete('set null');
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('shop_products')->onDelete('cascade');
            $table->bigInteger('item_id')->unsigned()->nullable();
            $table->foreign('item_id')->references('id')->on('shop_items')->onDelete('set null');
            $table->string('title')->nullable();
            $table->text('text')->nullable();
            $table->integer('rating')->default(0);
            $table->integer('useful')->default(0);
            $table->integer('useless')->default(0);
            $table->text('answer')->nullable();
            $table->timestamp('answer_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_testimonials');
    }
};
