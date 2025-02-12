<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariationGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variation_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->integer('sort')->nullable();
            $table->timestamps();

            // مفتاح أجنبي للشركة
            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.

     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('variation_groups');
    }
}