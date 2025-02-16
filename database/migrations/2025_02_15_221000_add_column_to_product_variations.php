<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToProductVariations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_variations', function (Blueprint $table) {
            $table->foreignId("variation_attribute_id")->after("variation_id")->nullable()->constrained("variation_attributes");
            $table->foreignId("parent_id")->after("variation_attribute_id")->nullable()->constrained("product_variations")->nullOnDelete();
            $table->foreignId("company_id")->after("parent_id")->nullable()->constrained("companies")->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_variations', function (Blueprint $table) {

            $table->dropForeign(["variation_attribute_id"]);
            $table->dropColumn("variation_attribute_id");
            $table->dropColumn("colors");
            $table->dropForeign(["parent_id"]);
            $table->dropColumn("parent_id");
            $table->dropForeign(["company_id"]);
            $table->dropColumn("company_id");
        });
    }
}
