<?php

// database/migrations/2024_09_22_000001_create_variation_attributes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariationAttributesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('variation_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 190);
            $table->unsignedBigInteger('variation_id');
            $table->unsignedBigInteger('company_id');
            $table->integer('sort')->default(0);
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('variation_id')->references('id')->on('variations')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('variation_attributes');
    }
}