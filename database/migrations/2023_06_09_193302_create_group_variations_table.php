<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId("variations_id")->nullable()
                ->constrained("variations")
                ->nullOnDelete();

            $table->unsignedInteger("variation_group_id")->nullable();
//            $table->foreignId("variation_group_id")->nullable()
//                ->constrained("variation_groups")
//                ->nullOnDelete(); // Removes the incorrect `name()` and sets NULL on delete

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
        Schema::dropIfExists('group_variations');
    }
}
