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
        Schema::create('couch_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50); // e.g., 'indoor' or 'outdoor'
            $table->timestamps();
        });

        //Optionally, you can seed the couch types here or in a separate seeder.
        // DB::table('couch_types')->insert([
        //     ['name' => 'indoor', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'outdoor', 'created_at' => now(), 'updated_at' => now()],
        // ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('couch_types');
    }
};
