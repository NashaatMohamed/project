<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTemplateIdToCreditNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credit_notes', function (Blueprint $table) {
            $table->unsignedBigInteger('template_id')->nullable();
            $table->foreign('template_id')->references('id')->on('credit_note_templates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credit_notes', function (Blueprint $table) {
            $table->dropColumn('template_id');
        });
    }
}
