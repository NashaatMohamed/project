<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAmountToDecraseToWithdrawRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('withdraw_requests', function (Blueprint $table) {
            $table->decimal('amount_to_decrease', 64, 0)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('withdraw_requests', function (Blueprint $table) {
            $table->dropColumn('amount_to_decrease');
        });
    }
}
