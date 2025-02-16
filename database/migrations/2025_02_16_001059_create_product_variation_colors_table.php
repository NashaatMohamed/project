<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariationColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variation_colors', function (Blueprint $table) {
            $table->id();
            $table->foreignId("product_variation_id")->nullable()->constrained("product_variations")->cascadeOnDelete();
            $table->string("color",127)->nullable();
            $table->string("hex",127)->nullable();
            $table->string("image",127)->nullable();
            $table->string("quantity")->default(0);
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
        Schema::dropIfExists('product_variation_colors');
    }
}
