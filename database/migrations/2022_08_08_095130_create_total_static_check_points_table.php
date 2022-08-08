<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTotalStaticCheckPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('total_static_check_points', function (Blueprint $table) {
            $table->id();
            $table->integer('year')->index('ano');
            $table->integer('month')->index('month');
            $table->string('type')->index('type');
            $table->string('classification');
            $table->integer('classification_id')->index('classification_id');
            $table->decimal('result');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('total_static_check_points');
    }
}
