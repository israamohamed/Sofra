<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;


class EditEnumColumnInTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::table('tokens', function (Blueprint $table) {
            $table->enum('platform' , ['android' , 'ios'])->change();
        });*/
        DB::statement("ALTER TABLE tokens CHANGE COLUMN platform platform ENUM('android', 'ios')");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tokens', function (Blueprint $table) {
            //
        });
    }
}
