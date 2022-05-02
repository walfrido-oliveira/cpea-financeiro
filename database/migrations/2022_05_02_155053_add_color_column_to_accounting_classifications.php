<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColorColumnToAccountingClassifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounting_classifications', function (Blueprint $table) {
            $table->string("color")->nullable();
            $table->boolean("bolder")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounting_classifications', function (Blueprint $table) {
            $table->dropColumn("color", "bolder");
        });
    }
}
