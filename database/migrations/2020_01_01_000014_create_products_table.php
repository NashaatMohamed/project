<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uid')->unique();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
             

            $table->unsignedBigInteger('currency_id')->nullable();
            $table->float('price')->nullable()->default(0);

            $table->string('name');
            $table->string('description')->nullable();

            

            // الأعمدة المضافة من الترحيل الآخر
            $table->string('cover', 127)->nullable()->default('');
            $table->longText('images')->nullable();
            $table->float('quantity_alarm')->nullable()->default(0);
            $table->string('opening_stock', 127)->nullable()->default('');
            // $table->date('opening_stock_date')->nullable();

            $table->foreignId('category_id')->nullable()->constrained('product_categories');
            $table->foreignId('brand_id')->nullable()->constrained('product_brands');
            $table->unsignedBigInteger('warehouse_id')->nullable();

            $table->string('code', 127)->nullable()->default('');
            $table->string('barcode', 127)->nullable()->default('');

            // $table->float('sale_price')->nullable()->default(0);
            // $table->float('purchase_price')->nullable()->default(0);
            // $table->float('mrp')->nullable()->default(0);

            $table->timestamps();

            // تعريف المفاتيح الأجنبية
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->foreign('unit_id')->references('id')->on('product_units');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}