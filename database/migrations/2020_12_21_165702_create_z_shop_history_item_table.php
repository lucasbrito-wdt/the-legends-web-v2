<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZShopHistoryItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('z_shop_history_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('to_name')->nullable();
            $table->integer('to_account')->nullable();
            $table->string('from_nick')->nullable();
            $table->integer('from_account')->nullable();
            $table->integer('price')->nullable();
            $table->string('offer_id')->nullable();
            $table->string('trans_state')->nullable();
            $table->integer('trans_start')->nullable();
            $table->integer('trans_real')->nullable();
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
        Schema::dropIfExists('z_shop_history_item');
    }
}
