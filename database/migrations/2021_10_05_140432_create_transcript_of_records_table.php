<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranscriptOfRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transcript_of_records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('school_name');
            $table->string('subject_code');
            $table->string('subject_name');
            $table->string('card_name');
            $table->string('group')->nullable();
            $table->string('level');
            $table->string('units');
            $table->string('school_year');
            $table->string('period')->nullable();
            $table->decimal("first_grading",7,2)->nullable();
            $table->decimal("second_grading",7,2)->nullable();
            $table->decimal("third_grading",7,2)->nullable();
            $table->decimal("fourth_grading",7,2)->nullable();
            $table->decimal("final_grade",7,2)->nullable();
            $table->string("first_grading_letter")->nullable();
            $table->string("second_grading_letter")->nullable();
            $table->string("third_grading_letter")->nullable();
            $table->string("fourth_grading_letter")->nullable();
            $table->string("final_grade_letter")->nullable();
            $table->timestamps();
             $table->foreign('idno')
                    ->references('idno')->on('users')
                    ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transcript_of_records');
    }
}
