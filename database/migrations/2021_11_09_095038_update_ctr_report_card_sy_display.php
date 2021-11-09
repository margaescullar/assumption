<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCtrReportCardSyDisplay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('ctr_report_card_sy_displays', function(Blueprint $table) {
            $table->integer('is_open')->default(0);
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
        Schema::table('ctr_report_card_sy_displays', function(Blueprint $table) {
            $table->dropColumn('is_open');
        });
    }
}
