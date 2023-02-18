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
        Schema::table('shop_product_parameter_values', function (Blueprint $table) {
            $table->bigInteger('color_value')->unsigned()->after('text_value')->nullable();
            $table->foreign('color_value')->references('id')->on('shop_colors')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_product_parameter_values', function (Blueprint $table) {
            $table->dropForeign('shop_product_parameter_values_color_value_foreign');
            $table->dropColumn('color_value');
        });
    }
};
