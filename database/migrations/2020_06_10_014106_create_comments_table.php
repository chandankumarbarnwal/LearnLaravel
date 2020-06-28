<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            if(env('DB_CONNECTION') == 'sqlite_testing'){
                $table->text('content')->default('');
            }else{
                $table->text('content');
            }
            $table->unsignedBigInteger('blog_post_id')->index();
            $table->foreign('blog_post_id')->references('id')->on('blog_posts');

        });
    }
 
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['blog_post_id']);
        });
        Schema::dropIfExists('comments');
    }
}
