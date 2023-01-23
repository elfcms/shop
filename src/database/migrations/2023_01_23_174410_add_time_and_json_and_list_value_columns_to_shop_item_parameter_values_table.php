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
        Schema::table('shop_item_parameter_values', function (Blueprint $table) {
            $table->time('time_value')->nullable()->after('date_value');
            $table->json('list_value')->nullable()->after('file_value');
            $table->json('json_value')->nullable()->after('file_value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_item_parameter_values', function (Blueprint $table) {
            $table->dropColumn('time_value');
            $table->dropColumn('json_value');
            $table->dropColumn('list_value');
        });
    }
};
