<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormulaAccountingConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_config_formula', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('formula_id');
            $table->unsignedBigInteger('accounting_config_id');

            $table->foreign('formula_id', 'fac_formula_id_foreign')->references('id')->on('formulas')->onDelete('cascade');
            $table->foreign('accounting_config_id', 'fac_accounting_config_id_foreign')->references('id')->on('accounting_configs')->onDelete('cascade');

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
        Schema::dropIfExists('accounting_config_formula');
    }
}
