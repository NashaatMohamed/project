<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_variation_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger("invoice_id")->nullable();
            $table->foreignId("company_id")->nullable()->constrained("companies")->nullOnDelete();
            $table->foreignId("product_id")->nullable()->constrained("products")->nullOnDelete();
            $table->integer('quantity')->nullable();
            $table->string('type')->comment("1=>in, 2=>out"); // ده معناه انه هيكون فيه حركة دخول او حركة خروج للمخزن

            $table->string('reference')->nullable()->comment("example: purchase invoice Number or sales Invoice Number");
            $table->string('reference_type')->nullable()->comment("1=>sale;2=>purchase;3=>returned;4=>manual Edit;5=>damage");

            $table->foreign('product_variation_id')->references('id')->on('product_variations')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign("invoice_id")->references("id")->on("invoices")->nullOnDelete();
            $table->index('product_variation_id');
            $table->index("user_id");
            $table->index("reference_type");
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
        Schema::dropIfExists('stock_movements');
    }
}
