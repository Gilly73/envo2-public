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
        Schema::create('legs', function (Blueprint $table) {
            $table->id();
            $table->string('description', 100); // e.g., "black wooden leg"
            $table->unsignedBigInteger('couch_type_id'); // Reference to the couch type
            $table->decimal('price', 10, 2); // Price for this leg option
            $table->timestamps();

            $table->foreign('couch_type_id')
                  ->references('id')
                  ->on('couch_types')
                  ->onDelete('cascade');
        });

        // DB::table('legs')->insert([
        //     ['description' => 'black wooden leg','couch_type_id' => 1,'price' => 10.00,'created_at' => now(), 'updated_at' => now()],
        //     ['couch_type_id' => 1, 'price' => 30.00,'description' => 'gold metal leg','created_at' => now(), 'updated_at' => now()],
        //     ['couch_type_id' => 1, 'price' => 20.00,'description' => 'brown wooden leg','created_at' => now(), 'updated_at' => now()],
        //     ['couch_type_id' => 2, 'price' => 30.00,'description' => 'brown protected wooden leg','created_at' => now(), 'updated_at' => now()],
        //     ['couch_type_id' => 2, 'price' => 20.00,'description' => 'white metal leg','created_at' => now(), 'updated_at' => now()],
        // ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('legs');
    }
};
