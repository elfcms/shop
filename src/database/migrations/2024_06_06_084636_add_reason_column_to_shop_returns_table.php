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
        Schema::table('shop_returns', function (Blueprint $table) {
            $table->text('reason_text')->nullable()->after('return_status_id');
            $table->bigInteger('reason_id')->unsigned()->nullable()->after('return_status_id');
            $table->foreign('reason_id')->references('id')->on('shop_return_reasons')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_returns', function (Blueprint $table) {
            $table->dropForeign(['reason_id']);
            $table->dropColumn('reason_id');
            $table->dropColumn('reason_text');
        });
    }
};
