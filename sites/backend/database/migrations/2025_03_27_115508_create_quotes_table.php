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

            Schema::create('quotes', function (Blueprint $table) {
                $table->id();
                $table->boolean('active');
    
                // Individual columns for request data
                $table->integer('couchtype')->nullable();
                $table->integer('styletype')->nullable();
                $table->integer('fabrictype')->nullable();
                $table->integer('legtype')->nullable();
                $table->integer('seatertype')->nullable();
                $table->string('discount_code')->nullable();
                $table->string('country')->nullable();
    
                // Individual columns for response data
                $table->text('description')->nullable();
                $table->decimal('couch_cost', 8, 2)->nullable();
                $table->decimal('discount', 8, 2)->nullable();
                $table->decimal('tax', 8, 2)->nullable();
                $table->decimal('total_cost', 8, 2)->nullable();

                $table->unsignedBigInteger('customer_id')->nullable();

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
        Schema::dropIfExists('quotes');
    }
};
