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
		Schema::create('hasil', function (Blueprint $table) {
			$table->id();
			$table->foreignId('alternatif_id')->constrained('alternatif')
				->cascadeOnDelete()->comment('Alternatif');
			$table->float('skor', 8, 5)->default(0.00000);
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
		Schema::dropIfExists('hasil');
	}
};