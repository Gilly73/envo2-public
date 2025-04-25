<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seaters', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('seat_count'); // e.g., 2, 3, or 4
            $table->unsignedBigInteger('couch_type_id'); // Reference to the couch type
            $table->string('description', 100)->nullable(); // e.g., "2 seater - indoor"
            $table->decimal('price', 10, 2); // Price for this seater option
            $table->timestamps();

            $table->foreign('couch_type_id')
                  ->references('id')
                  ->on('couch_types')
                  ->onDelete('cascade');
        });

        // DB::table('seaters')->insert([
        //     ['seat_count' => 2, 'couch_type_id' => 1, 'price' => 50.00,'description' => '2 seater - indoor','created_at' => now(), 'updated_at' => now()],
        //     ['seat_count' => 3, 'couch_type_id' => 1, 'price' => 100.00,'description' => '3 seater - indoor','created_at' => now(), 'updated_at' => now()],
        //     ['seat_count' => 4, 'couch_type_id' => 1, 'price' => 150.00,'description' => '4 seater - indoor','created_at' => now(), 'updated_at' => now()],
        //     ['seat_count' => 2, 'couch_type_id' => 2, 'price' => 20.00,'description' => '2 seater - outdoor','created_at' => now(), 'updated_at' => now()],
        //     ['seat_count' => 3, 'couch_type_id' => 2, 'price' => 30.00,'description' => '3 seater - outdoor','created_at' => now(), 'updated_at' => now()],
        // ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seaters');
    }
};
