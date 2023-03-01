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
        Schema::table('shop_prices', function (Blueprint $table) {
            $table->boolean('old')->nullable()->after('currency_id');
            $table->boolean('current')->nullable()->after('currency_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_prices', function (Blueprint $table) {
            $table->dropColumn('current');
            $table->dropColumn('old');
        });
    }
};
