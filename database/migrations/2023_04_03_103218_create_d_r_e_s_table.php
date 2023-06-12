<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDRESTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accounting_classification_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer("month");
            $table->integer("year");
            $table->decimal('value', 10, 2)->nullable();
            $table->string('justification');
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
        Schema::dropIfExists('dres');
    }
}
