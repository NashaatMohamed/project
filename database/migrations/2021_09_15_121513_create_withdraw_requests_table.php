<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraw_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uid');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('withdraw_account_id')->nullable();
            $table->unsignedBigInteger('wallet_id')->nullable();
            $table->unsignedBigInteger('requested_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->string('wallet_currency');
            $table->string('amount_to_deposit');
            $table->text('notes')->nullable();
            $table->tinyInteger('status');
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('declined_at')->nullable();
            $table->text('declined_reason')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('withdraw_account_id')->references('id')->on('withdraw_accounts')->onDelete('cascade');
            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withdraw_requests');
    }
}
