<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Blogcomments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('blogcomments', function (Blueprint $table) {
    		$table->increments('id');
    		$table->integer('blog_id')->unsigned()->default(0);
    		$table->integer('user_id')->unsigned()->default(0);
    		$table->text('body');
    		$table->timestamps();
    	
    		$table->foreign('blog_id')
    			->references('id')
    			->on('blogs')
    			->onDelete('cascade');
    		
    		$table->foreign('user_id')
    			->references('id')
    			->on('users')
    			->onDelete('cascade');
    	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('blogcomments');
    }
}
