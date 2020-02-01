<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration {

	public function up()
	{
		Schema::create('orders', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('client_id')->unsigned();
			$table->integer('restaurant_id')->unsigned();
			$table->text('address');
			$table->text('notes')->nullable();
			$table->integer('payment_method_id')->unsigned();
			$table->float('cost')->default('0.0');
			$table->float('delivery_cost')->default('0.0');
			$table->float('total_cost')->default('0.0');
			$table->float('commission')->default('0.0');
			$table->float('net')->default('0.0');
			$table->enum('state', array('pending', 'accepted', 'rejected', 'declined', 'delivered', 'confirmed'));
		});
	}

	public function down()
	{
		Schema::drop('orders');
	}
}