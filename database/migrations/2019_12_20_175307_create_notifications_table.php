<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotificationsTable extends Migration {

	public function up()
	{
		Schema::create('notifications', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->mediumText('title');
			$table->text('body');
			$table->boolean('is_read')->default(0);
			$table->integer('order_id')->unsigned()->nullable();
			$table->integer('notifiable_id');
			$table->string('notifiable_type');
		});
	}

	public function down()
	{
		Schema::drop('notifications');
	}
}