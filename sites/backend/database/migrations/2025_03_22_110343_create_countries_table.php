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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('country', 4); 
            $table->string('label', 50);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

    DB::table('countries')->insert([
        ['country' => 'UK', 'active' => true,'label' => 'United Kindom','created_at' => now(), 'updated_at' => now()],
        ['country' => 'US', 'active' => true,'label' => 'United States America','created_at' => now(), 'updated_at' => now()],
    ]);
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
