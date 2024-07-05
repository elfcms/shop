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
        Schema::create('shop_testimonial_likes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('testimonial_id')->unsigned();
            $table->foreign('testimonial_id')->references('id')->on('shop_testimonials')->onDelete('cascade');
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->foreign('customer_id')->references('id')->on('shop_customers')->onDelete('set null');
            $table->string('tmp_user')->nullable();
            $table->boolean('useful')->nullable()->default(null);
            $table->boolean('useless')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_testimonial_likes');
    }
};
