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
        Schema::create('shop_item_pictures', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('item_id')->unsigned();
            $table->foreign('item_id')->references('id')->on('shop_items')->onDelete('cascade');
            $table->string('image');
            $table->string('preview')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('description')->nullable();
            $table->text('additional_text')->nullable();
            $table->integer('position')->nullable()->default(0);
            $table->boolean('active')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_item_pictures');
    }
};
