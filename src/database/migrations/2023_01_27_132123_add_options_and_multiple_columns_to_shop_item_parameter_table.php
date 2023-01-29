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
        Schema::table('shop_item_parameters', function (Blueprint $table) {
            $table->json('options')->nullable()->after('is_filter');
            $table->boolean('multiple')->default(0)->after('is_filter');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_item_parameters', function (Blueprint $table) {
            $table->dropColumn('options');
            $table->dropColumn('multiple');
        });
    }
};
