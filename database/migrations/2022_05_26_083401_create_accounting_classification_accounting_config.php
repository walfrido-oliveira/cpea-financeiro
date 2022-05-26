<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountingClassificationAccountingConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_classification_accounting_config', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('accounting_classification_id');
            $table->unsignedBigInteger('accounting_config_id');

            $table->foreign('accounting_classification_id', 'acac_accounting_classification_id_foreign')->references('id')->on('accounting_classifications')->onDelete('cascade');
            $table->foreign('accounting_config_id', 'acac_accounting_config_id_foreign')->references('id')->on('accounting_configs')->onDelete('cascade');

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
        Schema::dropIfExists('accounting_classification_accounting_config');
    }
}
