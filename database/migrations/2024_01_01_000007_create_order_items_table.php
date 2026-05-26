<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('tshirt_image_id');
            $table->string('color_code');
            $table->string('size');
            $table->integer('qty');
            $table->decimal('unit_price', 8, 2);
            $table->decimal('sub_total', 10, 2);
            $table->text('custom')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->foreign('tshirt_image_id')->references('id')->on('tshirt_images');
            $table->foreign('color_code')->references('code')->on('colors');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
