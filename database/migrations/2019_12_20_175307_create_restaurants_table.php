<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRestaurantsTable extends Migration {

	public function up()
	{
		Schema::create('restaurants', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name');
			$table->string('email');
			$table->string('phone');
			$table->string('password');
			$table->float('minimum_charge');
			$table->float('delivery_fees');
			$table->boolean('availability');
			$table->string('whatsapp');
			$table->text('image');
			$table->string('api_token', 60)->unique()->nullable();
			$table->mediumInteger('pin_code')->nullable();
			$table->integer('region_id')->unsigned();
			$table->boolean('is_active')->default(1);
		});
	}

	public function down()
	{
		Schema::drop('restaurants');
	}
}