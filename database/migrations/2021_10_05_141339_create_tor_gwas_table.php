<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTorGwasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tor_gwas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('level');
            $table->string('strand')->nullable();
            $table->string('school_year')->nullable();
            $table->string('period')->nullable();
            $table->string("gwa_letter")->nullable();
            $table->decimal("gwa",7,3)->nullable();
            $table->string("final_gwa_letter")->nullable();
            $table->decimal("final_gwa",7,3)->nullable();
            $table->integer('days_of_school')->nullable();
            $table->integer('days_present')->nullable();
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
        Schema::dropIfExists('tor_gwas');
    }
}
