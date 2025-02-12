<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string("name",127)->nullable()->default("");
            $table->string("slug",127)->nullable()->default("");
            $table->string("email",127)->nullable()->default("");
            $table->string("phone",127)->nullable()->default("");
            $table->string("logo",127)->nullable()->default("");
            $table->boolean("active")->default(1)->nullable();
            $table->foreignId("company_id")->nullable()->constrained();
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
        Schema::dropIfExists('warehouse');
    }
}
