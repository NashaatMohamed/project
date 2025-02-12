<?php

// database/migrations/2024_09_22_000000_create_variations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('variations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 190);
            $table->unsignedBigInteger('company_id');
            $table->integer('sort')->default(0);
            $table->timestamps();

            // Foreign Key Constraint
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('variations');
    }
}