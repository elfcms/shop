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
        Schema::table('shop_invoices', function (Blueprint $table) {
            $table->text('file')->nullable()->after('result_sum');
            $table->integer('delivery_sum')->unsigned()->default(0)->after('discount_sum');
            $table->integer('clear_sum')->unsigned()->after('discount_sum');
            $table->string('number')->nullable()->after('id');
            $table->bigInteger('customer_id')->unsigned()->nullable()->after('order_id');
            $table->foreign('customer_id')->references('id')->on('shop_customers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_invoices', function (Blueprint $table) {
            $table->dropColumn('file');
            $table->dropColumn('delivery_sum');
            $table->dropColumn('clear_sum');
            $table->dropColumn('number');
            $table->dropForeign('customer_id');
            $table->dropColumn('customer_id');
        });
    }
};
