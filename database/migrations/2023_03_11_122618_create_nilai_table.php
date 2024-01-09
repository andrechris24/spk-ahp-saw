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
		Schema::create('nilai', function (Blueprint $table) {
			$table->id();
			$table->foreignId('alternatif_id')->constrained('alternatif')
				->cascadeOnDelete()->comment('Alternatif');
			$table->foreignId('kriteria_id')->constrained('kriteria')
				->cascadeOnDelete()->comment('Kriteria');
			$table->foreignId('subkriteria_id')->constrained('subkriteria')
				->cascadeOnDelete()->comment('Sub Kriteria');
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
		Schema::dropIfExists('nilai');
	}
};