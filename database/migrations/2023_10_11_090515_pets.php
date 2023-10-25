<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void {
		Schema::create('pets', function (Blueprint $table) {
			$table->increments('id')->unique();
			$table->string('name')->nullable(false);
			$table->longText('description')->default(null);
			$table->integer('age_in_months')->nullable(false);
			$table->string('sex')->nullable(false);
			$table->double('price')->nullable(false);
			$table->string('location')->default(null);
			$table->double('weight')->nullable(false);
			$table->string('kidFriendly')->nullable(false);
			$table->string('multipleAnimalsFriendly')->nullable(false);
			$table->string('castrated')->nullable(false);
			$table->unsignedInteger('users_id')->nullable(false);
			$table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
			$table->unsignedInteger('type_of_pets_id')->nullable(false);
			$table->foreign('type_of_pets_id')->references('id')->on('type_of_pets')->onDelete('cascade');
			$table->unsignedInteger('breeds_id')->nullable(true);
			$table->foreign('breeds_id')->references('id')->on('breeds')->onDelete('cascade');
			$table->timestamps();
		});
	}

	public function down(): void {
		Schema::dropIfExists('pets');
	}
};
