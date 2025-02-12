<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecurringOptionsToExpenses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->integer('is_recurring')->default(0);
            $table->integer('cycle')->nullable();
            $table->date('next_recurring_at')->nullable();
            $table->unsignedBigInteger('parent_expense_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('is_recurring');
            $table->dropColumn('cycle');
            $table->dropColumn('next_recurring_at');
            $table->dropColumn('parent_expense_id');
        });
    }
}
