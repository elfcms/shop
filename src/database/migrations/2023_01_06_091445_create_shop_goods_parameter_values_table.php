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
        Schema::create('shop_goods_parameter_values', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parameter_id')->unsigned();
            $table->foreign('parameter_id')->references('id')->on('shop_goods')->onDelete('cascade');
            $table->bigInteger('goods_id')->unsigned();
            $table->foreign('goods_id')->references('id')->on('shop_goods')->onDelete('cascade');
            $table->boolean('bool_value')->nullable();
            $table->bigInteger('int_value')->nullable();
            $table->float('float_value')->nullable();
            $table->string('string_value')->nullable();
            $table->text('text_value')->nullable();
            $table->date('date_value')->nullable();
            $table->dateTime('datetime_value')->nullable();
            $table->string('file_value')->nullable();
            $table->string('image_value')->nullable();
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
        Schema::dropIfExists('shop_goods_parameter_values');
    }
};
