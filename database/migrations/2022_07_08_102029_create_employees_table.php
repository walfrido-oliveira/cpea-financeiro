<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            $table->foreignId('occupation_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('direction_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->foreign('manager_id')->references('id')->on('users');

            $table->string('occupation_type')->nullable();
            $table->string('employee_id')->nullable();
            $table->timestamp('admitted_at', 0)->nullable();
            $table->integer('working_day')->default(0);

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
        Schema::dropIfExists('employees');
    }
}
