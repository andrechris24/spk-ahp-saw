<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subkriteria_banding', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idkriteria')->constrained('kriteria')->cascadeOnDelete();
            $table->foreignId('subkriteria1')->constrained('subkriteria')->cascadeOnDelete();
            $table->foreignId('subkriteria2')->constrained('subkriteria')->cascadeOnDelete();
            $table->float('nilai', 8,5)->default(0.0000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subkriteria_banding');
    }
};
