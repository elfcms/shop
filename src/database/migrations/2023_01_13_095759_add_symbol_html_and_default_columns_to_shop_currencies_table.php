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
        Schema::table('shop_currencies', function (Blueprint $table) {
            $table->boolean('default')->default(0)->after('symbol_code');
            $table->string('symbol_html')->nullable()->after('symbol_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_currencies', function (Blueprint $table) {
            $table->dropColumn('default');
            $table->dropColumn('symbol_html');
        });
    }
};
