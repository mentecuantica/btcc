<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
          //  $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('user_id'); //->references('id')->on('users');
            $table->integer('amount');
            $table->integer('type');
            $table->integer('status');
            $table->integer('sender_id');
            $table->integer('reciever_id');
            $table->boolean('debit_flag');
            $table->boolean('credit_flag');
            $table->string('comment')->nullable();
            $table->string('hash')->nullable();
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
        Schema::drop('users_transactions');
    }
}
