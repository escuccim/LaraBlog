<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::table('users', function (Blueprint $table) {
	    	$table->integer('type')->unsigned()->default(0);
    		$table->boolean('active')->default(1);
    		$table->string('image')->nullable();
    	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::table('users', function (Blueprint $table) {
    		$table->dropColumn('active');
    		$table->dropColumn('type');
    		$table->dropColumn('image');
    	});
    }
}
