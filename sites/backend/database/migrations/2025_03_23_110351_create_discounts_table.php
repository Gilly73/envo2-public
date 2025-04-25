<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->float('discount');
            $table->string('code', 10);
            $table->unsignedBigInteger('country_id');
            $table->boolean('active')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->onDelete('cascade');
        });

        DB::table('discounts')->insert([
            ['country_id' => 1, 'active' => true, 'code'=>'UKSAVE10', 'discount' => 0.10, 'created_at' => now(), 'updated_at' => now()],
            ['country_id' => 1, 'active' => true, 'code'=>'UKSAVE50', 'discount' => 0.50, 'created_at' => now(), 'updated_at' => now()],
            ['country_id' => 2, 'active' => true, 'code'=>'USSAVE10', 'discount' => 0.20, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
