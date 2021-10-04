<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateStudentInfoSchoolRank extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('student_info_school_ranks', function(Blueprint $table) {
            $table->string('others_field')->nullable();
            $table->integer('open_house')->nullable();
            $table->integer('social_media')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('student_info_school_ranks', function(Blueprint $table) {
            $table->dropColumn('others_field');
            $table->dropColumn('open_house');
            $table->dropColumn('social_media');
        });
    }
}
