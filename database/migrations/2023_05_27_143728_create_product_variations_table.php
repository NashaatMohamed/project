<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();

            // العلاقة مع جدول المنتجات
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');

            // السعر
            $table->decimal('price', 10, 2)->default(0);

            // معرف الضريبة
            $table->foreignId('tax_id')->nullable()->constrained('taxes')->onDelete('set null');

            // الخصم
            $table->decimal('discount', 10, 2)->nullable()->default(0);

            // الصورة
            $table->string('image', 127)->nullable()->default('');

            // الكمية
            $table->decimal('quantity', 10, 2)->nullable()->default(0);

            // SKU
            $table->string('sku', 127)->nullable()->default('');

            // الحقل الجديد: معرف التغيير (variation_id)
            $table->unsignedBigInteger('variation_id')->nullable();
            // إذا كان هناك جدول للتغييرات، يمكنك إضافة العلاقة الأجنبية
            $table->foreign('variation_id')->references('id')->on('variations')->onDelete('set null');

            // الحقل الجديد: ضريبة القيمة المضافة (vat)
            $table->unsignedBigInteger('vat')->nullable();

            // حقل لتخزين بيانات التغييرات بصيغة JSON (اختياري)
            $table->json('variations_json')->nullable();

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
        Schema::dropIfExists('product_variations');
    }
}