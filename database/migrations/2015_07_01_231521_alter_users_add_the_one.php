<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersAddTheOne extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
      Schema::table('users', function($table){
              $table->integer('the_one');
              });
  }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
