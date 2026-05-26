<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('unit_price_catalog', 8, 2);
            $table->decimal('unit_price_own', 8, 2);
            $table->decimal('unit_price_catalog_discount', 8, 2);
            $table->decimal('unit_price_own_discount', 8, 2);
            $table->integer('qty_discount')->default(10);
            $table->text('custom')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
