<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZShopOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('z_shop_offer', function (Blueprint $table) {
            $table->id();
            $table->integer('points')->nullable();
            $table->integer('itemid1')->nullable();
            $table->integer('count1')->nullable();
            $table->integer('itemid2')->nullable();
            $table->integer('count2')->nullable();
            $table->string('offer_type');
            $table->text('offer_description')->nullable();
            $table->string('offer_name')->nullable();
            $table->integer('pid')->nullable();
            $table->integer('bought')->nullable();
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
        Schema::dropIfExists('z_shop_offer');
    }
}
