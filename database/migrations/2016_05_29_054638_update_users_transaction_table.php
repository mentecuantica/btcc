<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class UpdateUsersTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_transactions', function (Blueprint $table) {
          $table->boolean('credit_flag')->nullable();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_transactions', function (Blueprint $table) {
            $table->dropColumn('credit_flag');
        });
    }
}
