<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTorDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tor_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('elementary_course_completed_at')->nullable();
            $table->string('elementary_year')->nullable();
            $table->string("elem_gwa")->nullable();
            $table->string('jhs_course_completed_at')->nullable();
            $table->string('jhs_year')->nullable();
            $table->string("jhs_gwa")->nullable();
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
        Schema::dropIfExists('tor_details');
    }
}
