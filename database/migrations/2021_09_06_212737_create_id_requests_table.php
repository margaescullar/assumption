<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('id_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_id');
            $table->string('idno');
            $table->string('or_number')->nullable();
            $table->decimal('amount_pay',10,2)->default(0.00);
            $table->string('claiming_date')->nullable();
            $table->date('claim_date')->nullable();
            $table->string('status')->default(0);
            $table->timestamps();
            
            
            $table->foreign('idno')->references('idno')
                    ->on('users')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('id_requests');
    }
}
