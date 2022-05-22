<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('id_customer');
            $table->integer('id_reference_type_transaction');
            $table->integer('id_reference_type_of_payment');
            $table->integer('id_package');
            $table->integer('amount');
            $table->integer('id_reference_tax');
            $table->string('affiliation',255);
            $table->integer('id_no_rekening');
            $table->integer('id_user');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction');
    }
}
