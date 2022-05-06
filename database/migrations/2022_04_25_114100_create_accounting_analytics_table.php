<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountingAnalyticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accounting_control_id')->constrained()->onDelete('cascade');
            $table->foreignId('accounting_classification_id')->constrained()->onDelete('cascade');

            $table->decimal('value', 10, 2);
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
        Schema::dropIfExists('accounting_analytics');
    }
}
