<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable extends Migration {

	public function up()
	{
		Schema::create('settings', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->float('commission')->nullable();
			$table->text('commission_text')->nullable();
			$table->text('banks_text')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('settings');
	}
}