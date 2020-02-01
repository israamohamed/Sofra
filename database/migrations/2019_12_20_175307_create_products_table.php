<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration {

	public function up()
	{
		Schema::create('products', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('restaurant_id')->unsigned();
			$table->string('name');
			$table->mediumText('description')->nullable();
			$table->float('price');
			$table->float('offer_price')->nullable();
			$table->integer('preparation_time');
			$table->text('image');
		});
	}

	public function down()
	{
		Schema::drop('products');
	}
}