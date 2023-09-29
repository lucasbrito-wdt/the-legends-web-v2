<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZOtsComunicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('z_ots_comunication', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable();
            $table->text('type')->nullable();
            $table->text('action')->nullable();
            $table->text('param1')->nullable();
            $table->text('param2')->nullable();
            $table->text('param3')->nullable();
            $table->text('param4')->nullable();
            $table->text('param5')->nullable();
            $table->text('param6')->nullable();
            $table->text('param7')->nullable();
            $table->integer('delete_it')->default(1);
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
        Schema::dropIfExists('z_ots_comunication');
    }
}
