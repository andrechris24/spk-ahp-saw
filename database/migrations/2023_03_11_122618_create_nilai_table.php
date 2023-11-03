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
				->cascadeOnDelete()->cascadeOnUpdate();
			$table->foreignId('kriteria_id')->constrained('kriteria')
				->cascadeOnDelete()->cascadeOnUpdate();
			$table->foreignId('subkriteria_id')->nullable()->constrained('subkriteria')
				->nullOnDelete()->cascadeOnUpdate();
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