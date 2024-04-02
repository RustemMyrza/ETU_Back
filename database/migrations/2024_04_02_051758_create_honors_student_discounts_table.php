<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHonorsStudentDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('honors_student_discounts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('category');
            $table->string('amount');
            $table->string('note')->nullable();
            $table->string('gpa')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('honors_student_discounts');
    }
}
