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
		Schema::create('kriteria', function (Blueprint $table) {
			$table->id();
			$table->string('name', 99)->comment('Nama kriteria');
			$table->float('bobot', 8, 5)->default(0.00000);
			$table->enum('type', ['cost', 'benefit'])->comment('Atribut');
			$table->string('desc')->nullable()->comment('Keterangan');
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
		Schema::dropIfExists('kriteria');
	}
};