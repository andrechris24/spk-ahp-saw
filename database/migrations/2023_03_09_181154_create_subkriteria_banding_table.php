<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subkriteria_banding', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idkriteria')->constrained('kriteria')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('subkriteria1')->constrained('subkriteria')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('subkriteria2')->constrained('subkriteria')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('nilai');
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