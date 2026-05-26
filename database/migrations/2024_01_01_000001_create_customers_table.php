<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('nif')->nullable();
            $table->text('address')->nullable();
            $table->string('default_payment_type')->nullable();
            $table->string('default_payment_ref')->nullable();
            $table->text('custom')->nullable();
            $table->softDeletes();
            $table->foreign('id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
