<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBlogComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('blogcomments', 'parent_comment_id')) {
            Schema::table('blogcomments', function (Blueprint $table) {
                $table->integer('parent_comment_id')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blogcomments', function (Blueprint $table) {
            $table->dropColumn('parent_comment_id');
        });
    }
}
