<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnityColumnToAccountingClassifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounting_classifications', function (Blueprint $table) {
            $table->string('unity')->index('unity')->default('R$');
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
            $table->dropColumn('unity');
        });
    }
}
