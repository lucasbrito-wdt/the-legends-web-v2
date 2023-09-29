<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZForumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('z_forum', function (Blueprint $table) {
            $table->id();
            $table->integer('first_post')->nullable();
            $table->integer('last_post')->nullable();
            $table->integer('section')->nullable();
            $table->integer('replies')->nullable();
            $table->integer('views')->nullable();
            $table->integer('author_aid')->nullable();
            $table->integer('author_guid')->nullable();
            $table->text('post_text')->nullable();
            $table->string('post_topic')->nullable();
            $table->tinyInteger('post_smile')->nullable();
            $table->integer('post_date')->nullable();
            $table->integer('last_edit_aid')->nullable();
            $table->integer('edit_date')->nullable();
            $table->ipAddress('post_ip')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('z_forum');
    }
}
