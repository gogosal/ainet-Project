<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('customer_id');
            $table->date('date');
            $table->decimal('total_price', 10, 2);
            $table->text('notes')->nullable();
            $table->text('reason_for_cancellation')->nullable();
            $table->string('nif')->nullable();
            $table->text('address')->nullable();
            $table->string('payment_type');
            $table->string('payment_ref');
            $table->string('receipt_url')->nullable();
            $table->text('custom')->nullable();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
