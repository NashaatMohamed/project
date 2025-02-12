<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditNoteRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_note_refunds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('credit_note_id');
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->date('refund_date');
            $table->unsignedBigInteger('amount');
            $table->text('notes')->nullable(); 
            $table->timestamps();

            $table->foreign('credit_note_id')->references('id')->on('credit_notes')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_note_refunds');
    }
}
