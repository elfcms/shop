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
        Schema::create('shop_customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->foreign('customer_id')
                ->references('id')
                ->on('shop_customers')
                ->cascadeOnDelete()
                ->nullable();
            $table->string('plz',10);
            $table->string('country',100)->nullable();
            $table->string('region',100)->nullable();
            $table->string('city',100);
            $table->string('street',100)->nullable();
            $table->string('house',10)->nullable();
            $table->string('flat',10)->nullable();
            $table->string('address',100)->nullable();
            $table->string('additional',100)->nullable();
            $table->bigInteger('geodata_zip_id')
                ->unsigned()
                ->nullable();
            $table->bigInteger('geodata_country_id')
                ->unsigned()
                ->nullable();
            $table->bigInteger('geodata_region_id')
                ->unsigned()
                ->nullable();
            $table->bigInteger('geodata_city_id')
                ->unsigned()
                ->nullable();
            $table->bigInteger('geodata_street_id')
                ->unsigned()
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_customer_addresses');
    }
};
