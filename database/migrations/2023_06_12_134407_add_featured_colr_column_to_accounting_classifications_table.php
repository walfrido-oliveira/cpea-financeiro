<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeaturedColrColumnToAccountingClassificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounting_classifications', function (Blueprint $table) {
            $table->string('featured_color')->nullable();
            $table->string('initial_state')->default('open');
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
            $table->dropColumn(['featured_color']);
        });
    }
}
