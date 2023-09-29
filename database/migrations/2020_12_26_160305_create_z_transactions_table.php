<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('z_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('account_id')->nullable();
            $table->string('name')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('item_count')->nullable();
            $table->string('points')->nullable();
            $table->string('reference_code')->nullable();
            $table->string('transaction_code')->nullable();
            $table->string('status')->nullable()->default('Pagamento não efetuado');
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
        Schema::dropIfExists('z_transactions');
    }
}
