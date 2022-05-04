<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthFormulaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('month_formulas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('formula_id')->index("formula_id");#->constrained()->onDelete('cascade');
            $table->integer("month")->index("month");
            $table->timestamps();

            #$table->foreign('formula_id')->references('id')->on('formulas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('month_formulas');
    }
}
