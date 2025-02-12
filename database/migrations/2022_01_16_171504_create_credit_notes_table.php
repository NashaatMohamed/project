<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uid')->unique();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->date('credit_note_date');
            $table->string('credit_note_number');
            $table->string('reference_number')->nullable();
            $table->string('tax_per_item');
            $table->string('discount_per_item');
            $table->string('status');
            $table->boolean('is_archived')->default(false);
            $table->text('notes')->nullable();
            $table->text('private_notes')->nullable();
            $table->string('discount_type')->nullable();
            $table->unsignedBigInteger('discount_val')->nullable();
            $table->unsignedBigInteger('sub_total');
            $table->unsignedBigInteger('total');
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_notes');
    }
}
