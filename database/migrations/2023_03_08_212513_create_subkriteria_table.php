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
		Schema::create('subkriteria', function (Blueprint $table) {
			$table->id();
			$table->foreignId('kriteria_id')->constrained('kriteria')
				->cascadeOnDelete()->comment('Kriteria');
			$table->string('name', 99)->comment('Nama sub kriteria');
			$table->float('bobot', 8, 5)->default(0.00000);
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
		Schema::dropIfExists('subkriteria');
	}
};