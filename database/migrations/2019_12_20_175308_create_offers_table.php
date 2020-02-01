<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOffersTable extends Migration {

	public function up()
	{
		Schema::create('offers', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('restaurant_id')->unsigned();
			$table->text('image');
			$table->text('description');
			$table->date('from');
			$table->date('to');
			$table->string('title');
		});
	}

	public function down()
	{
		Schema::drop('offers');
	}
}