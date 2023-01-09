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
        Schema::table('shop_goods', function (Blueprint $table) {
            $table->bigInteger('unit_id')->unsigned()->after('title');
            $table->foreign('unit_id')->references('id')->on('shop_units')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_goods', function (Blueprint $table) {
            $table->dropForeign('unit_id');
            $table->dropColumn('unit_id');
        });
    }
};
