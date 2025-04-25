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
        Schema::create('fabrics', function (Blueprint $table) {
            $table->id();
            $table->string('material', 50); // e.g., "cotton", "leather", "wood", "plastic"
            $table->unsignedBigInteger('couch_type_id'); // Reference to the couch type
            $table->decimal('price', 10, 2); // Price for this fabric option
            $table->timestamps();

            $table->foreign('couch_type_id')
                  ->references('id')
                  ->on('couch_types')
                  ->onDelete('cascade');
        });

        // DB::table('fabrics')->insert([
        //     ['couch_type_id' => 1, 'price' => 300.00,'material' => 'velvet','created_at' => now(), 'updated_at' => now()],
        //     ['couch_type_id' => 1, 'price' => 100.00,'material' => 'cotton','created_at' => now(), 'updated_at' => now()],
        //     ['couch_type_id' => 1, 'price' => 400.00,'material' => 'leather','created_at' => now(), 'updated_at' => now()],
        //     ['couch_type_id' => 2, 'price' => 100.00,'material' => 'wood','created_at' => now(), 'updated_at' => now()],
        //     ['couch_type_id' => 2, 'price' => 200.00,'material' => 'metal','created_at' => now(), 'updated_at' => now()],
        // ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fabrics');
    }
};
