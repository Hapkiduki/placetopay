<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('transactionID');
            $table->string('sessionID');
			$table->string('returnCode');
			$table->string('trazabilityCode')->nullable();
			$table->integer('transactionCycle')->nullable();
			$table->string('bankCurrency',3)->nullable();
			$table->string('bankURL',255)->nullable();
			$table->integer('responseCode')->nullable();
			$table->float('bankFactor', 9, 2)->nullable();
			$table->string('responseReasonCode')->nullable();
			$table->string('responseReasonText',255)->nullable();
			$table->string('transactionState',20)->default('UNDEFINED');
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
        Schema::dropIfExists('transactions');
    }
}
