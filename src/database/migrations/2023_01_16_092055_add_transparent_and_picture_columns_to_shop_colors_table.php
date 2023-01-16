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
        Schema::table('shop_colors', function (Blueprint $table) {
            $table->boolean('transparent')->default(0)->after('name');
            $table->string('picture')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_colors', function (Blueprint $table) {
            $table->dropColumn('transparent');
            $table->dropColumn('picture');
        });
    }
};
