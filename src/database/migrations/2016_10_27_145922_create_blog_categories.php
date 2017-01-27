<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('tags')) {
            Schema::create('tags', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->timestamps();
            });

            Schema::create('blog_tag', function (Blueprint $table) {
                $table->integer('blog_id')->unsigned()->index();
                $table->foreign('blog_id')->references('id')->on('blogs')->onDelete('cascade');

                $table->integer('tag_id')->unsigned()->index();
                $table->foreign('tag_id')->references('id')->on('tags');

                $table->timestamps();
            });
        }

        // seed the database
        $tag = DB::table('tags')->where('name', 'test')->first();
        if (!count($tag)) {
            DB::table('tags')->insert([
                'name' => 'test',
            ]);
        }

        $blog = DB::table('blogs')->first();
        if (!count($blog)) {
            DB::table('blogs')->insert([
                'user_id' => 1,
                'title' => 'First Post',
                'slug' => 'first-post',
                'body' => 'First post. For test purposes.',
                'published' => 1,
                'published_at' => Carbon::now(),
            ]);
        }

        $blogtag = DB::table('blog_tag')->first();
        if(!count($blogtag)){
            DB::table('blog_tag')->insert([
                'blog_id' => 1,
                'tag_id' => 1,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_tags');
        Schema::dropIfExists('tags');
    }
}
