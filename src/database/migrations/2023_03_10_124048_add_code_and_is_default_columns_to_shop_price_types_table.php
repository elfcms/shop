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
        Schema::table('shop_price_types', function (Blueprint $table) {
            $table->string('code')->nullable()->unique()->after('name');
            $table->boolean('is_default')->nullable()->after('tax_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_price_types', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->dropColumn('is_default');
        });
    }
};
