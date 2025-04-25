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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(0); // 0 = pending, 1 = completed, 2 = cancelled
            $table->unsignedBigInteger('quote_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('payment_id');
            $table->float('amount');
            $table->string('currency');

            $table->softDeletes();

            $table->timestamps();

            $table->foreign('customer_id')
                  ->references('id')
                  ->on('customers')
                  ->onDelete('cascade');
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
